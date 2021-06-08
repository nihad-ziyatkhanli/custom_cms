<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Custom\Traits\ConstructsPages;
use App\MenuItem;
use App\Http\Requests\MenuItem\{Add, Edit};

class MenuItemController extends Controller
{
    use ConstructsPages;

    private $model_class = MenuItem::class;
    
    public function browse()
    {
    	if (auth()->user()->cant('browse', $this->model_class))
    		return view('admin.unauthorized', $this->shared);

    	$menu_items = MenuItem::browse();

    	return view($this->view.'.browse', array_merge($this->shared, [
            'menu_items' => $menu_items,
            'model_class' => $this->model_class,
        ]));
    }

    public function read()
    {
    	//
    }

    public function add()
    {
        if (auth()->user()->cant('add', $this->model_class))
    		return view('admin.unauthorized', $this->shared);
    	
        $parents = MenuItem::validParents()->orderBy('title', 'ASC')->get();
        
    	return view($this->view.'.add', array_merge($this->shared, [
            'parents' => $parents,
        ]));
    }

    public function add_do(Add $request)
    {
        if (auth()->user()->cant('add', $this->model_class))
            return back();

        $validated = $request->validated();

        try {
            MenuItem::create($validated);
        } catch (QueryException $e) {
            return back()->with('fail', 'Error occured.');
        }

        return redirect()->route($this->route_name)->with('success', 'A new record has been added.');
    }

    public function edit($id)
    {
        if (auth()->user()->cant('edit', $this->model_class))
            return view('admin.unauthorized', $this->shared);

        $item = MenuItem::with('children')->findOrFail($id);

        $parents = ($item->children->isEmpty())
            ? MenuItem::validParents($item)->orderBy('title', 'ASC')->get()
            : collect();

        return view($this->view.'.edit', array_merge($this->shared, [
            'parents' => $parents,
            'item' => $item,
        ]));
    }

    public function edit_do(Edit $request, $id)
    {
        if (auth()->user()->cant('edit', $this->model_class))
            return back();

        $item = MenuItem::findOrFail($id);

        $validated = $request->validated();

        try {
            $item->update($validated);
        } catch (QueryException $e) {
            return back()->with('fail', 'Error occured.');
        }

        return redirect()->route($this->route_name)->with('success', 'The record has been edited.');
    }

    public function delete($id)
    {
        if (auth()->user()->cant('delete', $this->model_class))
            return view('admin.unauthorized', $this->shared);

        $item = MenuItem::with('parent', 'children')->findOrFail($id);

        return view($this->view.'.delete', array_merge($this->shared, [
            'item' => $item,
        ]));
    }

    public function delete_do($id)
    {
        if (auth()->user()->cant('delete', $this->model_class))
            return back();
        
        $item = MenuItem::findOrFail($id);

        try {
            $item->delete();
        } catch (QueryException $e) {
            return back()->with('fail', 'Error occured.');
        }

        return redirect()->route($this->route_name)->with('success', 'The record has been deleted.');
    }
}

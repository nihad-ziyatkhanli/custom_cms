<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use App\Custom\Traits\ConstructsPages;
use App\Category;
use App\Http\Requests\Category\{Add, Edit};

class CategoryController extends Controller
{
    use ConstructsPages;
    
    public function browse()
    {
    	if (Gate::denies('browse'))
    		return view('admin.unauthorized', $this->shared);

    	$categories = Category::orderBy('id', 'DESC')->get();

    	return view($this->view.'.browse', array_merge($this->shared, [
            'categories' => $categories,
        ]));
    }

    public function read()
    {
    	//
    }

    public function add()
    {
        if (Gate::denies('add'))
    		return view('admin.unauthorized', $this->shared);
 
    	return view($this->view.'.add', array_merge($this->shared, [
        ]));
    }

    public function add_do(Add $request)
    {
        if (Gate::denies('add'))
            return back();

        $validated = $request->validated();

        try {
            Category::create($validated);
        } catch (QueryException $e) {
            return back()->with('fail', 'Error occured.');
        }

        return redirect()->route($this->route_name)->with('success', 'A new record has been added.');
    }

    public function edit($id)
    {
        if (Gate::denies('edit'))
            return view('admin.unauthorized', $this->shared);

        $category = Category::findOrFail($id);

        return view($this->view.'.edit', array_merge($this->shared, [
            'category' => $category,
        ]));
    }

    public function edit_do(Edit $request, $id)
    {
        if (Gate::denies('edit'))
            return back();

        $category = Category::findOrFail($id);

        $validated = $request->validated();

        try {
            $category->update($validated);
        } catch (QueryException $e) {
            return back()->with('fail', 'Error occured.');
        }

        return redirect()->route($this->route_name)->with('success', 'The record has been edited.');
    }

    public function delete($id)
    {
        if (Gate::denies('delete'))
            return view('admin.unauthorized', $this->shared);

        $category = Category::findOrFail($id);

        return view($this->view.'.delete', array_merge($this->shared, [
            'category' => $category,
        ]));
    }

    public function delete_do($id)
    {
        if (Gate::denies('delete'))
            return back();
        
        $category = Category::findOrFail($id);

        try {
            $category->delete();
        } catch (QueryException $e) {
            return back()->with('fail', 'Error occured.');
        }

        return redirect()->route($this->route_name)->with('success', 'The record has been deleted.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Custom\Traits\ConstructsPages;
use App\Link;
use App\Http\Requests\Link\{Add, Edit};

class LinkController extends Controller
{
    use ConstructsPages;
    
    public function updateProperties()
    {
        $this->shared = array_merge($this->shared, [
            'languages' => config('custom.languages'),
            'vms' => config('custom.visibility_modes'),
            'lgs' => config('custom.link_groups'),
        ]);
    }

    public function browse()
    {
        if (auth()->user()->cant('browse'))
    		return view('admin.unauthorized', $this->shared);

    	$links = Link::with('parent')->orderBy('id', 'DESC')->get();

    	$this->updateProperties();
        return view($this->view.'.browse', array_merge($this->shared, [
            'links' => $links,
        ]));
    }

    public function read()
    {
    	//
    }

    public function add()
    {
        if (auth()->user()->cant('add'))
    		return view('admin.unauthorized', $this->shared);

        $parent_old = Link::find((int) old('parent_id'));
        
    	$this->updateProperties();
        return view($this->view.'.add', array_merge($this->shared, [
            'locale' => old('locale') ?? config('app.locale'),
            'po' => $parent_old,
        ]));
    }

    public function add_do(Add $request)
    {
        if (auth()->user()->cant('add'))
            return back();

        $validated = $request->validated();

        try {
            Link::create($validated);
        } catch (QueryException $e) {
            return back()->with('fail', __('Error occured.'));
        }

        return redirect()->route($this->route_name)->with('success', __('A new record has been added.'));
    }

    public function edit($id)
    {
        if (auth()->user()->cant('edit'))
            return view('admin.unauthorized', $this->shared);

        $link = Link::with('parent')->findOrFail($id);

        $this->updateProperties();
        return view($this->view.'.edit', array_merge($this->shared, [
            'link' => $link,
        ]));
    }

    public function edit_do(Edit $request, $id)
    {
        if (auth()->user()->cant('edit'))
            return back();

        $link = Link::findOrFail($id);

        $validated = $request->validated();

        try {
            $link->update($validated);
        } catch (QueryException $e) {
            return back()->with('fail', __('Error occured.'));
        }

        return redirect()->route($this->route_name)->with('success', __('The record has been edited.'));
    }

    public function delete($id)
    {
        if (auth()->user()->cant('delete'))
            return view('admin.unauthorized', $this->shared);

        $link = Link::with('parent', 'children')->findOrFail($id);

        $this->updateProperties();
        return view($this->view.'.delete', array_merge($this->shared, [
            'link' => $link,
        ]));
    }

    public function delete_do(Request $request, $id)
    {
        if (auth()->user()->cant('delete'))
            return back();
        
        $link = Link::findOrFail($id);

        try {
            // transaction is not very necessary.
            if (!$request->cascade)
                $link->children()->update(['parent_id' => null]);
            $link->delete();
        } catch (QueryException $e) {
            return back()->with('fail', __('Error occured.'));
        }

        return redirect()->route($this->route_name)->with('success', __('The record has been deleted.'));
    }
}

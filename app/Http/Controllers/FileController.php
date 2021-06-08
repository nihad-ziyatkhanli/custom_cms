<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Custom\Traits\ConstructsPages;
use App\File;
use App\Http\Requests\File\{Add, Edit};

class FileController extends Controller
{
    use ConstructsPages;
    
    public function browse()
    {
    	if (Gate::denies('browse'))
    		return view('admin.unauthorized', $this->shared);

    	$files = auth()->user()->files()->with('user')->orderBy('id', 'DESC')->get();

    	return view($this->view.'.browse', array_merge($this->shared, [
            'files' => $files,
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
            File::saveUploadedFile($validated);
        } catch (QueryException $e) {
            return back()->with('fail', __('Error occured.'));
        }

        return redirect()->route($this->route_name)->with('success', __('A new record has been added.'));
    }

    public function edit($id)
    {
        $file = File::with('user.role')->findOrFail($id);

        if (auth()->user()->cant('edit', $file))
            return view('admin.unauthorized', $this->shared);

        return view($this->view.'.edit', array_merge($this->shared, [
            'file' => $file,
        ]));
    }

    public function edit_do(Edit $request, $id)
    {
        $file = File::with('user.role')->findOrFail($id);

        if (auth()->user()->cant('edit', $file))
            return back();

        $validated = $request->validated();
        
        try {
            $file->update($validated);
        } catch (QueryException $e) {
            return back()->with('fail', __('Error occured.'));
        }

        return redirect()->route($this->route_name)->with('success', __('The record has been edited.'));
    }

    public function delete($id)
    {
        $file = File::with('user.role')->findOrFail($id);

        if (auth()->user()->cant('delete', $file))
            return view('admin.unauthorized', $this->shared);

        return view($this->view.'.delete', array_merge($this->shared, [
            'file' => $file,
        ]));
    }

    public function delete_do($id)
    {
        $file = File::with('user.role')->findOrFail($id);

        if (auth()->user()->cant('delete', $file))
            return back();

        $path = $file->path;

        try {
            $file->delete();
            Storage::delete($path);
        } catch (QueryException $e) {
            return back()->with('fail', __('Error occured.'));
        }

        return redirect()->route($this->route_name)->with('success', __('The record has been deleted.'));
    }
}

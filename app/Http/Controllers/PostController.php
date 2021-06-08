<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use App\Custom\Traits\ConstructsPages;
use App\Custom\Services\PostService as PS;
use App\Post;
use App\Category;
use App\Http\Requests\Post\{Add, Edit};

class PostController extends Controller
{
    use ConstructsPages;

    protected function updateProperties()
    {
        $this->shared = array_merge($this->shared, [
            'languages' => config('custom.languages'),
            'vms' => config('custom.visibility_modes'),
            'categories' => Category::all(),
        ]);
    }
    
    public function browse()
    {
    	if (Gate::denies('browse'))
    		return view('admin.unauthorized', $this->shared);

    	return view($this->view.'.browse', $this->shared);
    }

    public function read()
    {
    	//
    }

    public function add()
    {
        if (Gate::denies('add'))
    		return view('admin.unauthorized', $this->shared);

        $locale = old('locale') ?? config('app.locale');
        $attachment_old = auth()->user()->attachments()->find((int) old('file_id'));
 
    	$this->updateProperties();
        return view($this->view.'.add', array_merge($this->shared, [
            'locale' => $locale,
            'ao' => $attachment_old,
        ]));
    }

    public function add_do(Add $request)
    {
        if (Gate::denies('add'))
            return back();

        $validated = $request->validated();

        try {
            $post = PS::create($validated);
        } catch (QueryException $e) {
            return back()->with('fail', __('Error occured.'));
        }

        return redirect()->route($this->route_name.'.edit', ['id' => $post->id])->with('success', __('A new record has been added.'))->withInput();
    }

    public function edit($id)
    {
        $post = Post::browsable()->with([
            'translations' => function ($query) {
                $query->where(function ($query) {
                    $query->where('is_default', '=', 1);
                    if (is_string(old('locale')))
                        $query->orWhere('locale', '=', old('locale'));
                })->orderBy('is_default', 'ASC');
            },
            'user.role',
            'file',
        ])->findOrFail($id);

        if (auth()->user()->cant('edit', $post))
            return view('admin.unauthorized', $this->shared);

        $pt = $post->translations->first();

        $this->updateProperties();
        return view($this->view.'.edit', array_merge($this->shared, [
            'post' => $post,
            'locale' => $pt->locale,
            'url' => url('read/'.$post->slug),
        ]));
    }

    public function edit_do(Edit $request, $id)
    {
        $post = Post::browsable()->with('user.role')->findOrFail($id);

        if (auth()->user()->cant('edit', $post))
            return back();

        $validated = $request->validated();
        $validated['pt_default'] = isset($request->pt_default);
        $validated['pt_delete'] = isset($request->pt_delete);

        try {
            PS::update($post, $validated);
        } catch (QueryException $e) {
            return back()->with('fail', __('Error occured.'));
        }

        return back()->with('success', __('The record has been edited.'))->withInput();
    }

    public function delete($id)
    {
        $post = Post::browsable()->with([
            'user.role',
            'translations',
            'category',
        ])->findOrFail($id);
        
        if (auth()->user()->cant('delete', $post))
            return view('admin.unauthorized', $this->shared);

        $this->updateProperties();
        return view($this->view.'.delete', array_merge($this->shared, [
            'post' => $post,
        ]));
    }

    public function delete_do($id)
    {
        $post = Post::browsable()->with('user.role')->findOrFail($id);

        if (auth()->user()->cant('delete', $post))
            return back();

        try {
            PS::delete($post);
        } catch (QueryException $e) {
            return back()->with('fail', __('Error occured.'));
        }

        return redirect()->route($this->route_name)->with('success', __('The record has been deleted.'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use App\Custom\Traits\ConstructsPages;
use App\User;
use App\Http\Requests\User\{Add, Edit};

class UserController extends Controller
{
    use ConstructsPages;
    
    public function browse()
    {
    	if (Gate::denies('browse'))
    		return view('admin.unauthorized', $this->shared);

    	$users = User::browse();

    	return view($this->view.'.browse', array_merge($this->shared, [
            'users' => $users,
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
    	
        $roles = auth()->user()->role->assignables();
        
    	return view($this->view.'.add', array_merge($this->shared, [
            'roles' => $roles,
        ]));
    }

    public function add_do(Add $request)
    {
        if (Gate::denies('add'))
            return back();

        $validated = $request->validated();

        if (isset($request->verified)) 
            $validated['email_verified_at'] = Carbon::now();

        try {
            User::create($validated);
        } catch (QueryException $e) {
            return back()->with('fail', 'Error occured.');
        }

        return redirect()->route($this->route_name)->with('success', 'A new record has been added.');
    }

    public function edit($id)
    {
        $user = User::with('role')->findOrFail($id);

        if (auth()->user()->cant('edit', $user))
            return view('admin.unauthorized', $this->shared);

        $roles = auth()->user()->role->assignables($user->role);

        return view($this->view.'.edit', array_merge($this->shared, [
            'roles' => $roles,
            'user' => $user,
        ]));
    }

    public function edit_do(Edit $request, $id)
    {
        $user = User::findOrFail($id);

        if (auth()->user()->cant('edit', $user))
            return back();

        $validated = $request->validated();

        $validated['email_verified_at'] = isset($request->verified) ? Carbon::now() : null;
        $validated['expired'] = true;

        try {
            $user->update($validated);
        } catch (QueryException $e) {
            return back()->with('fail', 'Error occured.');
        }

        return redirect()->route($this->route_name)->with('success', 'The record has been edited.');
    }

    public function delete($id)
    {
        $user = User::with('role')->findOrFail($id);

        if (auth()->user()->cant('delete', $user))
            return view('admin.unauthorized', $this->shared);

        return view($this->view.'.delete', array_merge($this->shared, [
            'user' => $user,
        ]));
    }

    public function delete_do($id)
    {
        $user = User::findOrFail($id);

        if (auth()->user()->cant('delete', $user))
            return back();
        
        try {
            $user->delete();
        } catch (QueryException $e) {
            return back()->with('fail', 'Error occured.');
        }

        return redirect()->route($this->route_name)->with('success', 'The record has been deleted.');
    }
}

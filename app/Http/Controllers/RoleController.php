<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use App\Custom\Traits\ConstructsPages;
use App\Role;
use App\MenuItem;
use App\Permission;
use App\Http\Requests\Role\{Add, Edit, Attach};

class RoleController extends Controller
{
    use ConstructsPages;
    
    public function browse()
    {
    	if (Gate::denies('browse'))
    		return view('admin.unauthorized', $this->shared);

    	$roles = Role::browse();

    	return view($this->view.'.browse', array_merge($this->shared, [
            'roles' => $roles,
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
    	
    	return view($this->view.'.add', $this->shared);
    }

    public function add_do(Add $request)
    {
        if (Gate::denies('add'))
            return back();

        $validated = $request->validated();

        try {
            Role::create($validated);
        } catch (QueryException $e) {
            return back()->with('fail', 'Error occured.');
        }

        return redirect()->route($this->route_name)->with('success', 'A new record has been added.');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        if (auth()->user()->cant('edit', $role))
            return view('admin.unauthorized', $this->shared);

        return view($this->view.'.edit', array_merge($this->shared, [
            'role' => $role,
        ]));
    }

    public function edit_do(Edit $request, $id)
    {
        $role = Role::findOrFail($id);

        if (auth()->user()->cant('edit', $role))
            return back();

        $validated = $request->validated();

        try {
            $role->update($validated);
        } catch (QueryException $e) {
            return back()->with('fail', 'Error occured.');
        }

        return redirect()->route($this->route_name)->with('success', 'The record has been edited.');
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);

        if (auth()->user()->cant('delete', $role))
            return view('admin.unauthorized', $this->shared);

        return view($this->view.'.delete', array_merge($this->shared, [
            'role' => $role,
        ]));
    }

    public function delete_do($id)
    {
        $role = Role::findOrFail($id);

        if (auth()->user()->cant('delete', $role))
            return back();
        
        try {
            $role->delete();
        } catch (QueryException $e) {
            return back()->with('fail', 'Error occured.');
        }

        return redirect()->route($this->route_name)->with('success', 'The record has been deleted.');
    }

    public function attach($id)
    {
        $role = Role::with('rmps')->findOrFail($id);

        if (auth()->user()->cant('attach', $role))
            return view('admin.unauthorized', $this->shared);

        $menu_items = MenuItem::attachable();
        $permissions = Permission::browse();

        return view($this->view.'.attach', array_merge($this->shared, [
            'role' => $role,
            'menu_items' => $menu_items,
            'permissions' => $permissions,
        ]));
    }

    public function attach_do(Attach $request, $id)
    {
        $role = Role::with('rmps')->findOrFail($id);

        if (auth()->user()->cant('attach', $role))
            return back();

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $role->syncPermissions($validated);
            $role->users()->update(['expired' => true]);
            
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return back()->with('fail', 'Error occured.');
        }
        
        return redirect()->route($this->route_name)->with('success', 'Permissions have been set.');
    }
}

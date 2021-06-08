<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
	protected $table = 'roles';

    protected $fillable = [
        'title', 'code', 'rank', 'status',
    ];

    private $subordinates;
	
    /* Me: Defining relationships. */
    public function users()
    {
        return $this->hasMany('App\User', 'role_id');
    }

    public function rmps()
    {
        return $this->hasMany('App\RMP', 'role_id');
    }

    /* Me: Return this role's subordinates. */
    public function subordinates()
    {
        if(is_null($this->subordinates))
            $this->subordinates = self::where('rank', '>=', $this->rank)->orderBy('title', 'ASC')->get();

        return $this->subordinates;
    }

    /* Me: Return this role's enabled subordinates + passed role. */
    public function assignables(Role $role = null)
    {
        if (isset($role))
            $f = function ($sr) use ($role) {
                return $sr->status === 1 || $sr->id === $role->id;
            };
        else
            $f = function ($sr) {
                return $sr->status === 1;
            };

        return $this->subordinates()->filter($f);
    }

    /* Me: Return a collection of this model. */
    public static function browse()
    {
        return self::orderBy('id', 'DESC')->get();
    }

    /* Me: Attach selected models and detach rest. */
    public function syncPermissions($data)
    {
        $insert_arr = []; // format: [role_id, menu_item_id, permission_id]
        $delete_arr = []; // format: [menu_item_id => [permission_ids]]
        $permissions = isset($data['permissions']) ? $data['permissions'] : [];

        foreach ($this->rmps as $rmp) {
            if (!isset($permissions[$rmp->menu_item_id]) || !in_array($rmp->permission_id, $permissions[$rmp->menu_item_id]))
                $delete_arr[$rmp->menu_item_id][] = $rmp->permission_id;
        }

        foreach ($permissions as $menu_item_id => $permission_ids)
            foreach ($permission_ids as $permission_id)
                if ($this->rmps->filter(function ($rmp) use ($menu_item_id, $permission_id) {
                    return $rmp->menu_item_id == $menu_item_id && $rmp->permission_id == $permission_id;
                })->isEmpty())
                    $insert_arr[] = ['role_id' => $this->id, 'menu_item_id' => $menu_item_id, 'permission_id' => $permission_id];

        DB::beginTransaction();
        try {
            if ($delete_arr)
                RMP::where('role_id', '=', $this->id)->where(function ($query) use ($delete_arr) {

                    foreach ($delete_arr as $menu_item_id => $permission_ids) {
                        $query->orWhere(function ($query) use ($menu_item_id, $permission_ids) {

                            $query->where('menu_item_id', '=', $menu_item_id)->whereIn('permission_id', $permission_ids);

                        });     
                    }

                })->delete();

            RMP::insert($insert_arr);

            DB::commit();      
        } catch (QueryException $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

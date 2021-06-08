<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RMP extends Model
{
    protected $table='rmps';	

    /* Me: Defining relationships. */
    public function role()
    {
        return $this->belongsTo('App\Role', 'role_id');
    }
    public function permission()
    {
        return $this->belongsTo('App\Permission', 'permission_id');
    }
    public function menuItem()
    {
        return $this->belongsTo('App\MenuItem', 'menu_item_id');
    }
}

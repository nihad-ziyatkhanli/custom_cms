<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    /* Me: Defining relationships. */
    public function rmps()
    {
        return $this->hasMany('App\RMP', 'permission_id');
    }

    /* Me: Return a collection of this model. */
    public static function browse()
    {
        return self::orderBy('id', 'ASC')->get();
    }
}

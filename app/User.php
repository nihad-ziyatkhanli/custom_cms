<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use App\Permission;

class User extends Authenticatable
{
    use Notifiable;

    protected $table='users';

    protected $fillable = [
        'name', 'email', 'email_verified_at', 'password', 'role_id', 'expired',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* Me: Password mutator */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /* Me: Defining relationships. */
    public function role()
    {
        return $this->belongsTo('App\Role', 'role_id')->withDefault([
            'id' => 0,
            'title' => 'User',
            'rank' => 10000,
        ]);
    }

    public function files()
    {
        return $this->hasMany('App\File', 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany('App\File', 'user_id')->whereIn('mime_type', config('custom.image_mime_types'));
    }

    public function posts()
    {
        return $this->hasMany('App\Post', 'user_id');
    }

    /* Me: Return a collection of this model */
    public static function browse()
    {
        return self::with('role')->orderBy('id', 'DESC')->get();
    }

    /* Me: Return a collection of this model based on parameters. Joined to roles table for sorting. $data is assured to be valid. Recommended to move this function to a seperate service. */
    public static function browseAndCount($data)
    {
        $query = self::leftJoin('roles', 'roles.id', '=', 'users.role_id');

        $records_total = $query->count();

        foreach ($data['columns'] as $column)
            if ($column['data'] === 'role_title' && isset($column['search'])) {
                $query->where(function ($query) use ($column) {
                    $query->whereHas('role', function ($query) use ($column) {
                        $query->where('title', 'like', '%'.$column['search'].'%');
                    });
                    if(stripos('User', $column['search']) !== false)
                        $query->orDoesntHave('role');
                });
            }
            elseif (in_array($column['data'], [
                "name",
                "email",
                "email_verified_at",
                "created_at",
            ], true) && isset($column['search']))
                $query->where('users.'.$column['data'], 'like', '%'.$column['search'].'%');

        if (isset($data['search']))
            $query->where(function ($query) use ($data) {
                $query->whereHas('role', function ($query) use ($data) {
                    $query->where('title', 'like', '%'.$data['search'].'%');
                });
                if (stripos('User', $data['search']) !== false)
                    $query->orDoesntHave('role');
                foreach ([
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                ] as $t)
                    $query->orWhere('users.'.$t, 'like', '%'.$data['search'].'%');
            });

        $records_filtered = $query->count();

        foreach ($data['order'] as $o)
            if ($o['data'] === 'role_title')
                $query->orderBy('roles.title', $o['dir']);
            elseif (in_array($o['data'], [
                'name',
                'email',
                'email_verified_at',
                'created_at',
            ], true))
                $query->orderBy('users.'.$o['data'], $o['dir']);

        $records = $query->orderBy('users.id', 'DESC')->select('users.*')->with('role')->skip($data['start'])->take($data['length'])->get();

        return [
            'records_total' => $records_total,
            'records_filtered' => $records_filtered,
            'records' => $records,
        ];
    }

    /* Me: Returns a collection of MenuItem models that represents the user's menu. */
    public function getMenuData()
    {
        $role_id = $this->role->id;
        $permission_id = Permission::where('code', '=', 'browse')->firstOrFail()->id;

        $rmps = function ($query) use ($role_id, $permission_id) {
            $query->where('role_id', '=', $role_id)->where('permission_id', '=', $permission_id);
        };

        $menu_items = MenuItem::whereNull('parent_id')->where(function ($query) use ($rmps) {
            $query->whereHas('rmps', $rmps)->orWhereHas('children', function ($query) use ($rmps) {
                $query->whereHas('rmps', $rmps);
            });
        })->with([
            'children' => function ($query) use ($rmps) {
                $query->whereHas('rmps', $rmps)->orderBy('rank', 'ASC')->orderBy('id', 'ASC');
            },
        ])->orderBy('rank', 'ASC')->orderBy('id', 'ASC')->get();

        return $menu_items;
    }
}

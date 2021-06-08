<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Custom\Helpers\Helper;

class MenuItem extends Model
{
    protected $table = 'menu_items';

    protected $fillable = [
        'title', 'code', 'icon', 'parent_id', 'url', 'rank',
    ];

    /* Me: Defining relationships. */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function rmps()
    {
        return $this->hasMany('App\RMP', 'menu_item_id');
    }

    /* Me: Defining local scope */
    public function scopeValidParents($query, self $menu_item = null)
    {
        if ($menu_item)
            $query->where('id', '!=', $menu_item->id);
        return $query->whereNull('parent_id')->whereNull('url');
    }

   	/* Me: Returns a collection of this model with their parents loaded. */
    public static function browse()
    {
    	return self::with('parent')->orderBy('id', 'DESC')->get();
    }

    /* Me: Returns current MenuItem model based on URL */
    public static function current()
    {
        $url=Helper::parse(\Request::getPathInfo(), '/', 3);

        return self::where('url', '=', $url)->firstOrFail();
    }

    /* Me: Returns all menu items which have url or have children with url. */
    public static function attachable()
    {
        $has_url = function ($query) {
            $query->whereNotNull('url');
        };

        return self::whereNull('parent_id')->where(function ($query) use ($has_url) {
            $query->where($has_url)->orWhereHas('children', $has_url);
        })->with([
            'children' => function ($query) use ($has_url) {
                $query->where($has_url)->orderBy('rank', 'ASC')->orderBy('id', 'ASC');
            },
        ])->orderBy('rank', 'ASC')->orderBy('id', 'ASC')->get();
    }

    /* Me: For validation. Returns an array contains attachable menu item ids. */
    public static function attachableIds()
    {
        $attachables = self::attachable();
        $arr = [];

        foreach ($attachables as $item) {
            if ($item->children->isEmpty())
                $arr[] = $item->id;
            else
                foreach ($item->children as $child)
                    $arr[] = $child->id;
        }

        return $arr;
    }
}

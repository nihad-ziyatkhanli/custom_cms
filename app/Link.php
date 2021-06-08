<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $table = 'links';

    protected $fillable = [
        'locale', 'group', 'visibility', 'title', 'code', 'icon', 'parent_id', 'url', 'rank',
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

    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /* Me: Returns ids of links that can't be a parent to this link due to causing infinite loop. Includes ids of this link and its descendants. */
    public function causingLoopIds()
    {
        $arr = [$this->id];
        foreach ($this->descendants as $d)
            $arr = array_merge($arr, $d->causingLoopIds());
        return $arr;
    }

    /* Me: Defining local scope */
    public function scopeOfBase($query, $locale = null, $group = null)
    {
        if (isset($locale))
            $query->where('locale', '=', $locale);
        if (isset($group))
            $query->where('group', '=', $group);
        return $query;
    }
}

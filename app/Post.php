<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = [
        'slug', 'user_id', 'category_id', 'file_id', 'visibility',
    ];
	
    /* Me: Defining relationships. */
    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id')->withDefault([
            'id' => 0,
            'title' => 'Uncategorized',
        ]);
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id')->withDefault([
            'id' => 0,
            'name' => __('n/a'),
        ]);
    }

    public function file()
    {
        return $this->belongsTo('App\File', 'file_id')->withDefault([
            'id' => 0,
        ]);
    }

    public function translations()
    {
        return $this->hasMany('App\PostTranslation', 'post_id');
    }

    /* Me: Defining local scope */
    public function scopeBrowsable($query)
    {
        $subordinate_ids = auth()->user()->role->subordinates()->pluck('id')->all();

        return $query->whereHas('user.role', function ($query) use ($subordinate_ids) {
            $query->whereIn('id', $subordinate_ids);
        })->orDoesntHave('user.role')->orWhere('visibility', '>', 0);
    }
}

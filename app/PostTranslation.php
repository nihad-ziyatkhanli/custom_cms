<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    protected $table = 'post_translations';

    protected $fillable = [
        'locale', 'is_default', 'title', 'excerpt', 'content', 
    ];
}

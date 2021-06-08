<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Custom\Helpers\Helper;

class File extends Model
{
    protected $table = 'files';

    protected $fillable = [
        'title', 'caption', 'description',
    ];
	
    /* Me: Defining relationships. */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id')->withDefault([
            'id' => 0,
            'name' => __('n/a'),
        ]);
    }

    public function posts()
    {
        return $this->hasMany('App\Post', 'file_id');
    }

    /* Me: Checks if file exists in either storage or db. */
    public static function fileExists($path)
    {
    	return Storage::exists($path) || self::where('path', '=', $path)->exists();
    }

    /* Me: Makes a path for the uploaded file and saves it to the db. */
    public static function saveUploadedFile($data)
    {
    	$basename = $data['file']->getClientOriginalName();
    	$filename = pathinfo($basename, PATHINFO_FILENAME);
        $mime_type = $data['file']->getClientMimeType(); //->getMimeType() guesses mime type
        $dirname = date('Y').'/'.date('m');

        $path = Helper::makeFilePath($dirname, $basename, [self::class, 'fileExists']);

    	if ($data['file']->storeAs('', $path))
    	{
	    	$f = new self;
	    	$f->path = $path;
	    	$f->mime_type = $mime_type;
	    	$f->title = $filename;
	    	$f->user_id = auth()->user()->id;
	    	$f->save();
        }
    }
}

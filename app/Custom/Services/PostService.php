<?php

namespace App\Custom\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Post;

class PostService
{
    /* Me: Returns array containing count, filtered count, and a collection of Post model based on parameters. Joined to post_translations table for sorting. $data is assured to be valid. Passed argument's ($data) format: ['columns' => (array) $columns, 'order' => (array) $order, 'start' => (int) $start, 'length' => (int) $length, 'search' => (string) $search] */
    public static function browseAndCount($data)
    {
        $query = Post::leftJoin('post_translations as pts', 'pts.post_id', '=', 'posts.id');
        $query->where('pts.is_default', '=', '1');

        if(isset($data['show_all']))
            $query->browsable();
        else
            $query->where('user_id', '=', auth()->user()->id);

        $records_total = $query->count();

        foreach ($data['columns'] as $column)
            if ($column['data'] === 'title' && isset($column['search'])) {
                $query->whereHas('translations', function ($query) use ($column) {
                    $query->where('title', 'like', '%'.$column['search'].'%');
                });
            }

        if (isset($data['search']))
            $query->where(function ($query) use ($data) {
                $query->whereHas('category', function ($query) use ($data) {
                    $query->where('title', 'like', '%'.$data['search'].'%');
                });
                if (stripos('Uncategorized', $data['search']) !== false)
                    $query->orDoesntHave('category');
                $query->orWhereHas('user', function ($query) use ($data) {
                    $query->where('name', 'like', '%'.$data['search'].'%');
                });
                $query->orWhere('posts.created_at', 'like', '%'.$data['search'].'%');
            });

        $records_filtered = $query->count();

        foreach ($data['order'] as $o)
            if ($o['data'] === 'title')
                $query->orderBy('pts.title', $o['dir']);
            elseif (in_array($o['data'], [
            	'visibility',
                'created_at',
            ], true))
                $query->orderBy('posts.'.$o['data'], $o['dir']);

        $records = $query->orderBy('posts.id', 'DESC')->select('posts.*')->with([
            'translations' => function ($query) {
                $query->where('is_default', '=', 1);
            },
            'user.role',
            'category',
            'file',
        ])->skip($data['start'])->take($data['length'])->get();

        return [
        	'records_total' => $records_total,
        	'records_filtered' => $records_filtered,
        	'records' => $records,
        ];
    }

	public static function create($data)
	{
        $data['is_default'] = 1;
        $data['user_id'] = auth()->user()->id;

        DB::beginTransaction();
        try {
            $post = Post::create($data);
			$post->translations()->create($data);

            DB::commit();      
        } catch (QueryException $e) {
            DB::rollBack();
            throw $e;
        }

        return $post;
	}

	public static function update($post, $data)
	{   
        DB::beginTransaction();
        try {
            if ($data['pt_delete'])
                $post->translations()->where('locale', '=', $data['locale'])->where('is_default', '=', 0)->delete();
            else {
                if ($data['pt_default']) {
                    $post->translations()->where('locale', '!=', $data['locale'])->update(['is_default' => 0]);
                    $data['is_default'] = 1;
                }

                $post->translations()->updateOrCreate([
                    'locale' => $data['locale'],
                ], $data);
            }

            $post->update($data);

            DB::commit();  
        } catch (QueryException $e) {
            DB::rollBack();
            throw $e;
        }
	}

	public static function delete($post)
	{
		$post->delete();
	}
}
<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Custom\Traits\ConstructsPages;
use App\Custom\Services\PostService as PS;
use App\Custom\Services\DataTableService as DataTable;
use App\Custom\Services\ValidationService as Val;
use App\Custom\Helpers\Helper;
use App\Post;

class PostController extends Controller
{
    use ConstructsPages;

    public function browse(Request $request)
    {
        $rearranged = DataTable::rearrange($request->all());
        
        $result = PS::browseAndCount($rearranged);
        $posts = $result['records'];
        $auth_user = auth()->user();
        $vms = config('custom.visibility_modes');

        $data = [];

        foreach ($posts as $post) {
            $data[] = [
                'title' => [
                    'text' => $post->translations->first()->title ?? __('n/a'),
                    'url' => url('read/'.$post->slug),
                ],
                'category' => __($post->category->title),
                'user' => $post->user->name,
                'visibility' => ucfirst(__($vms[$post->visibility])),
                'file_url' => isset($post->file->path) ? Storage::url($post->file->path) : false,
                'created_at' => isset($post->created_at) ? $post->created_at->format('Y-m-d') : __('n/a'),
                'operations' => [
                    'edit' => $auth_user->can('edit', $post) ? route($this->route_name.'.edit', ['id' => $post->id]) : false,
                    'delete'=> $auth_user->can('delete', $post) ? route($this->route_name.'.delete', ['id' => $post->id]) : false,
                ],
            ];
        }
        
        return [
            'recordsTotal' => $result['records_total'],
            'recordsFiltered' => $result['records_filtered'],
            'data' => Helper::safe($data),
        ];
    }

    public function getAttachments(Request $request)
    {
        $p = auth()->user()->attachments();

        if ( isset($request->search) && is_string($request->search) ) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $request->search);
            $p->where('path', 'like', '%'.$search.'%' );
        }

        $p = $p->simplePaginate(15);

        $attachments = $p->items();

        $results = array_map(function ($file) {
            return [
                'id' => $file->id,
                'text' => pathinfo($file->path, PATHINFO_BASENAME),
                'url' => Storage::url($file->path),
            ];
        }, $attachments);

        return [
            'results' => $results,
            'pagination' => [
                'more' => $p->hasMorePages(),
            ],
        ];
    }

    public function getTranslation(Request $request)
    {
        $id = (int) $request->input('id', 0);
        $locale = $request->input('locale');
        $locale = Val::isLocale($locale) ? $locale : '';

        $post = Post::browsable()->with([
            'translations' => function ($query) use ($locale) {
                $query->where('locale', '=', $locale);
            },
        ])->findOrFail($id);

        return $post->translations->first();
    }
}

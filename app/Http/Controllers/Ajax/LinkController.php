<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Custom\Traits\ConstructsPages;
use App\Custom\Services\ValidationService as Val;
use App\Link;

class LinkController extends Controller
{
    use ConstructsPages;

    public function getParents(Request $request)
    {
        $id = (int) $request->input('id');
        $locale = $request->input('locale');
        $group = $request->input('group');
        $locale = Val::isLocale($locale) ? $locale : '';
        $group = Val::isLg($group) ? $group : '';

        $p = Link::ofBase($locale, $group);
        
        if ($id) {
            $link = Link::with('descendants')->find($id);
                if(!$link)
                    return [];
            $disallowed = $link->causingLoopIds();
            $p->whereNotIn('id', $disallowed);
        }

        if ( isset($request->search) && is_string($request->search) ) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $request->search);
            $p->where('title', 'like', '%'.$search.'%' );
        }

        $p = $p->orderBy('rank', 'ASC')->orderBy('title', 'ASC')->simplePaginate(30);

        $parents = $p->items();

        $results = array_map(function ($link) {
            return [
                'id' => $link->id,
                'text' => $link->title,
            ];
        }, $parents);

        return [
            'results' => $results,
            'pagination' => [
                'more' => $p->hasMorePages(),
            ],
        ];
    }
}

<?php

namespace App\Custom\Traits;

use App\MenuItem;

trait ConstructsPages
{
    private $menu_item;
    private $route_name;
    private $shared;    
    private $view;

    public function __construct()
    {
       	$this->middleware('auth');
        $this->middleware('menu.data.refresh');

	    $this->middleware(function ($request, $next) {
			$this->authorize('admin');

            $this->menu_item = MenuItem::current();
            $this->route_name = $this->menu_item->code;
            $this->shared = [
                'menu_item' => $this->menu_item,
                'menu_data' => session('menu_data'),
                'route_name' => $this->route_name,
            ];
            $this->view = 'admin.' . $this->menu_item->code;
            
            auth()->user()->load([
                'role.rmps' => function ($query) {
                    $query->where('menu_item_id', '=', $this->menu_item->id);
                },
                'role.rmps.permission',
            ]);

	        return $next($request);
	    });
    }
}

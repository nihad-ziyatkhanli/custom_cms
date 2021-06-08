<?php

namespace App\Http\Middleware;

use Closure;

class RefreshMenuData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session('menu_data') === null || auth()->user()->expired) {
            session()->put('menu_data', auth()->user()->getMenuData());
            auth()->user()->expired = 0;
            auth()->user()->save();
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Classes\DynamicMenu;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;
use Core\MenuManage\Models\Menu;
use Core\Permissions\Models\Permission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!Sentinel::check()) {
            $request->session()->put('loginRedirect', $request->url());
            return redirect()->route('login');
        } else {
            $user = Sentinel::getUser();
            $action = Route::currentRouteName();
            $permissions = Permission::whereIn('name', [$action, 'admin'])->where('status', 1)->pluck('name');
            if (!$user->hasAnyAccess($permissions)) {
                return response()->view('errors.restricted');
            }

        }

        try {
            $menu = Menu::where('label', '=', 'Root Menu')->first()->getDescendants()->toHierarchy(); // Get all menus
            $currentMenu = Menu::where('link', '=', $request->path())->where('status', '=', 1)->first(); //Get the id of Current Route Url

            if ($currentMenu)
                $aa = DynamicMenu::generateMenu(0, $menu, 0, $currentMenu, Sentinel::getUser()->id); //Generate Menu with current url id

            else
                $aa = DynamicMenu::generateMenu(0, $menu, 0, null, Sentinel::getUser()->id); //Generate Menu without current url id
            view()->share('menu', $aa); //Share the generated menu with all views
            view()->share('user', $user);

        } catch (Exception $e) {
            dd($e);
        }

        return $next($request);
    }
}

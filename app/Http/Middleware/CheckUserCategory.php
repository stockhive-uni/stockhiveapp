<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class CheckUserCategory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Selectes all categories the user has access to. User -> UserRole -> RolePermission -> Category
        $perms = DB::select('SELECT DISTINCT category.name FROM  user_role, permission, role_permission, category WHERE user_role.user_id = ' . Auth::user()->id . ' AND user_role.role_id = role_permission.role_id AND role_permission.permission_id = permission.id AND permission.category_id = category.id');

        // Converts returned categories into an array
        $categories = array_map(function ($perm) {
            return str_replace(' ', '-', strtolower($perm->name));
        }, $perms);

        // Gets the current route name
        $currentRouteName = Route::currentRouteName();

        // Checks if the current route is dashboard or in the list of routes they have access to
        if (!in_array($currentRouteName, $categories)) {
            header("Location: " . route('dashboard')); // Routes back to dashboard if the user doesn't have permissions
            exit;
        }
        return $next($request);
    }
}
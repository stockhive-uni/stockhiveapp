<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $employees = Employee::with('store')
        ->paginate(5)
        ->onEachSide(1);
        return view('Admin.index', ['employees' => $employees]);
    }

    public function selectedUser(Request $request)
    {
        $user = Employee::whereIn('id', $request->id)->with('store')->get();
        return (view('Admin.user', ['user' => $user]));
    }
}

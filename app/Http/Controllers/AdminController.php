<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $user = Employee::where('id', $request->id)->with('store')->get();
        $user = $user[0];
        return (view('Admin.user', ['user' => $user]));
    }

    public function updateSettings(Request $request) {
        $id = $request->id;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $email = $request->email;

        if ($first_name != "" && $last_name != "" && $email != ""){
            Employee::where('id', $id)
            ->update(['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email]);
        }

        $user = Employee::where('id', $request->id)->with('store')->get();
        $user = $user[0];

        return (view('Admin.user', ['user' => $user]));
    }

    public function updatePermissions(Request $request) {
        $roles = $request->roles;

        if ($roles != null) {
            DB::table('user_role')
                ->where('user_id', $request->id)
                ->delete();

            foreach ($roles as $role) {
                DB::table('user_role')->insert([
                    ['user_id' => $request->id, 'role_id' => $role]
                ]);
            }
        }

        $user = Employee::where('id', $request->id)->with('store')->get();
        $user = $user[0];
        return (view('Admin.user', ['user' => $user]));
    }

    public function toggleAccountActivation(Request $request) {
        $user = Employee::where('id', $request->id)->with('store')->get();
        $user = $user[0];

        if ($user['password'] != "") {
            Employee::where('id', $request->id)
                ->update(['password' => '']);
        }
        else {
            if ($request->password != "") {
                Employee::where('id', $request->id)
                    ->update(['password' => Hash::make($request->password)]);
            }
        }

        $user = Employee::where('id', $request->id)->with('store')->get();
        $user = $user[0];
        return (view('Admin.user', ['user' => $user]));
    }
}

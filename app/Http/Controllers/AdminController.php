<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
        if (!$request->has('password')) {
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

    public function createNewUser(Request $request) {
        return (view('Admin.new-user'));
    }

    public function addNewUser(Request $request) {
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $email = $request->email;
        $password = Hash::make($request->password); 
        $roles = $request->roles;

        if ($roles != null && $password != null && $email != null && $last_name != null && $first_name != null && trim($password) != "" && trim($email) != "" && trim($last_name) != "" && trim($first_name) != "") {
            $exists = Employee::where('email', '=', $email)->get();
            if (isset($exists[0])) {
                return (view('Admin.new-user', ['error' => "Account not created, duplicate email"]));
            }
            else {
                $id = Employee::insertGetId([
                    'store_id' => Auth::user()->store_id,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'password' => $password,
                    'remember_token' => ''
                ]);
    
                foreach ($roles as $role) {
                    DB::table('user_role')->insert([
                        ['user_id' => $id, 'role_id' => $role]
                    ]);
                }
                
                $user = Employee::where('id', $id)->with('store')->get();
                $user = $user[0];
                return (view('Admin.user', ['user' => $user]));
            }
        }
        else {
            return (view('Admin.new-user', ['error' => "Account not created, missing field"]));
        }
    }
}

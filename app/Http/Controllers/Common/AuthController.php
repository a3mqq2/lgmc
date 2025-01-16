<?php

namespace App\Http\Controllers\Common;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function login() {
        return view('login');
    }

    public function do_login(Request $request) {
        $request->validate([
            "email" => "required",
            "password" => "required",
            "remember_me" => "nullable",
        ]);
    
        $credentials = $request->only('email', 'password');
    
        if(Auth::attempt($credentials)) {
            $user = auth()->user();
            $access_token = $user->createToken('authToken')->plainTextToken;
            Cookie::queue('ast', $access_token, 777500);
            return redirect('/sections');
        } else {
            return redirect()->back()->withErrors([
                'email' => 'يرجى التأكد من بيانات الحساب'
            ]);
        }
    }
    


    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }



    public function sections() {
        return view('sections');
    }


    public function change_branch(Request $request)
    {
        $user = auth()->user();
        if(in_array($request->branch_id, auth()->user()->branches->pluck('id')->toArray())) {
            $user->branch_id = $request->branch_id;
        } else {
            if($request->branch_id == null && auth()->user()->roles->where('name', 'general_admin')->count() > 0) {
                $user->branch_id = null;
                $user->save();
            } else {
                return redirect()->back()->withErrors(['ليس لديك صلاحية للوصول إلى هذا الفرع']);
            }
        }
        $user->branch_id = $request->branch_id;
        $user->save();
        return redirect()->back();
    }
}

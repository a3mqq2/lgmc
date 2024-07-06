<?php

namespace App\Http\Controllers;

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
}

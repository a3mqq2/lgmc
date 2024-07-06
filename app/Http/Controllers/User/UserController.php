<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function home(Request $request) {
        if($request->branch_id) {
            $user = auth()->user();
            $user->branch_id = $request->branch_id;
            $user->save();
        } else {
            $user = auth()->user();
            $user->branch_id = auth()->user()->branches->first()->id;
            $user->save();
        }


        if(!$user->branch_id) {
            abort(401);
        }

        return view('user.home');
    }
}

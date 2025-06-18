<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function home(Request $request) {
        if($request->branch_id) {
            if(in_array($request->branch_id, auth()->user()->branches->pluck('id')->toArray())) {
                $user = auth()->user();
                $user->branch_id = $request->branch_id;
                $user->save();
            } else {
                $user = auth()->user();
                $user->branch_id = auth()->user()->branches->first()->id;
                $user->save();
            }
        } else {
            $user = auth()->user();
        }


        if(!$user->branch_id) {
            abort(401);
        }

        return view('user.home');
    }


    public function search(Request $request)
    {
        if(request('phone'))
        {
            $doctor = Doctor::where('phone', 'like', '%' . request('phone') . '%')
            ->orWhere('phone_2', 'like', '%' . request('phone') . "%")
            ->when(auth()->user()->branch_id, function ($query) {
                $query->where('branch_id', auth()->user()->branch_id);
            })->first();
        }



        if(request('name'))
        {
            $doctor = Doctor::where('name', 'like', '%' . request('name') . '%')
            ->when(auth()->user()->branch_id, function ($query) {
                $query->where('branch_id', auth()->user()->branch_id);
            })->first();
        }

        
        if(request('code'))
        {
            $code = explode('-', $request->code);
            if(count($code) > 1)
            {
                $doctor = Doctor::where('code', request('code'))
                ->when(auth()->user()->branch_id, function ($query) {
                   $query->where('branch_id', auth()->user()->branch_id);
                })->first();
            } else {
                $doctor = Doctor::where('index', request('code'))->when(auth()->user()->branch_id, function($q) {
                    $q->where('branch_id', auth()->user()->branch_id);
                })->first();
            }
        }


        if($doctor)
        {
            return redirect()->route(get_area_name().'.doctors.show', $doctor);
        } else {
            return back()->withErrors(['لم يتم العثور على هذا الطبيب']);
        }

    }
}

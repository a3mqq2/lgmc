<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function home() {
        $all_foreign_doctors = Doctor::where('type', 'foreign')->count();
        return view('admin.home', compact('all_foreign_doctors'));
    }

}

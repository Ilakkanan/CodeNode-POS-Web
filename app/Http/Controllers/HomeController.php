<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
        if(Auth::id()){
            $user=Auth::user();
            $usertype=Auth()->user()->usertype;
            if($usertype=='cashier'){
                return view('Cashier.dashboard',compact('user'));
            }elseif($usertype=='admin'){
                return view('Admin.dashboard',compact('user'));
            }elseif($usertype=='superadmin'){
                return view('SuperAdmin.dashboard',compact('user'));
            }else{
                return redirect()->back();
            }
        }
    }
}

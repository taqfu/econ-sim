<?php

namespace App\Http\Controllers;

use Auth;
use App\Avatar;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home', ["avatars"=>Avatar::where('user_id', Auth::user()->id)->get()]);
    }
}

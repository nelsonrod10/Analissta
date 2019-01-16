<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\RolesHabilidades;

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
        event(new RolesHabilidades());
        
        return redirect()->route('role-verify.index');
        
        //return view('/inicio');
    }
}

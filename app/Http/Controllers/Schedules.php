<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Schedules extends Controller
{
    public function index(Request $request){
        return view("doctors/schedules/schedules");
    }
}
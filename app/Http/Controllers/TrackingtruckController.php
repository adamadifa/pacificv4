<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackingtruckController extends Controller
{
    public function index()
    {
        return view('trackingtruck.index');
    }
}

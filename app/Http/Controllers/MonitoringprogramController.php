<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonitoringprogramController extends Controller
{
    public function index()
    {
        return view('worksheetom.monitoringprogram.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Programikatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class ProgrammarketingController extends Controller
{
    public function index(Request $request)
    {
        return view('worksheetom.programmarketing.index');
    }

    public function create()
    {
       
    }

    public function store(Request $request)
    {
       
    }

    public function edit($kode_program)
    {
       
    }

    public function update(Request $request, $kode_program)
    {
       
    }

    public function destroy($kode_program)
    {
       
    }
}

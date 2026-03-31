<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class EmployeeAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'password' => 'required',
        ]);

        $employee = Karyawan::where('nik', $request->nik)->first();

        if (!$employee || !Hash::check($request->password, $employee->password)) {
            return response()->json([
                'success' => false,
                'message' => 'NIK atau Password salah.',
            ], 401);
        }

        $token = $employee->createToken('employee-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Berhasil',
            'token' => $token,
            'employee' => $employee
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout Berhasil',
        ]);
    }

    public function me(Request $request)
    {
        $employee = Karyawan::getKaryawan($request->user()->nik);
        return response()->json([
            'success' => true,
            'data' => $employee
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Regional;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        $query->with('roles');
        $query->join('cabang', 'users.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('regional', 'users.kode_regional', '=', 'regional.kode_regional');
        if (!empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if (!empty($request->kode_cabang)) {
            $query->where('users.kode_cabang', $request->kode_cabang);
        }
        $users = $query->paginate(10);
        $users->appends(request()->all());
        $cabang = Cabang::orderBy('nama_cabang')->get();
        return view('settings.users.index', compact('users', 'cabang'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $cabang = Cabang::orderBy('nama_cabang')->get();
        $regional = Regional::orderBy('kode_regional')->get();
        return view('settings.users.create', compact('roles', 'cabang', 'regional'));
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::with('roles')->where('id', $id)->first();
        $roles = Role::orderBy('name')->get();
        $cabang = Cabang::orderBy('nama_cabang')->get();
        $regional = Regional::orderBy('kode_regional')->get();
        return view('settings.users.edit', compact('user', 'roles', 'cabang', 'regional'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required',
            'kode_cabang' => 'required',
            'kode_regional' => 'required'
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
                'kode_cabang' => $request->kode_cabang,
                'kode_regional' => $request->kode_regional
            ]);

            $user->assignRole($request->role);
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }


    public function update($id, Request $request)
    {
        $id = Crypt::decrypt($id);
        $user = User::findorFail($id);


        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'kode_cabang' => 'required',
            'kode_regional' => 'required'
        ]);

        try {

            if (isset($request->password)) {
                User::where('id', $id)->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'kode_cabang' => $request->kode_cabang,
                    'kode_regional' => $request->kode_regional,
                    'password' => bcrypt($request->password)
                ]);
            } else {
                User::where('id', $id)->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'kode_cabang' => $request->kode_cabang,
                    'kode_regional' => $request->kode_regional,
                ]);
            }

            if (isset($request->role)) {
                $user->syncRoles([]);
                $user->assignRole($request->role);
            }

            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }


    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        try {
            User::where('id', $id)->delete();
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }
}

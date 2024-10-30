<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Regional;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;
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
        $departemen = Departemen::orderBy('kode_dept')->get();
        $deptchunks = $departemen->chunk(2);
        return view('settings.users.create', compact('roles', 'cabang', 'regional', 'departemen', 'deptchunks'));
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::with('roles')->where('id', $id)->first();
        //get Roles name from user
        // dd();
        $roles = Role::orderBy('name')->get();
        $cabang = Cabang::orderBy('nama_cabang')->get();
        $regional = Regional::orderBy('kode_regional')->get();
        $departemen = Departemen::orderBy('kode_dept')->get();
        $deptchunks = $departemen->chunk(2);
        $dept_access = json_decode($user->dept_access, true) != null ? json_decode($user->dept_access, true) : [];

        return view('settings.users.edit', compact('user', 'roles', 'cabang', 'regional', 'departemen', 'deptchunks', 'dept_access'));
    }

    public function ubahpassword()
    {
        $id = auth()->user()->id;
        $user = User::with('roles')->where('id', $id)->first();
        //get Roles name from user
        // dd();
        $roles = Role::orderBy('name')->get();
        $cabang = Cabang::orderBy('nama_cabang')->get();
        $regional = Regional::orderBy('kode_regional')->get();
        $departemen = Departemen::orderBy('kode_dept')->get();
        $deptchunks = $departemen->chunk(2);
        $dept_access = json_decode($user->dept_access, true) != null ? json_decode($user->dept_access, true) : [];

        return view('settings.users.ubahpassword', compact('user', 'roles', 'cabang', 'regional', 'departemen', 'deptchunks', 'dept_access'));
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
            'kode_regional' => 'required',
            'kode_dept' => 'required'
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
                'kode_cabang' => $request->kode_cabang,
                'kode_dept' => $request->kode_dept,
                'kode_regional' => $request->kode_regional,
                'dept_access' => json_encode($request->dept_access)
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
            'kode_regional' => 'required',
            'kode_dept' => 'required',
            'status' => 'required'
        ]);

        $force_logout = $request->status == 0 ? 1 : 0;
        try {

            if (isset($request->password)) {
                User::where('id', $id)->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'kode_cabang' => $request->kode_cabang,
                    'kode_dept' => $request->kode_dept,
                    'kode_regional' => $request->kode_regional,
                    'password' => bcrypt($request->password),
                    'dept_access' => json_encode($request->dept_access),
                    'status' => $request->status,
                    'force_logout' => $force_logout
                ]);
            } else {
                User::where('id', $id)->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'kode_cabang' => $request->kode_cabang,
                    'kode_dept' => $request->kode_dept,
                    'kode_regional' => $request->kode_regional,
                    'dept_access' => json_encode($request->dept_access),
                    'status' => $request->status,
                    'force_logout' => $force_logout

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

    public function updateprofile(Request $request)
    {
        $id = auth()->user()->id;


        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
        ]);

        try {

            if (isset($request->password)) {
                User::where('id', $id)->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                ]);
            } else {
                User::where('id', $id)->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                ]);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }


    public function createuserpermission($id)
    {
        $id = Crypt::decrypt($id);
        $permissions = Permission::selectRaw('id_permission_group,permission_groups.name as group_name,GROUP_CONCAT(permissions.id,"-",permissions.name) as permissions')
            ->join('permission_groups', 'permissions.id_permission_group', '=', 'permission_groups.id')
            ->orderBy('id_permission_group')
            ->groupBy('id_permission_group')
            ->groupBy('permission_groups.name')
            ->get();

        $user = User::find($id);
        //Cek Role ID dari User

        $userpermissions = $user->permissions->pluck('name')->toArray();
        $role = Role::findByName($user->getRoleNames()[0]);
        $rolepermissions = $role->permissions->pluck('name')->toArray();
        // dd($rolepermissions);
        return view('settings.users.create_user_permissions', compact('permissions', 'user', 'userpermissions', 'rolepermissions'));
    }

    public function storeuserpermission($id, Request $request)
    {
        $id = Crypt::decrypt($id);
        $permissions = $request->permission;
        $user = User::find($id);
        $old_permissions = $user->permissions->pluck('name')->toArray();


        if (empty($permissions)) {
            return Redirect::back()->with(['warning' => 'Data Permission Harus Di Pilih']);
        }

        try {
            $user->revokePermissionTo($old_permissions);
            $user->givePermissionTo($permissions);
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



    public function assignRoleuser()
    {
        // Daftar ID pengguna yang akan diberikan role
        $userIds = [
            104,
            107,
            112,
            119,
            120,
            121,
            122,
            123,
            124,
            125,
            126,
            127,
            128,
            129,
            130,
            131,
            132,
            133,
            134,
            135,
            136,
            138,
            139,
            140,
            141,
            142,
            143,
            144,
            145,
            146,
            147,
            148,
            149,
            150,
            151,
            152,
            153,
            154,
            155,
            156,
            157,
            158,
            159,
            160,
            161,
            162,
            163,
            165,
            169,
            170,
            171,
            175,
            179,
            180,
            181,
            182,
            183,
            187,
            204,
            205,
            206,
            207,
            208,
            209,
            214,
            215,
            216,
            227,
            228,
            236,
            237,
            238,
            242,
            243
        ];

        // Ambil role yang ingin diberikan
        $role = Role::findByName('salesman');

        // Cari pengguna berdasarkan ID dan berikan role
        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            $user->assignRole($role);
        }
    }
}

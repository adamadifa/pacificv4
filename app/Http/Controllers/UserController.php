<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Group;
use App\Models\Jabatan;
use App\Models\Regional;
use App\Models\User;
use App\Models\Karyawan;
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

        if (!empty($request->kode_dept)) {
            $query->where('users.kode_dept', $request->kode_dept);
        }

        if (!empty($request->role_id)) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role_id);
            });
        }

        $users = $query->paginate(10);
        $users->appends(request()->all());
        $cabang = Cabang::orderBy('nama_cabang')->get();
        $departemen = Departemen::orderBy('nama_dept')->get();
        $roles = Role::orderBy('name')->get();
        return view('settings.users.index', compact('users', 'cabang', 'departemen', 'roles'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $cabang = Cabang::orderBy('nama_cabang')->get();
        $regional = Regional::orderBy('kode_regional')->get();
        $departemen = Departemen::orderBy('kode_dept')->get();
        $jabatan = Jabatan::orderBy('nama_jabatan')->get();
        $karyawan = Karyawan::orderBy('nama_karyawan')->get();
        $group = Group::orderBy('nama_group')->get();
        return view('settings.users.create', compact('roles', 'cabang', 'regional', 'departemen', 'jabatan', 'karyawan', 'group'));
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
        $dept_access = json_decode($user->dept_access, true) != null ? json_decode($user->dept_access, true) : [];
        $cabang_access = json_decode($user->cabang_access, true) != null ? json_decode($user->cabang_access, true) : [];
        $jabatan = Jabatan::orderBy('nama_jabatan')->get();
        $jabatan_access = json_decode($user->jabatan_access, true) != null ? json_decode($user->jabatan_access, true) : [];
        $karyawan = Karyawan::orderBy('nama_karyawan')->get();
        $karyawan_access = json_decode($user->karyawan_access, true) != null ? json_decode($user->karyawan_access, true) : [];
        $group = Group::orderBy('nama_group')->get();
        $group_access = json_decode($user->group_access, true) != null ? json_decode($user->group_access, true) : [];
        $is_pic_presensi = $user->is_pic_presensi;
        $is_approval_presensi = $user->is_approval_presensi;

        return view('settings.users.edit', compact('user', 'roles', 'cabang', 'regional', 'departemen', 'dept_access', 'cabang_access', 'jabatan', 'jabatan_access', 'karyawan', 'karyawan_access', 'group', 'group_access', 'is_pic_presensi', 'is_approval_presensi'));
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
                'cabang_access' => json_encode($request->cabang_access),
                'dept_access' => json_encode($request->dept_access),
                'jabatan_access' => json_encode($request->jabatan_access),
                'karyawan_access' => json_encode($request->karyawan_access),
                'group_access' => json_encode($request->group_access),
                'is_pic_presensi' => $request->has('is_pic_presensi') ? 1 : 0,
                'is_approval_presensi' => $request->has('is_approval_presensi') ? 1 : 0
            ]);

            $user->assignRole($request->role);

            if ($request->has('is_pic_presensi')) {
                $pic_permissions = [
                    'izinabsen.index', 'izinabsen.create', 'izinabsen.edit', 'izinabsen.store', 'izinabsen.update', 'izinabsen.show', 'izinabsen.delete',
                    'izincuti.index', 'izincuti.create', 'izincuti.edit', 'izincuti.store', 'izincuti.update', 'izincuti.show', 'izincuti.delete',
                    'izinkeluar.index', 'izinkeluar.create', 'izinkeluar.edit', 'izinkeluar.store', 'izinkeluar.update', 'izinkeluar.show', 'izinkeluar.delete',
                    'izinterlambat.index', 'izinterlambat.create', 'izinterlambat.edit', 'izinterlambat.store', 'izinterlambat.update', 'izinterlambat.show', 'izinterlambat.delete',
                    'izinpulang.index', 'izinpulang.create', 'izinpulang.edit', 'izinpulang.store', 'izinpulang.update', 'izinpulang.show', 'izinpulang.delete',
                    'izindinas.index', 'izindinas.create', 'izindinas.edit', 'izindinas.store', 'izindinas.update', 'izindinas.show', 'izindinas.delete',
                    'izinkoreksi.index', 'izinkoreksi.create', 'izinkoreksi.edit', 'izinkoreksi.store', 'izinkoreksi.update', 'izinkoreksi.show', 'izinkoreksi.delete',
                    'izinsakit.index', 'izinsakit.create', 'izinsakit.edit', 'izinsakit.store', 'izinsakit.update', 'izinsakit.show', 'izinsakit.delete',
                    'presensi.index', 'presensi.koreksi'
                ];
                $user->givePermissionTo($pic_permissions);
            }

            if ($request->has('is_approval_presensi')) {
                $approval_permissions = [
                    'izinabsen.approve', 'izincuti.approve', 'izinkeluar.approve', 'izinterlambat.approve', 'izinpulang.approve', 'izindinas.approve', 'izinkoreksi.approve', 'izinsakit.approve', 'lembur.approve', 'penilaiankaryawan.approve'
                ];
                $user->givePermissionTo($approval_permissions);
            }

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
                    'cabang_access' => json_encode($request->cabang_access),
                    'dept_access' => json_encode($request->dept_access),
                    'jabatan_access' => json_encode($request->jabatan_access),
                    'karyawan_access' => json_encode($request->karyawan_access),
                    'group_access' => json_encode($request->group_access),
                    'is_pic_presensi' => $request->has('is_pic_presensi') ? 1 : 0,
                    'is_approval_presensi' => $request->has('is_approval_presensi') ? 1 : 0,
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
                    'cabang_access' => json_encode($request->cabang_access),
                    'dept_access' => json_encode($request->dept_access),
                    'jabatan_access' => json_encode($request->jabatan_access),
                    'karyawan_access' => json_encode($request->karyawan_access),
                    'group_access' => json_encode($request->group_access),
                    'is_pic_presensi' => $request->has('is_pic_presensi') ? 1 : 0,
                    'is_approval_presensi' => $request->has('is_approval_presensi') ? 1 : 0,
                    'status' => $request->status,
                    'force_logout' => $force_logout
                ]);
            }

            if (isset($request->role)) {
                $user->syncRoles([]);
                $user->assignRole($request->role);
            }

            $pic_permissions = [
                'izinabsen.index', 'izinabsen.create', 'izinabsen.edit', 'izinabsen.store', 'izinabsen.update', 'izinabsen.show', 'izinabsen.delete',
                'izincuti.index', 'izincuti.create', 'izincuti.edit', 'izincuti.store', 'izincuti.update', 'izincuti.show', 'izincuti.delete',
                'izinkeluar.index', 'izinkeluar.create', 'izinkeluar.edit', 'izinkeluar.store', 'izinkeluar.update', 'izinkeluar.show', 'izinkeluar.delete',
                'izinterlambat.index', 'izinterlambat.create', 'izinterlambat.edit', 'izinterlambat.store', 'izinterlambat.update', 'izinterlambat.show', 'izinterlambat.delete',
                'izinpulang.index', 'izinpulang.create', 'izinpulang.edit', 'izinpulang.store', 'izinpulang.update', 'izinpulang.show', 'izinpulang.delete',
                'izindinas.index', 'izindinas.create', 'izindinas.edit', 'izindinas.store', 'izindinas.update', 'izindinas.show', 'izindinas.delete',
                'izinkoreksi.index', 'izinkoreksi.create', 'izinkoreksi.edit', 'izinkoreksi.store', 'izinkoreksi.update', 'izinkoreksi.show', 'izinkoreksi.delete',
                'izinsakit.index', 'izinsakit.create', 'izinsakit.edit', 'izinsakit.store', 'izinsakit.update', 'izinsakit.show', 'izinsakit.delete',
                'presensi.index', 'presensi.koreksi'
            ];

            $approval_permissions = [
                'izinabsen.approve', 'izincuti.approve', 'izinkeluar.approve', 'izinterlambat.approve', 'izinpulang.approve', 'izindinas.approve', 'izinkoreksi.approve', 'izinsakit.approve', 'lembur.approve', 'penilaiankaryawan.approve'
            ];

            if ($request->has('is_pic_presensi')) {
                $user->givePermissionTo($pic_permissions);
            } else {
                $user->revokePermissionTo($pic_permissions);
            }

            if ($request->has('is_approval_presensi')) {
                $user->givePermissionTo($approval_permissions);
            } else {
                $user->revokePermissionTo($approval_permissions);
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

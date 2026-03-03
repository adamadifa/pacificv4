# Backup Queries

## 1. OR Logic Version (Feb 2026 - Before AND Refinement)

This version used `OR` logic for `jabatan_access` and `regional_access`, effectively expanding the results.

### Penilaiankaryawan.php
```php
    function getPenilaiankaryawan($kode_penilaian = null, Request $request = null)
    {
        $user = User::findOrFail(auth()->user()->id);
        $query = Penilaiankaryawan::query();

        // 1. SELECT & JOINS
        $query->select(
            'hrd_penilaian.*',
            'hrd_karyawan.nama_karyawan',
            'hrd_karyawan.foto',
            'hrd_karyawan.jenis_kelamin',
            'hrd_jabatan.nama_jabatan',
            'hrd_jabatan.alias as alias_jabatan',
            'hrd_jabatan.kategori as kategori_jabatan',
            'hrd_departemen.nama_dept',
            'cabang.nama_cabang',
            'cabang.kode_regional',
            'hrd_kontrak_penilaian.no_kontrak as no_kontrak_baru',
            'hrd_kesepakatanbersama.no_kb'
        );

        $query->join('hrd_karyawan', 'hrd_penilaian.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_penilaian.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('cabang', 'hrd_penilaian.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('hrd_departemen', 'hrd_penilaian.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->leftJoin('hrd_kontrak_penilaian', 'hrd_penilaian.kode_penilaian', '=', 'hrd_kontrak_penilaian.kode_penilaian');
        $query->leftJoin('hrd_kesepakatanbersama', 'hrd_penilaian.kode_penilaian', '=', 'hrd_kesepakatanbersama.kode_penilaian');

        // 2. DATA ACCESS RESTRICTIONS
        if (!$user->hasRole(['super admin', 'asst. manager hrd', 'spv presensi'])) {

            $query->where(function ($access) use ($user) {
                $dept_access = json_decode($user->dept_access, true) ?? [];
                $jabatan_access = json_decode($user->jabatan_access, true) ?? [];

                // a. Default Organizational Access (Mandatory)
                $access->where(function ($q) use ($user, $dept_access) {
                    if ($user->kode_cabang == 'PST') {
                        $q->where('hrd_penilaian.kode_cabang', 'PST')
                            ->whereIn('hrd_penilaian.kode_dept', array_merge([$user->kode_dept], $dept_access));
                    } else {
                        $q->where('hrd_penilaian.kode_cabang', $user->kode_cabang);
                    }
                });

                // b. Explicit Jabatan Access (OR)
                if (!empty($jabatan_access)) {
                    $access->orWhereIn('hrd_penilaian.kode_jabatan', $jabatan_access);
                }

                // c. Regional Access (OR)
                if (!empty($user->kode_regional) && $user->kode_regional != 'R00') {
                    $access->orWhere('cabang.kode_regional', $user->kode_regional);
                }
            });
        }

        // ... REQUEST FILTERS & ORDER BY ...
        return $query;
    }
```

### Karyawan.php
```php
    function getKaryawanpenilaian()
    {
        $user = User::findOrFail(auth()->user()->id);
        $query = Karyawan::query();

        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');

        if (!$user->hasRole(['super admin', 'asst. manager hrd', 'spv presensi'])) {

            $query->where(function ($access) use ($user) {
                $dept_access = json_decode($user->dept_access, true) ?? [];
                $jabatan_access = json_decode($user->jabatan_access, true) ?? [];

                // 1. Branch/Dept Rule (Mandatory)
                $access->where(function ($q) use ($user, $dept_access) {
                    if ($user->kode_cabang == 'PST') {
                        $q->where('hrd_karyawan.kode_cabang', 'PST')
                            ->whereIn('hrd_karyawan.kode_dept', array_merge([$user->kode_dept], $dept_access));
                    } else {
                        $q->where('hrd_karyawan.kode_cabang', $user->kode_cabang);
                    }
                });

                // 2. Job Position Access (OR)
                if (!empty($jabatan_access)) {
                    $access->orWhereIn('hrd_karyawan.kode_jabatan', $jabatan_access);
                }

                // 3. Regional (OR)
                if (!empty($user->kode_regional) && $user->kode_regional != 'R00') {
                    $access->orWhere('cabang.kode_regional', $user->kode_regional);
                }
            });
        }
        // ... ORDER BY ...
        return $query;
    }
```

---

## 2. Complex Disposisi Version (Early March 2026 - Before Total Rewrite)

This version included complex role-based logic and joins to the `disposisi` table.

### Penilaiankaryawan.php (Excerpt)
```php
    public function getPenilaiankaryawan($kode_penilaian = null, Request $request = null)
    {
        $user = User::findOrFail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $query = Penilaiankaryawan::query();

        // Select with complex joins
        $query->select(
            'hrd_penilaian.*',
            'hrd_karyawan.nama_karyawan',
            'hrd_karyawan.foto',
            'hrd_karyawan.jenis_kelamin',
            'hrd_jabatan.nama_jabatan',
            'hrd_jabatan.alias as alias_jabatan',
            'hrd_departemen.nama_dept',
            'cabang.nama_cabang',
            'cabang.kode_regional',
            'roles.name as posisi_ajuan'
        );

        // ... Joins to hrd_karyawan, hrd_jabatan, cabang, hrd_departemen ...
        // Complex Join for Posisi Ajuan:
        $query->leftJoin(DB::raw("(SELECT kode_penilaian, id_role_ajuan FROM hrd_penilaian_disposisi WHERE id IN (SELECT MAX(id) FROM hrd_penilaian_disposisi GROUP BY kode_penilaian)) as latest_disposisi"), 'hrd_penilaian.kode_penilaian', '=', 'latest_disposisi.kode_penilaian');
        $query->leftJoin('roles', 'latest_disposisi.id_role_ajuan', '=', 'roles.id');

        // Access Logic:
        if (!in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi'])) {
            $query->where(function ($access) use ($user, $role) {
                // Mix of Organizational and Workflow checks
                $access->where(function($q) use ($user) {
                    $q->where('hrd_penilaian.kode_dept', $user->kode_dept)
                      ->where('hrd_penilaian.kode_cabang', $user->kode_cabang);
                })
                ->orWhereExists(function ($q) use ($user) {
                    $q->select(DB::raw(1))
                      ->from('hrd_penilaian_disposisi')
                      ->whereColumn('hrd_penilaian_disposisi.kode_penilaian', 'hrd_penilaian.kode_penilaian')
                      ->where('hrd_penilaian_disposisi.id_penerima', $user->id);
                });
                
                // ... Role Specific Exceptions ...
            });
        }
        // ... Filters ...
        return $query;
    }
```

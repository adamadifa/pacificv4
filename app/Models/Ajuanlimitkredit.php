<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Ajuanlimitkredit extends Model
{
    use HasFactory;
    protected $table = "marketing_ajuan_limitkredit";
    protected $primaryKey = "no_pengajuan";
    protected $guarded = [];
    public $incrementing = false;


    function getAjuanlimitkredit($no_pengajuan = null, Request $request = null)
    {
        $user = User::findOrFail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $all_configs = AjuanlimitkreditConfig::all();
        $query = Ajuanlimitkredit::query();

        $query->select(
            'marketing_ajuan_limitkredit.*',
            'pelanggan.nama_pelanggan',
            'pelanggan.nik',
            'pelanggan.alamat_pelanggan',
            'pelanggan.alamat_toko',
            'pelanggan.no_hp_pelanggan',
            'salesman.nama_salesman',
            'cabang.nama_cabang',
            'cabang.nama_pt',
            'cabang.alamat_cabang',
            'pelanggan.hari',
            'pelanggan.latitude',
            'pelanggan.longitude',
            'pelanggan.foto',
            'pelanggan.foto_owner',

        );
        $query->join('pelanggan', 'marketing_ajuan_limitkredit.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('salesman', 'marketing_ajuan_limitkredit.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');

        // Access Control Logic matching Lembur::getLembur
        if (!$user->hasRole($roles_access_all_cabang)) {
            $query->where(function ($access) use ($user) {
                $cabang_access = json_decode($user->cabang_access, true) ?? [];

                // Branch Access
                if (!in_array('all', $cabang_access)) {
                    if (!empty($cabang_access)) {
                        $access->whereIn('salesman.kode_cabang', $cabang_access);
                    } else {
                        // Default logic if cabang_access is empty
                        if (empty($user->kode_regional) || $user->kode_regional == 'R00') {
                            $access->where('salesman.kode_cabang', $user->kode_cabang);
                        }
                    }
                }

                // Regional Access
                if (!empty($user->kode_regional) && $user->kode_regional != 'R00') {
                    $access->where('cabang.kode_regional', $user->kode_regional);
                }
            });
        }

        // Filter by Role-Specific Limit Ranges (Super Admin sees all)
        if (!$user->hasRole('super admin')) {
            $user_roles = $user->getRoleNames()->toArray();
            $my_configs = $all_configs->filter(function ($config) use ($user_roles) {
                return !empty(array_intersect($user_roles, $config->roles));
            });

            if ($my_configs->isNotEmpty()) {
                $query->where(function ($q) use ($my_configs) {
                    foreach ($my_configs as $config) {
                        $q->orWhereBetween('marketing_ajuan_limitkredit.jumlah', [$config->min_limit, $config->max_limit]);
                    }
                });
            }
        }

        if ($no_pengajuan) {
            $query->where('marketing_ajuan_limitkredit.no_pengajuan', $no_pengajuan);
        }

        if ($request) {
            if (!empty($request->kode_cabang_search)) {
                $query->where('salesman.kode_cabang', $request->kode_cabang_search);
            }

            $role_filter = $request->posisi_ajuan;
            $user_is_super_admin = $user->hasRole('super admin');

            if ($request->status === '1' && (!empty($role_filter) || !$user_is_super_admin)) {
                $roles_to_check = !empty($role_filter) ? [$role_filter] : $user->getRoleNames()->toArray();
                $query->where(function ($q) use ($roles_to_check, $all_configs) {
                    foreach ($all_configs as $config) {
                        $config_roles = $config->roles;
                        $matches = array_intersect($roles_to_check, $config_roles);
                        if (!empty($matches)) {
                            foreach ($matches as $m) {
                                $index = array_search($m, $config_roles);
                                $following_roles = array_slice($config_roles, $index + 1);
                                $q->orWhere(function ($sub) use ($config, $following_roles) {
                                    $sub->whereBetween('marketing_ajuan_limitkredit.jumlah', [$config->min_limit, $config->max_limit]);
                                    $sub->where(function ($cond) use ($following_roles) {
                                        $cond->where('marketing_ajuan_limitkredit.status', '1');
                                        if (!empty($following_roles)) {
                                            $cond->orWhere(function ($status0) use ($following_roles) {
                                                $status0->where('marketing_ajuan_limitkredit.status', '0')
                                                    ->whereIn('marketing_ajuan_limitkredit.posisi_ajuan', $following_roles);
                                            });
                                        }
                                    });
                                });
                            }
                        }
                    }
                });
            } else {
                if (!empty($request->posisi_ajuan)) {
                    $query->where('marketing_ajuan_limitkredit.posisi_ajuan', $request->posisi_ajuan);
                }

                if ($request->status === '0') {
                    $query->where('marketing_ajuan_limitkredit.status', $request->status);
                } else if (!empty($request->status)) {
                    $query->where('marketing_ajuan_limitkredit.status', $request->status);
                }
            }

            if (!empty($request->dari) && !empty($request->sampai)) {
                $query->whereBetween('marketing_ajuan_limitkredit.tanggal', [$request->dari, $request->sampai]);
            }
        }

        $query->orderBy('marketing_ajuan_limitkredit.status', 'asc');
        $query->orderBy('marketing_ajuan_limitkredit.tanggal', 'desc');

        return $query;
    }
}

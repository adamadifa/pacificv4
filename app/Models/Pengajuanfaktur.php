<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Pengajuanfaktur extends Model
{
    use HasFactory;
    protected $table = "marketing_ajuan_faktur";
    protected $primaryKey = "no_pengajuan";
    protected $guarded = [];
    public $incrementing = false;
    public function getPengajuanfaktur($no_pengajuan = null, Request $request = null)
    {
        $user = User::findOrFail(auth()->user()->id);
        $user_role = $user->roles->pluck('name')[0];
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $query = Pengajuanfaktur::query();
        $query->select(
            'marketing_ajuan_faktur.*',
            'pelanggan.nama_pelanggan',
            'salesman.nama_salesman',
            'cabang.nama_cabang',
            'pelanggan.limit_pelanggan'
        );
        $query->join('pelanggan', 'marketing_ajuan_faktur.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('salesman', 'marketing_ajuan_faktur.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');

        // Access Control Logic
        if (!$user->hasRole($roles_access_all_cabang)) {
            $query->where(function ($access) use ($user) {
                $cabang_access = json_decode($user->cabang_access, true) ?? [];

                // Branch Access
                if (!in_array('all', $cabang_access)) {
                    if (!empty($cabang_access)) {
                        $access->whereIn('salesman.kode_cabang', $cabang_access);
                    } else {
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

        if ($no_pengajuan) {
            $query->where('marketing_ajuan_faktur.no_pengajuan', $no_pengajuan);
        }

        if ($request) {
            if (!empty($request->kode_cabang_search)) {
                $query->where('salesman.kode_cabang', $request->kode_cabang_search);
            }

            if (!empty($request->posisi_ajuan)) {
                $query->where('marketing_ajuan_faktur.posisi_ajuan', $request->posisi_ajuan);
            }

            if (isset($request->status)) {
                if ($request->status === '1') {
                    $query->where(function ($q) use ($user_role, $roles_access_all_cabang) {
                        $q->where('marketing_ajuan_faktur.status', '1');
                        if (!in_array($user_role, $roles_access_all_cabang)) {
                            $q->orWhere(function ($sq) use ($user_role) {
                                $sq->where('marketing_ajuan_faktur.status', '0');
                                $sq->where('marketing_ajuan_faktur.posisi_ajuan', '!=', $user_role);
                                $sq->whereNotNull('marketing_ajuan_faktur.posisi_ajuan');
                            });
                        }
                    });
                } else if ($request->status === '0') {
                    $query->where('marketing_ajuan_faktur.status', '0');
                    if (!in_array($user_role, $roles_access_all_cabang) && empty($request->posisi_ajuan)) {
                        $query->where('marketing_ajuan_faktur.posisi_ajuan', $user_role);
                    }
                } else if ($request->status === '2') {
                    $query->where('marketing_ajuan_faktur.status', '2');
                }
            } else {
                if (!empty($request->posisi_ajuan)) {
                    // posisi_ajuan already set above, no further status filter needed if not explicitly requested
                } else {
                    if (!in_array($user_role, $roles_access_all_cabang)) {
                        $query->where(function ($q) use ($user_role) {
                            $q->where('marketing_ajuan_faktur.posisi_ajuan', $user_role)
                                ->orWhere('marketing_ajuan_faktur.status', '1')
                                ->orWhere('marketing_ajuan_faktur.status', '2')
                                ->orWhere(function ($sq) use ($user_role) {
                                    $sq->where('marketing_ajuan_faktur.status', '0')
                                        ->where('marketing_ajuan_faktur.posisi_ajuan', '!=', $user_role)
                                        ->whereNotNull('marketing_ajuan_faktur.posisi_ajuan');
                                });
                        });
                    }
                    if (in_array($user_role, $roles_access_all_cabang) && empty($request->posisi_ajuan)) {
                        $query->where('marketing_ajuan_faktur.status', 1);
                    }
                }
            }

            if (!empty($request->dari) && !empty($request->sampai)) {
                $query->whereBetween('marketing_ajuan_faktur.tanggal', [$request->dari, $request->sampai]);
            }

            if (!empty($request->nama_pelanggan)) {
                $query->where('pelanggan.nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
            }
        }

        $query->orderBy('marketing_ajuan_faktur.status', 'asc');
        $query->orderBy('marketing_ajuan_faktur.tanggal', 'desc');

        return $query;
    }
}

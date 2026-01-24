<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\PencairanProgramIkatan2026;
use App\Models\Programikatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\MktIkatan2026;
use App\Models\MktIkatanDetail2026;
use App\Models\MktIkatanTarget2026;
use App\Models\DetailPencairanProgramIkatan2026;
use App\Models\Detailpenjualan;
use Illuminate\Support\Facades\Crypt;

class PencairanProgramIkatan2026Controller extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $query = PencairanProgramIkatan2026::query();
        $query->select(
            'marketing_pencairan_ikatan_2026.*',
            'cabang.nama_cabang',
            'nama_program',
        );
        $query->join('program_ikatan', 'marketing_pencairan_ikatan_2026.kode_program', '=', 'program_ikatan.kode_program');
        $query->join('cabang', 'marketing_pencairan_ikatan_2026.kode_cabang', '=', 'cabang.kode_cabang');

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('marketing_pencairan_ikatan_2026.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang)) {
            $query->where('marketing_pencairan_ikatan_2026.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_program)) {
            $query->where('marketing_pencairan_ikatan_2026.kode_program', $request->kode_program);
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_pencairan_ikatan_2026.tanggal', [$request->dari, $request->sampai]);
        }

        if ($user->hasRole('regional sales manager')) {
            $query->whereNotNull('marketing_pencairan_ikatan_2026.om');
            $query->where('marketing_pencairan_ikatan_2026.status', '!=', 2);
        }

        if ($user->hasRole('gm marketing')) {
            $query->whereNotNull('marketing_pencairan_ikatan_2026.rsm');
            $query->where('marketing_pencairan_ikatan_2026.status', '!=', 2);
        }

        if ($user->hasRole('direktur')) {
            $query->whereNotNull('marketing_pencairan_ikatan_2026.gm');
            $query->where('marketing_pencairan_ikatan_2026.status', '!=', 2);
        }

        $query->orderBy('marketing_pencairan_ikatan_2026.kode_pencairan', 'desc');
        
        $pencairanprogramikatan = $query->paginate(15);
        $pencairanprogramikatan->appends(request()->all());
        $data['pencairanprogramikatan'] = $pencairanprogramikatan;

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['user'] = $user;
        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        return view('worksheetom.pencairanprogramikatan2026.index', $data);
    }

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        $data['roles_show_cabang'] = config('global.roles_show_cabang');
        return view('worksheetom.pencairanprogramikatan2026.create', $data);
    }

    public function destroy($kode_pencairan)
    {
        $kode_pencairan = \Illuminate\Support\Facades\Crypt::decrypt($kode_pencairan);
        try {
            DB::beginTransaction();
            PencairanProgramIkatan2026::where('kode_pencairan', $kode_pencairan)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function store(Request $request)
    {
        $user = User::findorFail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            $request->validate([
                'tanggal' => 'required',
                'kode_program' => 'required',
                'semester' => 'required',
                'tahun' => 'required',
                'keterangan' => 'required'
            ]);
        } else {
            $request->validate([
                'tanggal' => 'required',
                'kode_program' => 'required',
                'kode_cabang' => 'required',
                'semester' => 'required',
                'tahun' => 'required',
                'keterangan' => 'required'
            ]);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        $semester = $request->semester;
        $tahun = $request->tahun;


            
        // Use standard code generation logic, assuming PC<YY><CAB><XXXX> or similar?
        // Let's check the old controller logic: `PC` . substr($tahun, 2, 2) . $kode_cabang . $format
        
        $format = "PC" . substr($tahun, 2, 2) . $kode_cabang;
        $lastpencairan = PencairanProgramIkatan2026::select('kode_pencairan')
            ->where('kode_pencairan', 'like', $format . '%')
            ->orderBy('kode_pencairan', 'desc')
            ->first();
        
        $last_kode_pencairan = $lastpencairan ? $lastpencairan->kode_pencairan : '';
        $kode_pencairan = buatkode($last_kode_pencairan, $format, 4);

        try {
            DB::beginTransaction();
            PencairanProgramIkatan2026::create([
                'kode_pencairan' => $kode_pencairan,
                'tanggal' => $request->tanggal,
                'kode_program' => $request->kode_program,
                'kode_cabang' => $kode_cabang,
                'bulan' => NULL,
                'semester' => $semester,
                'tahun' => $tahun,
                'keterangan' => $request->keterangan,
                'status' => '0'
            ]);
            DB::commit();
            return Redirect::route('pencairanprogramikatan2026.index')->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function setpencairan($kode_pencairan)
    {
        $kode_pencairan = \Illuminate\Support\Facades\Crypt::decrypt($kode_pencairan);
        $query = PencairanProgramIkatan2026::query();
        $query->select(
            'marketing_pencairan_ikatan_2026.*',
            'cabang.nama_cabang',
            'nama_program',
        );
        $query->join('cabang', 'marketing_pencairan_ikatan_2026.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_pencairan_ikatan_2026.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_pencairan_ikatan_2026.tanggal', 'desc');
        $query->where('kode_pencairan', $kode_pencairan);
        $pencairanprogramikatan = $query->first();


        $pelangganprogram = \App\Models\MktIkatanDetail2026::select(
            'mkt_ikatan_detail_2026.kode_pelanggan',
            'mkt_ikatan_detail_2026.top',
            'mkt_ikatan_detail_2026.metode_pembayaran',
            'mkt_ikatan_detail_2026.qty_target',
            'mkt_ikatan_detail_2026.reward',
            'mkt_ikatan_detail_2026.tipe_reward',
            'mkt_ikatan_detail_2026.budget_smm',
            'mkt_ikatan_detail_2026.budget_rsm',
            'mkt_ikatan_detail_2026.budget_gm'
        )
            ->join('pelanggan', 'mkt_ikatan_detail_2026.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('mkt_ikatan_2026', 'mkt_ikatan_detail_2026.no_pengajuan', '=', 'mkt_ikatan_2026.no_pengajuan')
            ->where('mkt_ikatan_2026.status', 1)
            ->where('mkt_ikatan_2026.kode_program', $pencairanprogramikatan->kode_program)
            ->where('mkt_ikatan_2026.semester', $pencairanprogramikatan->semester)
            ->where('mkt_ikatan_2026.kode_cabang', $pencairanprogramikatan->kode_cabang);


        $target_ikatan = MktIkatanTarget2026::select(
            'no_pengajuan',
            'kode_pelanggan',
            DB::raw('SUM(avg) as avg'),
            DB::raw('SUM(target_perbulan) as target_perbulan')
        )
            ->groupBy('no_pengajuan', 'kode_pelanggan');

        $pelangganprogram->leftJoinSub($target_ikatan, 'target_ikatan', function ($join) {
            $join->on('mkt_ikatan_detail_2026.no_pengajuan', '=', 'target_ikatan.no_pengajuan');
            $join->on('mkt_ikatan_detail_2026.kode_pelanggan', '=', 'target_ikatan.kode_pelanggan');
        });
        
        $pelangganprogram->addSelect('target_ikatan.avg', 'target_ikatan.target_perbulan');


        $detail = \App\Models\DetailPencairanProgramIkatan2026::join('pelanggan', 'marketing_pencairan_ikatan_detail_2026.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_pencairan_ikatan_2026', 'marketing_pencairan_ikatan_detail_2026.kode_pencairan', '=', 'marketing_pencairan_ikatan_2026.kode_pencairan')
            ->leftJoinSub($pelangganprogram, 'pelangganprogram', function ($join) {
                $join->on('marketing_pencairan_ikatan_detail_2026.kode_pelanggan', '=', 'pelangganprogram.kode_pelanggan');
            })
            ->select(
                'marketing_pencairan_ikatan_detail_2026.*',
                'pelanggan.nama_pelanggan',
                'pelanggan.foto',
                'top',
                'metode_pembayaran',
                'qty_target',
                'marketing_pencairan_ikatan_detail_2026.rate',
                // 'reward', // Ambiguous and covered by marketing_pencairan_ikatan_detail_2026.reward
                'tipe_reward',
                'budget_smm',
                'budget_rsm',
                'budget_gm',
                'budget_rsm',
                'budget_gm',
                'kode_program',
                'avg',
                'target_perbulan'
            )
            ->where('marketing_pencairan_ikatan_detail_2026.kode_pencairan', $kode_pencairan)
            ->orderBy('pelangganprogram.metode_pembayaran')
            ->get();

        $detail->transform(function ($item) {
            $item->kenaikan_per_bulan = ($item->target_perbulan ?? 0) / 6;
            // Map new columns to old names if view not updated yet? No, plan is to update view.
            return $item;
        });
            
        $data['pencairanprogram'] = $pencairanprogramikatan;
        $data['detail'] = $detail;
        $data['user'] = User::find(auth()->user()->id);
        return view('worksheetom.pencairanprogramikatan2026.setpencairan', $data);
    }

    public function tambahpelanggan($kode_pencairan)
    {
        $kode_pencairan = \Illuminate\Support\Facades\Crypt::decrypt($kode_pencairan);
        $data['kode_pencairan'] = $kode_pencairan;
        return view('worksheetom.pencairanprogramikatan2026.tambahpelanggan', $data);
    }

    public function getpelanggan(Request $request)
    {
        $kode_pencairan = \Illuminate\Support\Facades\Crypt::decrypt($request->kode_pencairan);
        $pencairanprogram = PencairanProgramIkatan2026::where('kode_pencairan', $kode_pencairan)
            ->join('cabang', 'marketing_pencairan_ikatan_2026.kode_cabang', '=', 'cabang.kode_cabang')
            ->join('program_ikatan', 'marketing_pencairan_ikatan_2026.kode_program', '=', 'program_ikatan.kode_program')
            ->first();

        $pelanggansudahdicairkan = DetailPencairanProgramIkatan2026::join('marketing_pencairan_ikatan_2026', 'marketing_pencairan_ikatan_detail_2026.kode_pencairan', '=', 'marketing_pencairan_ikatan_2026.kode_pencairan')
            ->select('kode_pelanggan')
            ->where('marketing_pencairan_ikatan_2026.semester', $pencairanprogram->semester)
            ->where('marketing_pencairan_ikatan_2026.tahun', $pencairanprogram->tahun)
            ->where('marketing_pencairan_ikatan_2026.kode_program', $pencairanprogram->kode_program)
            ->where('marketing_pencairan_ikatan_2026.kode_cabang', $pencairanprogram->kode_cabang);


        $listpelangganikatan = MktIkatanDetail2026::select(
            'mkt_ikatan_detail_2026.kode_pelanggan',
            'mkt_ikatan_detail_2026.top',
            'mkt_ikatan_detail_2026.reward',
            'mkt_ikatan_detail_2026.tipe_reward',
            'mkt_ikatan_detail_2026.budget_smm',
            'mkt_ikatan_detail_2026.budget_rsm',
            'mkt_ikatan_detail_2026.budget_gm',
            'mkt_ikatan_detail_2026.qty_target'
        )
            ->join('pelanggan', 'mkt_ikatan_detail_2026.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('mkt_ikatan_2026', 'mkt_ikatan_detail_2026.no_pengajuan', '=', 'mkt_ikatan_2026.no_pengajuan')
            ->where('mkt_ikatan_2026.status', 1)
            ->where('mkt_ikatan_2026.kode_program', $pencairanprogram->kode_program)
            ->where('mkt_ikatan_2026.semester', $pencairanprogram->semester)
            ->where('mkt_ikatan_2026.kode_cabang', $pencairanprogram->kode_cabang)
             ->whereNotIn('mkt_ikatan_detail_2026.kode_pelanggan', $pelanggansudahdicairkan);

        if ($pencairanprogram->semester == 1) {
             $start_date = $pencairanprogram->tahun . '-01-01';
             $end_date = $pencairanprogram->tahun . '-06-30';
        } else {
             $start_date = $pencairanprogram->tahun . '-07-01';
             $end_date = $pencairanprogram->tahun . '-12-31';
        }
        $produk = json_decode($pencairanprogram->produk, true) ?? [];

        $detailpenjualan = Detailpenjualan::select(
            'marketing_penjualan.kode_pelanggan',
            DB::raw('SUM(floor(jumlah/isi_pcs_dus)) as jml_dus'),
            DB::raw('SUM(IF(jenis_transaksi = "T", floor(jumlah/isi_pcs_dus), 0)) as jml_tunai'),
            DB::raw('SUM(IF(jenis_transaksi = "K", floor(jumlah/isi_pcs_dus), 0)) as jml_kredit'),
        )
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->joinSub($listpelangganikatan, 'listpelangganikatan', function ($join) {
                $join->on('marketing_penjualan.kode_pelanggan', '=', 'listpelangganikatan.kode_pelanggan');
            })
            ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
             ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= listpelangganikatan.top + 3")
            ->where('status_batal', 0)
            ->whereIn('produk_harga.kode_produk', $produk)
            ->groupBy('marketing_penjualan.kode_pelanggan');


        $target_ikatan = MktIkatanTarget2026::select(
            'no_pengajuan',
            'kode_pelanggan',
            DB::raw('SUM(avg) as avg'),
            DB::raw('SUM(target_perbulan) as target_perbulan')
        )
            ->groupBy('no_pengajuan', 'kode_pelanggan');

        $data['peserta'] = MktIkatanDetail2026::select(
            'mkt_ikatan_detail_2026.kode_pelanggan',
            'mkt_ikatan_detail_2026.top',
            'pelanggan.nama_pelanggan',
            'mkt_ikatan_detail_2026.reward',
            'mkt_ikatan_detail_2026.tipe_reward',
            'mkt_ikatan_detail_2026.budget_smm',
            'mkt_ikatan_detail_2026.budget_rsm',
            'mkt_ikatan_detail_2026.budget_gm',
             'mkt_ikatan_detail_2026.qty_target',
             'target_ikatan.avg',
             'target_ikatan.target_perbulan',
            'detailpenjualan.jml_dus',
            'detailpenjualan.jml_tunai',
            'detailpenjualan.jml_kredit',
            'mkt_ikatan_2026.kode_program'
        )
            ->join('pelanggan', 'mkt_ikatan_detail_2026.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('mkt_ikatan_2026', 'mkt_ikatan_detail_2026.no_pengajuan', '=', 'mkt_ikatan_2026.no_pengajuan')
            ->leftJoinSub($target_ikatan, 'target_ikatan', function($join){
                $join->on('mkt_ikatan_detail_2026.no_pengajuan', '=', 'target_ikatan.no_pengajuan');
                $join->on('mkt_ikatan_detail_2026.kode_pelanggan', '=', 'target_ikatan.kode_pelanggan');
            })
            ->leftJoinSub($detailpenjualan, 'detailpenjualan', function ($join) {
                 $join->on('mkt_ikatan_detail_2026.kode_pelanggan', '=', 'detailpenjualan.kode_pelanggan');
            })
            ->where('mkt_ikatan_2026.status', 1)
            ->where('mkt_ikatan_2026.kode_program', $pencairanprogram->kode_program)
            ->where('mkt_ikatan_2026.semester', $pencairanprogram->semester)
             ->where('mkt_ikatan_2026.kode_cabang', $pencairanprogram->kode_cabang)
             ->whereNotIn('mkt_ikatan_detail_2026.kode_pelanggan', $pelanggansudahdicairkan)
             ->get();

        $reward_program = \App\Models\MktRewardProgram2026::with('details')->where('kode_program', $pencairanprogram->kode_program)->first();
        $reward_details = $reward_program ? $reward_program->details : collect([]);

        $data['peserta']->transform(function ($item) use ($reward_details) {
            $avg = $item->avg ?? 0;
            $target_perbulan = $item->target_perbulan ?? 0;
            $jml_dus = $item->jml_dus ?? 0;
            $total_target_pencapaian = $avg + $target_perbulan;
            $item->kenaikan_per_bulan = $target_perbulan / 6;

            // Find Reward Tier based on Kenaikan Per Bulan (previously AVG)
            $kenaikan_per_bulan = $item->kenaikan_per_bulan;
            $tier = $reward_details->first(function ($detail) use ($kenaikan_per_bulan) {
                return $kenaikan_per_bulan >= $detail->qty_dari && $kenaikan_per_bulan <= $detail->qty_sampai;
            });

            $rate = 0;
            $cap = null;
            if ($tier) {
                if ($avg == 0) {
                    if ($jml_dus >= $total_target_pencapaian) {
                        $rate = $tier->reward_tidak_minus;
                        if ($item->kode_program == 'PRIK003') {
                            $cap = 800000;
                        }
                    } else {
                        $rate = 0;
                    }
                } else {
                    if ($item->kode_program == 'PRIK003') {
                        if ($jml_dus >= $total_target_pencapaian) {
                            $rate = $tier->reward_ach_target;
                            $cap = 1200000;
                        } elseif ($jml_dus >= $avg) {
                            $rate = $tier->reward_tidak_minus;
                            $cap = 800000;
                        } elseif ($jml_dus >= $avg - ($avg * 0.05)) {
                            $rate = $tier->reward_minus;
                            $cap = 400000;
                        }
                    } else {
                        if ($jml_dus >= $total_target_pencapaian) {
                            $rate = $tier->reward_ach_target;
                        } elseif ($jml_dus >= $avg) {
                            $rate = $tier->reward_tidak_minus;
                        } elseif ($jml_dus >= $avg - ($avg * 0.10)) {
                            $rate = $tier->reward_minus;
                        }
                    }
                }
            }

            $is_flat = $tier->is_flat ?? 0;
            $item->reward_rate = $rate;

            if ($is_flat == 1) {
                $item->calculated_reward_total = $rate;
            } else {
                $item->calculated_reward_tunai = ($item->jml_tunai ?? 0) * $rate;
                $item->calculated_reward_kredit = ($item->jml_kredit ?? 0) * $rate;
                $item->calculated_reward_total = $jml_dus * $rate;
            }

            if ($cap != null && $item->calculated_reward_total > $cap) {
                $item->calculated_reward_total = $cap;
            }

            return $item;
        });

        return view('worksheetom.pencairanprogramikatan2026.getpelanggan', $data);
    }

    public function storepelanggan(Request $request, $kode_pencairan)
    {
        $kode_pencairan = \Illuminate\Support\Facades\Crypt::decrypt($kode_pencairan);
        $kode_pelanggan = $request->kode_pelanggan;
        $jumlah = $request->jumlah;
        $status = $request->status;
        $status_pencairan = $request->status_pencairan;

        DB::beginTransaction();
        try {
            $checkpelanggan = $request->input('checkpelanggan', []);
            $rate = $request->rate;
            foreach ($checkpelanggan as $index => $value) {

                if ($status[$index] == 1) {
                    DetailPencairanProgramIkatan2026::create([
                        'kode_pencairan' => $kode_pencairan,
                        'kode_pelanggan' => $kode_pelanggan[$index],
                        'realisasi' => toNumber($jumlah[$index]),
                        'reward' => toNumber($request->total_reward[$index]),
                        'rate' => toNumber($rate[$index]),
                        'status_pencairan' => $status_pencairan[$index]
                    ]);
                     
                } 
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Pelanggan Berhasil Di Proses'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function deletepelanggan($kode_pencairan, $kode_pelanggan)
    {
        $kode_pencairan = \Illuminate\Support\Facades\Crypt::decrypt($kode_pencairan);
        $kode_pelanggan = \Illuminate\Support\Facades\Crypt::decrypt($kode_pelanggan);
        try {
            DetailPencairanProgramIkatan2026::where('kode_pencairan', $kode_pencairan)->where('kode_pelanggan', $kode_pelanggan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function detailfaktur($kode_pelanggan, $kode_pencairan)
    {
        $kode_pencairan = \Illuminate\Support\Facades\Crypt::decrypt($kode_pencairan);
        $pencairanprogram = PencairanProgramIkatan2026::where('kode_pencairan', $kode_pencairan)
            ->join('program_ikatan', 'marketing_pencairan_ikatan_2026.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
            
        if ($pencairanprogram->semester == 1) {
             $start_date = $pencairanprogram->tahun . '-01-01';
             $end_date = $pencairanprogram->tahun . '-06-30';
        } else {
             $start_date = $pencairanprogram->tahun . '-07-01';
             $end_date = $pencairanprogram->tahun . '-12-31';
        }
        $produk = json_decode($pencairanprogram->produk, true) ?? [];

        $detailpenjualan = Detailpenjualan::select(
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.tanggal_pelunasan',
            'marketing_penjualan.jenis_transaksi',
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            DB::raw('floor(jumlah/isi_pcs_dus) as jml_dus'),
        )
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
            ->where('marketing_penjualan.kode_pelanggan', $kode_pelanggan)
            ->where('status_batal', 0)
            ->whereIn('produk_harga.kode_produk', $produk)
            ->get();

         return view('worksheetom.pencairanprogramikatan.detailfaktur', compact('detailpenjualan'));
    }
}


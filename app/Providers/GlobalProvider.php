<?php

namespace App\Providers;

use App\Models\Ajuanprogramikatan;
use App\Models\Ajuanprogramikatanenambulan;
use App\Models\Ajuanprogramkumulatif;
use App\Models\Ajuantransferdana;
use App\Models\Disposisiajuanfaktur;
use App\Models\Disposisiajuanlimitkredit;
use App\Models\Disposisiizinabsen;
use App\Models\Disposisiizincuti;
use App\Models\Disposisiizindinas;
use App\Models\Disposisiizinkeluar;
use App\Models\Disposisiizinkoreksi;
use App\Models\Disposisiizinpulang;
use App\Models\Disposisiizinsakit;
use App\Models\Disposisiizinterlambat;
use App\Models\Disposisilembur;
use App\Models\Disposisipenilaiankaryawan;
use App\Models\Disposisitargetkomisi;
use App\Models\Izinabsen;
use App\Models\Izincuti;
use App\Models\Izindinas;
use App\Models\Izinkeluar;
use App\Models\Izinkoreksi;
use App\Models\Izinpulang;
use App\Models\Izinsakit;
use App\Models\Izinterlambat;
use App\Models\Pencairanprogram;
use App\Models\Pencairanprogramenambulan;
use App\Models\Pencairanprogramikatan;
use App\Models\Ticket;
use App\Models\Ticketupdatedata;
use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;

class GlobalProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(Guard $auth): void
    {
        view()->composer('*', function ($view) use ($auth) {
            $roles_show_cabang = [
                'super admin',
                'gm marketing',
                'gm administrasi',
                'manager keuangan',
                'direktur',
                'regional sales manager',
                'asst. manager hrd',
                'staff keuangan',
                'admin pajak',
                'manager audit',
                'audit',
                'regional operation manager',
                'crm',
                'spv accounting',
                'manager general affair',
                'general affair',
                'spv presensi',
                'manager gudang',
                'spv gudang pusat',
                'admin gudang pusat',
                'gm operasional',
                'admin pusat'
            ];

            $roles_show_cabang_pjp = [
                'super admin',
                'gm marketing',
                'gm administrasi',
                'manager keuangan',
                'direktur',
                'regional sales manager',
                'asst. manager hrd',
                'staff keuangan',
                'regional operation manager',
            ];
            $start_periode = '2023-01-01';
            $end_periode = date('Y') . '-12-31';
            $namabulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            if ($auth->check()) {
                $level_user = auth()->user()->roles->pluck('name')[0];
                $roles_can_approve_presensi = config('presensi.approval');
                $level_hrd = config('presensi.approval.level_hrd');

                $notifikasi_limitkredit = Disposisiajuanlimitkredit::where('id_penerima', auth()->user()->id)->where('status', 0)->count();
                $notifikasi_ajuanfaktur = Disposisiajuanfaktur::where('id_penerima', auth()->user()->id)->where('status', 0)->count();
                $notifikasi_target = Disposisitargetkomisi::where('id_penerima', auth()->user()->id)->where('status', 0)->count();
                $notifikasi_pengajuan_marketing = $notifikasi_limitkredit + $notifikasi_ajuanfaktur;
                $notifikasi_komisi = $notifikasi_target;
                $notifikasi_marketing = $notifikasi_pengajuan_marketing + $notifikasi_komisi;




                $notifikasi_penilaiankaryawan = Disposisipenilaiankaryawan::where('id_penerima', auth()->user()->id)->where('status', 0)->count();

                $cek_approval_presensi = $roles_can_approve_presensi[$level_user] ?? [];
                //Jika Bukan Direktur
                $izinabsen = new Izinabsen();
                $izinkeluar = new Izinkeluar();
                $izinpulang = new Izinpulang();
                $izinterlambat = new Izinterlambat();
                $izinsakit = new Izinsakit();
                $izincuti = new Izincuti();
                $izindinas = new Izindinas();
                $izinkoreksi = new Izinkoreksi();
                $notifikasi_izinabsen = $cek_approval_presensi || in_array($level_user, $level_hrd) || $level_user == 'direktur' ? $izinabsen->getIzinabsen(cekPending: true)->count() : 0;
                $notifikasi_izinkeluar = $cek_approval_presensi || in_array($level_user, $level_hrd) || $level_user == 'direktur' ? $izinkeluar->getIzinkeluar(cekPending: true)->count() : 0;
                $notifikasi_izinpulang = $cek_approval_presensi || in_array($level_user, $level_hrd) || $level_user == 'direktur' ? $izinpulang->getIzinpulang(cekPending: true)->count() : 0;
                $notifikasi_izinterlambat = $cek_approval_presensi || in_array($level_user, $level_hrd) || $level_user == 'direktur' ? $izinterlambat->getIzinterlambat(cekPending: true)->count() : 0;
                $notifikasi_izinsakit = $cek_approval_presensi || in_array($level_user, $level_hrd) || $level_user == 'direktur' ? $izinsakit->getIzinsakit(cekPending: true)->count() : 0;
                $notifikasi_izincuti = $cek_approval_presensi || in_array($level_user, $level_hrd) || $level_user == 'direktur' ? $izincuti->getIzincuti(cekPending: true)->count() : 0;
                $notifikasi_izindinas = $cek_approval_presensi || in_array($level_user, $level_hrd) || $level_user == 'direktur' ? $izindinas->getIzindinas(cekPending: true)->count() : 0;
                $notifikasi_izinkoreksi = $cek_approval_presensi || in_array($level_user, $level_hrd) || $level_user == 'direktur' ? $izinkoreksi->getIzinkoreksi(cekPending: true)->count() : 0;









                $notifikasi_pengajuan_izin = $notifikasi_izinabsen + $notifikasi_izincuti + $notifikasi_izinterlambat + $notifikasi_izinsakit + $notifikasi_izinpulang + $notifikasi_izindinas + $notifikasi_izinkoreksi + $notifikasi_izinkeluar;

                $notifikasi_lembur = Disposisilembur::where('id_penerima', auth()->user()->id)->where('status', 0)->count();

                //Notifikasi SPV Presensi
                if ($level_user == 'spv presensi') {
                    $notifikasi_izinabsen_presensi = Disposisiizinabsen::where('hrd_izinabsen_disposisi.status', 0)
                        ->leftJoin('users', 'hrd_izinabsen_disposisi.id_penerima', '=', 'users.id')
                        ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                        ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('roles.name', 'asst. manager hrd')
                        ->count();
                    $notifikasi_izincuti_presensi = Disposisiizincuti::where('hrd_izincuti_disposisi.status', 0)
                        ->leftJoin('users', 'hrd_izincuti_disposisi.id_penerima', '=', 'users.id')
                        ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                        ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('roles.name', 'asst. manager hrd')
                        ->count();
                    $notifikasi_izinterlambat_presensi = Disposisiizinterlambat::where('hrd_izinterlambat_disposisi.status', 0)
                        ->leftJoin('users', 'hrd_izinterlambat_disposisi.id_penerima', '=', 'users.id')
                        ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                        ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('roles.name', 'asst. manager hrd')
                        ->count();
                    $notifikasi_izinsakit_presensi = Disposisiizinsakit::where('hrd_izinsakit_disposisi.status', 0)
                        ->leftJoin('users', 'hrd_izinsakit_disposisi.id_penerima', '=', 'users.id')
                        ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                        ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('roles.name', 'asst. manager hrd')
                        ->count();
                    $notifikasi_izinpulang_presensi = Disposisiizinpulang::where('hrd_izinpulang_disposisi.status', 0)
                        ->leftJoin('users', 'hrd_izinpulang_disposisi.id_penerima', '=', 'users.id')
                        ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                        ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('roles.name', 'asst. manager hrd')
                        ->count();
                    $notifikasi_izindinas_presensi = Disposisiizindinas::where('hrd_izindinas_disposisi.status', 0)
                        ->leftJoin('users', 'hrd_izindinas_disposisi.id_penerima', '=', 'users.id')
                        ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                        ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('roles.name', 'asst. manager hrd')
                        ->count();
                    $notifikasi_izinkoreksi_presensi = Disposisiizinkoreksi::where('hrd_izinkoreksi_disposisi.status', 0)
                        ->leftJoin('users', 'hrd_izinkoreksi_disposisi.id_penerima', '=', 'users.id')
                        ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                        ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('roles.name', 'asst. manager hrd')
                        ->count();
                    $notifikasi_izinkeluar_presensi = Disposisiizinkeluar::where('hrd_izinkeluar_disposisi.status', 0)
                        ->leftJoin('users', 'hrd_izinkeluar_disposisi.id_penerima', '=', 'users.id')
                        ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                        ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('roles.name', 'asst. manager hrd')
                        ->count();

                    $total_notifikasi_izin_spvpresensi = $notifikasi_izinabsen_presensi + $notifikasi_izincuti_presensi +
                        $notifikasi_izinterlambat_presensi + $notifikasi_izinsakit_presensi + $notifikasi_izinpulang_presensi +
                        $notifikasi_izindinas_presensi + $notifikasi_izinkoreksi_presensi + $notifikasi_izinkeluar_presensi;
                } else {
                    $notifikasi_izinabsen_presensi = 0;
                    $notifikasi_izincuti_presensi = 0;
                    $notifikasi_izinterlambat_presensi = 0;
                    $notifikasi_izinsakit_presensi = 0;
                    $notifikasi_izinpulang_presensi = 0;
                    $notifikasi_izindinas_presensi = 0;
                    $notifikasi_izinkoreksi_presensi = 0;
                    $notifikasi_izinkeluar_presensi = 0;
                    $total_notifikasi_izin_spvpresensi = 0;
                }
                // $notifikasi_log = Activity::where('status_log', 0)->whereIn('event', ['update', 'cancel', 'delete'])->count();
                $notifikasi_update_data = Ticketupdatedata::where('status', 0)->count();
                if ($level_user == "manager keuangan") {
                    $qajuantransferdana = Ajuantransferdana::query();
                    $qajuantransferdana->select(
                        'keuangan_ajuantransferdana.*',
                        'keuangan_setoranpusat_ajuantransfer.kode_setoran',
                        'keuangan_setoranpusat.tanggal as tanggal_proses',
                        'keuangan_setoranpusat.status as status_setoran',
                        'nama_cabang'
                    );
                    $qajuantransferdana->join('cabang', 'keuangan_ajuantransferdana.kode_cabang', '=', 'cabang.kode_cabang');
                    $qajuantransferdana->leftJoin('keuangan_setoranpusat_ajuantransfer', 'keuangan_ajuantransferdana.no_pengajuan', '=', 'keuangan_setoranpusat_ajuantransfer.no_pengajuan');
                    $qajuantransferdana->leftJoin('keuangan_setoranpusat', 'keuangan_setoranpusat_ajuantransfer.kode_setoran', '=', 'keuangan_setoranpusat.kode_setoran');
                    $qajuantransferdana->where('keuangan_ajuantransferdana.status', 0);
                    $notifikasiajuantransferdana  = $qajuantransferdana->count();
                } else if ($level_user == "operation manager") {

                    $qajuantransferdana = Ajuantransferdana::query();
                    $qajuantransferdana->select(
                        'keuangan_ajuantransferdana.*',
                        'keuangan_setoranpusat_ajuantransfer.kode_setoran',
                        'keuangan_setoranpusat.tanggal as tanggal_proses',
                        'keuangan_setoranpusat.status as status_setoran',
                        'nama_cabang'
                    );
                    $qajuantransferdana->join('cabang', 'keuangan_ajuantransferdana.kode_cabang', '=', 'cabang.kode_cabang');
                    $qajuantransferdana->leftJoin('keuangan_setoranpusat_ajuantransfer', 'keuangan_ajuantransferdana.no_pengajuan', '=', 'keuangan_setoranpusat_ajuantransfer.no_pengajuan');
                    $qajuantransferdana->leftJoin('keuangan_setoranpusat', 'keuangan_setoranpusat_ajuantransfer.kode_setoran', '=', 'keuangan_setoranpusat.kode_setoran');
                    $qajuantransferdana->whereNull('keuangan_setoranpusat_ajuantransfer.kode_setoran');
                    $qajuantransferdana->where('keuangan_ajuantransferdana.kode_cabang', auth()->user()->kode_cabang);
                    $notifikasiajuantransferdana  = $qajuantransferdana->count();
                } else {
                    $notifikasiajuantransferdana = 0;
                }


                //NOtifikasi Ajuan Program
                if ($level_user == 'operation manager') {
                    $notifikasi_ajuanprogramikatan = Ajuanprogramikatan::whereNull('om')->where('kode_cabang', auth()->user()->kode_cabang)->count();
                    $notifikasi_pencairanprogramikatan = Pencairanprogramikatan::whereNull('marketing_pencairan_ikatan.om')
                        ->where('marketing_pencairan_ikatan.kode_cabang', auth()->user()->kode_cabang)
                        ->count();

                    $notifikasi_ajuanprogramkumulatif = Ajuanprogramkumulatif::whereNull('om')->where('kode_cabang', auth()->user()->kode_cabang)->count();
                    $notifikasi_pencairanprogramkumulatif = Pencairanprogram::whereNull('om')
                        ->where('kode_cabang', auth()->user()->kode_cabang)
                        ->count();

                    $notifikasi_ajuanprogramikatanenambulan = Ajuanprogramikatanenambulan::whereNull('om')->where('kode_cabang', auth()->user()->kode_cabang)->count();
                    $notifikasi_pencairanprogramikatanenambulan = Pencairanprogramenambulan::whereNull('marketing_pencairan_ikatan_enambulan.om')
                        ->where('marketing_pencairan_ikatan_enambulan.kode_cabang', auth()->user()->kode_cabang)
                        ->count();
                } else if ($level_user == 'regional sales manager') {
                    $notifikasi_ajuanprogramikatan = Ajuanprogramikatan::whereNull('rsm')
                        ->join('cabang', 'marketing_program_ikatan.kode_cabang', '=', 'cabang.kode_cabang')
                        ->where('cabang.kode_regional', auth()->user()->kode_regional)
                        ->whereNotNull('om')
                        ->count();
                    $notifikasi_pencairanprogramikatan = Pencairanprogramikatan::whereNull('marketing_pencairan_ikatan.rsm')
                        ->join('cabang', 'marketing_pencairan_ikatan.kode_cabang', '=', 'cabang.kode_cabang')
                        ->where('cabang.kode_regional', auth()->user()->kode_regional)
                        ->whereNotNull('marketing_pencairan_ikatan.om')
                        ->count();

                    $notifikasi_ajuanprogramkumulatif = Ajuanprogramkumulatif::whereNull('rsm')
                        ->join('cabang', 'marketing_program_kumulatif.kode_cabang', '=', 'cabang.kode_cabang')
                        ->where('cabang.kode_regional', auth()->user()->kode_regional)
                        ->whereNotNull('om')

                        ->count();
                    $notifikasi_pencairanprogramkumulatif = Pencairanprogram::whereNull('rsm')
                        ->join('cabang', 'marketing_program_pencairan.kode_cabang', '=', 'cabang.kode_cabang')
                        ->where('kode_regional', auth()->user()->kode_regional)
                        ->whereNotNull('om')
                        ->count();


                    $notifikasi_ajuanprogramikatanenambulan = Ajuanprogramikatanenambulan::whereNull('rsm')
                        ->join('cabang', 'marketing_program_ikatan_enambulan.kode_cabang', '=', 'cabang.kode_cabang')
                        ->where('cabang.kode_regional', auth()->user()->kode_regional)
                        ->whereNotNull('om')
                        ->count();
                    $notifikasi_pencairanprogramikatanenambulan = Pencairanprogramenambulan::whereNull('marketing_pencairan_ikatan_enambulan.rsm')
                        ->join('cabang', 'marketing_pencairan_ikatan_enambulan.kode_cabang', '=', 'cabang.kode_cabang')
                        ->where('cabang.kode_regional', auth()->user()->kode_regional)
                        ->whereNotNull('marketing_pencairan_ikatan_enambulan.om')
                        ->count();
                } else if ($level_user == 'gm marketing') {
                    $notifikasi_ajuanprogramikatan = Ajuanprogramikatan::whereNull('gm')
                        ->whereNotNull('rsm')
                        ->where('status', 0)
                        ->count();

                    $notifikasi_pencairanprogramikatan = Pencairanprogramikatan::whereNull('marketing_pencairan_ikatan.gm')
                        ->where('marketing_pencairan_ikatan.status', 0)
                        ->whereNotNull('marketing_pencairan_ikatan.rsm')
                        ->count();

                    $notifikasi_ajuanprogramkumulatif = Ajuanprogramkumulatif::whereNull('gm')
                        ->whereNotNull('rsm')
                        ->where('status', 0)
                        ->count();
                    $notifikasi_pencairanprogramkumulatif = Pencairanprogram::whereNull('gm')

                        ->whereNotNull('rsm')
                        ->where('status', 0)
                        ->count();

                    $notifikasi_ajuanprogramikatanenambulan = Ajuanprogramikatanenambulan::whereNull('gm')
                        ->whereNotNull('rsm')
                        ->where('status', 0)
                        ->count();

                    $notifikasi_pencairanprogramikatanenambulan = Pencairanprogramenambulan::whereNull('marketing_pencairan_ikatan_enambulan.gm')
                        ->where('marketing_pencairan_ikatan_enambulan.status', 0)
                        ->whereNotNull('marketing_pencairan_ikatan_enambulan.rsm')
                        ->count();
                } else if ($level_user == 'direktur') {
                    $notifikasi_ajuanprogramikatan = Ajuanprogramikatan::whereNull('direktur')
                        ->whereNotNull('gm')
                        ->where('status', 0)
                        ->count();
                    $notifikasi_pencairanprogramikatan = Pencairanprogramikatan::whereNull('marketing_pencairan_ikatan.direktur')
                        ->where('marketing_pencairan_ikatan.status', 0)
                        ->whereNotNull('marketing_pencairan_ikatan.gm')
                        ->count();

                    $notifikasi_ajuanprogramkumulatif = Ajuanprogramkumulatif::whereNull('direktur')
                        ->whereNotNull('gm')
                        ->where('status', 0)
                        ->count();
                    $notifikasi_pencairanprogramkumulatif = Pencairanprogram::whereNull('direktur')
                        ->whereNotNull('gm')
                        ->where('status', 0)
                        ->count();

                    $notifikasi_ajuanprogramikatanenambulan = Ajuanprogramikatanenambulan::whereNull('direktur')
                        ->whereNotNull('gm')
                        ->where('status', 0)
                        ->count();

                    $notifikasi_pencairanprogramikatanenambulan = Pencairanprogramenambulan::whereNull('direktur')
                        ->whereNotNull('gm')
                        ->where('status', 0)
                        ->count();
                } else {
                    $notifikasi_ajuanprogramikatan = 0;
                    $notifikasi_pencairanprogramikatan = 0;
                    $notifikasi_ajuanprogramkumulatif = 0;
                    $notifikasi_pencairanprogramkumulatif = 0;
                    $notifikasi_ajuanprogramikatanenambulan = 0;
                    $notifikasi_pencairanprogramikatanenambulan = 0;
                }


                $notifikasi_ajuan_program = $notifikasi_ajuanprogramikatan + $notifikasi_pencairanprogramikatan + $notifikasi_ajuanprogramkumulatif + $notifikasi_pencairanprogramkumulatif + $notifikasi_ajuanprogramikatanenambulan + $notifikasi_pencairanprogramikatanenambulan;
                $notifikasi_hrd = $notifikasi_penilaiankaryawan + $notifikasi_pengajuan_izin + $notifikasi_lembur;
                $total_notifikasi = $notifikasi_marketing + $notifikasi_hrd + $notifikasiajuantransferdana;


                //Notifikasi Ticket
                if ($level_user == 'gm administrasi') {
                    $notifikasi_ticket = Ticket::whereNull('gm')->where('status', 0)->count();
                } else if ($level_user == 'super admin') {
                    $notifikasi_ticket = Ticket::where('status', 0)->whereNotNull('gm')->count();
                } else {
                    $notifikasi_ticket = 0;
                }
            } else {
                $level_user = '';
                $notifikasiajuantransferdana = 0;
                $notifikasi_limitkredit = 0;
                $notifikasi_ajuanfaktur = 0;
                $notifikasi_pengajuan_marketing = 0;
                $notifikasi_target = 0;
                $notifikasi_komisi = 0;
                $notifikasi_marketing = 0;
                $notifikasi_penilaiankaryawan = 0;
                $notifikasi_izinabsen = 0;
                $notifikasi_izincuti = 0;
                $notifikasi_izinterlambat = 0;
                $notifikasi_izinsakit = 0;
                $notifikasi_izinpulang = 0;
                $notifikasi_izindinas = 0;
                $notifikasi_izinkoreksi = 0;
                $notifikasi_izinkeluar = 0;
                $notifikasi_pengajuan_izin = 0;
                $notifikasi_lembur = 0;

                $notifikasi_hrd = 0;
                $total_notifikasi = 0;

                $notifikasi_ajuanprogramikatan = 0;
                $notifikasi_pencairanprogramikatan = 0;
                $notifikasi_ajuanprogramkumulatif = 0;
                $notifikasi_pencairanprogramkumulatif = 0;
                $notifikasi_ajuanprogramikatanenambulan = 0;
                $notifikasi_pencairanprogramikatanenambulan = 0;
                $notifikasi_ajuan_program = 0;

                $notifikasi_izinabsen_presensi = 0;
                $notifikasi_izincuti_presensi = 0;
                $notifikasi_izinterlambat_presensi = 0;
                $notifikasi_izinsakit_presensi = 0;
                $notifikasi_izinpulang_presensi = 0;
                $notifikasi_izindinas_presensi = 0;
                $notifikasi_izinkoreksi_presensi = 0;
                $notifikasi_izinkeluar_presensi = 0;
                $total_notifikasi_izin_spvpresensi = 0;

                $notifikasi_ticket = 0;
                //$notifikasi_log = 0;
                $notifikasi_update_data = 0;
            }

            if ($level_user == "gm administrasi") {
                $start_periode = '2023-01-01';
                $end_periode = date('Y') . '-12-31';
            }

            $datamaster_request = [
                'regional',
                'regional/*',
                'cabang',
                'cabang/*',
                'salesman',
                'salesman/*',
                'kategoriproduk',
                'kategoriproduk/*',
                'jenisproduk',
                'jenisproduk/*',
                'produk',
                'produk/*',
                'harga',
                'harga/*',
                'pelanggan',
                'pelanggan/*',
                'wilayah',
                'wilayah/*',
                'kendaraan',
                'kendaraan/*',
                'supplier',
                'supplier/*',
                'karyawan',
                'karyawan/*',
                'rekening',
                'rekening/*',
                'gaji',
                'gaji/*',
                'insentif',
                'insentif/*',
                'bpjskesehatan',
                'bpjskesehatan/*',
                'bpjstenagakerja',
                'bpjstenagakerja/*',
                'bufferstok',
                'bufferstok/*',
                'barangproduksi',
                'barangproduksi/*',
                'tujuanangkutan',
                'tujuanangkutan/*',
                'angkutan',
                'angkutan/*',
                'barangpembelian',
                'driverhelper'
            ];


            $datamaster_permission = [
                'regional.index',
                'cabang.index',
                'salesman.index',
                'kategoriproduk.index',
                'jenisproduk.index',
                'produk.index',
                'hraga.index',
                'pelanggan.index',
                'wilayah.index',
                'kendaraan.index',
                'supplier.index',
                'karyawan.index',
                'rekening.index',
                'gaji.index',
                'insentif.index',
                'bpjskesehatan.index',
                'bpjstenagakerja.index',
                'barangproduksi.index',
                'bufferstok.index',
                'driverhelper.index',
                'angkutan.index',
                'tujuanangkutan.index',
                'barangpembelian.index'
            ];

            //Produksi
            $produksi_request = [
                'bpbj',
                'bpbj/*',
                'fsthp',
                'fsthp/*',
                'samutasiproduksi',
                'samutasiproduksi/*',
                'barangmasukproduksi',
                'barangmasukproduksi/*',
                'barangkeluarproduksi',
                'barangkeluarproduksi/*',
                'sabarangproduksi',
                'sabarangproduksi/*',
                'permintaanproduksi',
                'permintaanproduksi/*',
                'laporanproduksi',
                'laporanproduksi/*',
            ];


            $produksi_permission = [
                'bpbj.index',
                'fsthp.index',
                'samutasiproduksi.index',
                'barangmasukproduksi.index',
                'barangkeluarproduksi.index',
                'sabarangproduksi.index',
                'permintaanproduksi.index',
                'prd.mutasiproduksi',
                'prd.rekapmutasi',
                'prd.pemasukan',
                'prd.pengeluaran',
                'prd.rekappersediaan'
            ];

            $produksi_mutasi_produk_request = ['samutasiproduksi', 'samutasiproduksi/*', 'bpbj', 'bpbj/*', 'fsthp', 'fsthp/*'];
            $produksi_mutasi_produk_permission = ['bpbj.index', 'fsthp.index', 'samutasiproduksi.index'];
            $produksi_mutasi_barang_request = [
                'sabarangproduksi',
                'sabarangproduksi/*',
                'barangmasukproduksi',
                'barangmasukproduksi/*',
                'barangkeluarproduksi',
                'barangkeluarproduksi/*'
            ];
            $produksi_mutasi_barang_permission = ['barangmasukproduksi.index', 'barangkeluarproduksi.index', 'sabarangproduksi.index'];
            $produksi_laporan_permission = ['prd.mutasiproduksi', 'prd.rekapmutasi', 'prd.pemasukan', 'prd.pengeluaran', 'prd.rekappersediaan'];

            $gudang_jadi_request = [
                'sagudangjadi',
                'sagudangjadi/*',
                'suratjalan',
                'suratjalan/*',
                'fsthpgudang',
                'fsthpgudang/*',
                'repackgudangjadi',
                'repackgudangjadi/*',
                'rejectgudangjadi',
                'rejectgudangjadi/*',
                'lainnyagudangjadi',
                'lainnyagudangjadi/*',
                'suratjalanangkutan',
                'suratjalanangkutan/*',
                'laporangudangjadi',
                'laporangudangjadi/*',
                'kontrabonangkutan',
                'kontrabonangkutan/*'
            ];

            $gudang_jadi_permission = [
                'suratjalan.index',
                'fsthpgudang.index',
                'sagudangjadi.index',
                'repackgudangjadi.index',
                'rejectgudangjadi.index',
                'lainnyagudangjadi.index',
                'kontrabonangkutan.index',
                'gj.persediaan',
                'gj.rekappersediaan',
                'gj.rekaphasilproduksi',
                'gj.rekappengeluaran',
                'gj.realisasikiriman',
                'gj.realisasioman',
                'gj.angkutan',
                'suratjalanangkutan.index'
            ];

            $gudang_jadi_mutasi_request = [
                'sagudangjadi',
                'sagudangjadi/*',
                'suratjalan',
                'suratjalan/*',
                'fsthpgudang',
                'fsthpgudang/*',
                'repackgudangjadi',
                'repackgudangjadi/*',
                'rejectgudangjadi',
                'rejectgudangjadi/*',
                'lainnyagudangjadi',
                'lainnyagudangjadi/*',
            ];

            $gudang_jadi_mutasi_permission = [
                'sagudangjadi.index',
                'suratjalan.index',
                'fsthpgudang.index',
                'repackgudangjadi.index',
                'lainnyagudangjadi.index',
            ];
            $gudang_jadi_laporan_permission = [
                'gj.persediaan',
                'gj.rekappersediaan',
                'gj.rekaphasilproduksi',
                'gj.rekappengeluaran',
                'gj.realisasikiriman',
                'gj.realisasioman',
                'gj.angkutan'
            ];

            //Gudang Bahan
            $gudang_bahan_request = [
                'barangmasukgudangbahan',
                'barangmasukgudangbahan/*',
                'barangkeluargudangbahan',
                'barangkeluargudangbahan/*',
                'sagudangbahan',
                'sagudangbahan/*',
                'sahargagb',
                'sahargagb/*',
                'opgudangbahan',
                'opgudangbahan/*',
                'laporangudangbahan',
                'laporangudangbahan/*'
            ];



            $gudang_bahan_mutasi_request = [
                'barangmasukgudangbahan',
                'barangmasukgudangbahan/*',
                'barangkeluargudangbahan',
                'barangkeluargudangbahan/*',
                'sagudangbahan',
                'sagudangbahan/*',
                'sahargagb',
                'sahargagb/*',
                'opgudangbahan',
                'opgudangbahan/*',
            ];

            $gudang_bahan_mutasi_permission = [
                'barangmasukgb.index',
                'barangkeluargb.index',
                'sagudangbahan.index',
                'sahargagb.index',
                'opgudangbahan.index',
            ];

            $gudang_bahan_laporan_permission = [
                'gb.barangmasuk',
                'gb.barangkeluar',
                'gb.persediaan',
                'gb.rekappersediaan',
                'gb.kartugudang',
            ];

            $gudang_bahan_permission = array_merge($gudang_bahan_mutasi_permission, $gudang_bahan_laporan_permission);
            $test = 'test';


            //Gudang Logistik
            $gudang_logistik_request = [
                'barangmasukgudanglogistik',
                'barangmasukgudanglogistik/*',
                'barangkeluargudanglogistik',
                'barangkeluargudanglogistik/*',
                'sagudanglogistik',
                'sagudanglogistik/*',
                'opgudanglogistik',
                'opgudanglogistik/*',
                'laporangudanglogistik',
                'laporangudanglogistik/*',
                'pembeliangudang',
            ];



            $gudang_logistik_mutasi_request = [
                'barangmasukgudanglogistik',
                'barangmasukgudanglogistik/*',
                'barangkeluargudanglogistik',
                'barangkeluargudanglogistik/*',
                'sagudanglogistik',
                'sagudanglogistik/*',
                'opgudanglogistik',
                'opgudanglogistik/*',
            ];

            $gudang_logistik_mutasi_permission = [
                'barangmasukgl.index',
                'barangkeluargl.index',
                'sagudanglogistik.index',
                'opgudanglogistik.index',
            ];

            $gudang_logistik_laporan_permission = [
                'gl.barangmasuk',
                'gl.barangkeluar',
                'gl.persediaan',
                'gl.persediaanopname',
            ];
            $gudang_logistik_permission = array_merge($gudang_logistik_mutasi_permission, $gudang_logistik_laporan_permission);

            //Gudang Cabang
            $gudang_cabang_request = [
                'suratjalancabang',
                'dpb',
                'dpb/*',
                'transitin',
                'reject',
                'repackcbg',
                'kirimpusat',
                'penygudangcbg',
                'sagudangcabang',
                'sagudangcabang/*',
                'laporangudangcabang'
            ];

            $gudang_cabang_laporan_permission = [
                'gc.goodstok',
                'gc.badstok',
                'gc.rekappersediaan',
                'gc.mutasidpb',
                'gc.monitoringretur',
                'gc.rekonsiliasibj'
            ];
            $gudang_cabang_permission = array_merge($gudang_cabang_laporan_permission, [
                'suratjalancabang.index',
                'dpb.index',
                'transitin.index',
                'reject.index',
                'repackcbg.index',
                'kirimpusat.index',
                'penygudangcbg.index',
                'sagudangcabang.index',
            ]);

            $users = User::select("*")
                ->whereNotNull('last_seen')
                ->orderBy('last_seen', 'DESC')
                ->get();
            $shareddata = [
                'roles_show_cabang' => $roles_show_cabang,
                'roles_show_cabang_pjp' => $roles_show_cabang_pjp,
                'start_periode' => $start_periode,
                'end_periode' => $end_periode,
                'namabulan' => $namabulan,

                'datamaster_request' => $datamaster_request,
                'datamaster_permission' => $datamaster_permission,
                'level_user' => $level_user,
                'produksi_request' => $produksi_request,
                'produksi_permission' => $produksi_permission,
                'produksi_mutasi_produk_request' => $produksi_mutasi_produk_request,
                'produksi_mutasi_produk_permission' => $produksi_mutasi_produk_permission,
                'produksi_mutasi_barang_request' => $produksi_mutasi_barang_request,
                'produksi_mutasi_barang_permission' => $produksi_mutasi_barang_permission,
                'produksi_laporan_permission' => $produksi_laporan_permission,


                'gudang_jadi_request' => $gudang_jadi_request,
                'gudang_jadi_permission' => $gudang_jadi_permission,
                'gudang_jadi_mutasi_request' => $gudang_jadi_mutasi_request,
                'gudang_jadi_mutasi_permission' => $gudang_jadi_mutasi_permission,
                'gudang_jadi_laporan_permission' => $gudang_jadi_laporan_permission,


                'gudang_bahan_request' => $gudang_bahan_request,
                'gudang_bahan_permission' => $gudang_bahan_permission,
                'gudang_bahan_mutasi_request' => $gudang_bahan_mutasi_request,
                'gudang_bahan_mutasi_permission' => $gudang_bahan_mutasi_permission,
                'gudang_bahan_laporan_permission' => $gudang_bahan_laporan_permission,

                'gudang_logistik_request' => $gudang_logistik_request,
                'gudang_logistik_permission' => $gudang_logistik_permission,
                'gudang_logistik_mutasi_request' => $gudang_logistik_mutasi_request,
                'gudang_logistik_mutasi_permission' => $gudang_logistik_mutasi_permission,
                'gudang_logistik_laporan_permission' => $gudang_logistik_laporan_permission,

                //Gudang Cabang
                'gudang_cabang_request' => $gudang_cabang_request,
                'gudang_cabang_permission' => $gudang_cabang_permission,
                'gudang_cabang_laporan_permission' => $gudang_cabang_laporan_permission,


                //Notifikasi
                'notifikasi_limitkredit' => $notifikasi_limitkredit,
                'notifikasi_ajuanfaktur' => $notifikasi_ajuanfaktur,
                'notifikasi_pengajuan_marketing' => $notifikasi_pengajuan_marketing,
                'notifikasi_target' => $notifikasi_target,
                'notifikasi_komisi' => $notifikasi_komisi,
                'notifikasi_marketing' => $notifikasi_marketing,

                'notifikasi_penilaiankaryawan' => $notifikasi_penilaiankaryawan,
                'notifikasi_pengajuan_izin' => $notifikasi_pengajuan_izin,

                'notifikasi_izinabsen' => $notifikasi_izinabsen,
                'notifikasi_izinpulang' => $notifikasi_izinpulang,
                'notifikasi_izinterlambat' => $notifikasi_izinterlambat,
                'notifikasi_izinkeluar' => $notifikasi_izinkeluar,
                'notifikasi_izinabsen' => $notifikasi_izinabsen,
                'notifikasi_izincuti' => $notifikasi_izincuti,
                'notifikasi_izinsakit' => $notifikasi_izinsakit,
                'notifikasi_lembur' => $notifikasi_lembur,
                'notifikasi_izinkoreksi' => $notifikasi_izinkoreksi,
                'notifikasi_hrd' => $notifikasi_hrd,
                'notifikasi_izindinas' => $notifikasi_izindinas,

                'notifikasiajuantransferdana' => $notifikasiajuantransferdana,

                'total_notifikasi' => $total_notifikasi,

                'notifikasi_ajuanprogramikatan' => $notifikasi_ajuanprogramikatan,
                'notifikasi_pencairanprogramikatan' => $notifikasi_pencairanprogramikatan,
                'notifikasi_ajuanprogramkumulatif' => $notifikasi_ajuanprogramkumulatif,
                'notifikasi_pencairanprogramkumulatif' => $notifikasi_pencairanprogramkumulatif,
                'notifikasi_ajuanprogramikatanenambulan' => $notifikasi_ajuanprogramikatanenambulan,
                'notifikasi_pencairanprogramikatanenambulan' => $notifikasi_pencairanprogramikatanenambulan,
                'notifikasi_ajuan_program' => $notifikasi_ajuan_program,

                'notifikasi_ticket' => $notifikasi_ticket,
                'notifikasi_update_data' => $notifikasi_update_data,

                'total_notifikasi_izin_spvpresensi' => $total_notifikasi_izin_spvpresensi,
                'notifikasi_izinabsen_presensi' => $notifikasi_izinabsen_presensi,
                'notifikasi_izinpulang_presensi' => $notifikasi_izinpulang_presensi,
                'notifikasi_izinterlambat_presensi' => $notifikasi_izinterlambat_presensi,
                'notifikasi_izinkeluar_presensi' => $notifikasi_izinkeluar_presensi,
                'notifikasi_izincuti_presensi' => $notifikasi_izincuti_presensi,
                'notifikasi_izinsakit_presensi' => $notifikasi_izinsakit_presensi,
                'notifikasi_izinkoreksi_presensi' => $notifikasi_izinkoreksi_presensi,
                'notifikasi_izindinas_presensi' => $notifikasi_izindinas_presensi,


                'users' => $users

            ];
            View::share($shareddata);
        });
    }
}

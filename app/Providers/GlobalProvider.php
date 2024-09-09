<?php

namespace App\Providers;

use App\Models\Disposisiajuanfaktur;
use App\Models\Disposisiajuanlimitkredit;
use App\Models\Disposisipenilaiankaryawan;
use App\Models\Disposisitargetkomisi;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class Globalprovider extends ServiceProvider
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
                'staff keuangan'
            ];
            $start_periode = '2018-01-01';
            $end_periode = date('Y') . '-12-31';
            $namabulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            if ($auth->check()) {
                $level_user = auth()->user()->roles->pluck('name')[0];
                $notifikasi_limitkredit = Disposisiajuanlimitkredit::where('id_penerima', auth()->user()->id)->where('status', 0)->count();
                $notifikasi_ajuanfaktur = Disposisiajuanfaktur::where('id_penerima', auth()->user()->id)->where('status', 0)->count();
                $notifikasi_target = Disposisitargetkomisi::where('id_penerima', auth()->user()->id)->where('status', 0)->count();
                $notifikasi_pengajuan_marketing = $notifikasi_limitkredit + $notifikasi_ajuanfaktur;
                $notifikasi_komisi = $notifikasi_target;
                $notifikasi_marketing = $notifikasi_pengajuan_marketing + $notifikasi_komisi;

                $notifikasi_penilaiankaryawan = Disposisipenilaiankaryawan::where('id_penerima', auth()->user()->id)->where('status', 0)->count();
                $notifikasi_hrd = $notifikasi_penilaiankaryawan;
                $total_notifikasi = $notifikasi_marketing + $notifikasi_hrd;
            } else {
                $level_user = '';
                $notifikasi_limitkredit = 0;
                $notifikasi_ajuanfaktur = 0;
                $notifikasi_pengajuan_marketing = 0;
                $notifikasi_target = 0;
                $notifikasi_komisi = 0;
                $notifikasi_marketing = 0;
                $notifikasi_penilaiankaryawan = 0;
                $notifikasi_hrd = 0;
                $total_notifikasi = 0;
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


            $shareddata = [
                'roles_show_cabang' => $roles_show_cabang,
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
                'notifikasi_hrd' => $notifikasi_hrd,

                'total_notifikasi' => $total_notifikasi

            ];
            View::share($shareddata);
        });
    }
}

<?php

use App\Http\Controllers\ActivitylogController;
use App\Http\Controllers\AjuanfakturkreditController;
use App\Http\Controllers\BackupDatabaseController;
use App\Http\Controllers\AjuanlimitkreditController;
use App\Http\Controllers\AjuanprogramikatanController;
use App\Http\Controllers\AjuanprogramikatanenambulanController;
use App\Http\Controllers\AjuanprogramkumulatifController;
use App\Http\Controllers\AjuantransferdanaController;
use App\Http\Controllers\AktifitassmmController;
use App\Http\Controllers\AngkutanController;
// use App\Http\Controllers\Api\SlipgajiController;
use App\Http\Controllers\BadstokgaController;
use App\Http\Controllers\BarangkeluargudangbahanController;
use App\Http\Controllers\BarangkeluargudanglogistikController;
use App\Http\Controllers\BarangkeluarmaintenanceController;
use App\Http\Controllers\BarangkeluarproduksiController;
use App\Http\Controllers\BarangmasukgudangbahanController;
use App\Http\Controllers\BarangmasukgudanglogistikController;
use App\Http\Controllers\BarangmasukmaintenanceController;
use App\Http\Controllers\BarangmasukproduksiController;
use App\Http\Controllers\BarangpembelianController;
use App\Http\Controllers\BarangproduksiController;
use App\Http\Controllers\BengkelController;
use App\Http\Controllers\BpbjController;
use App\Http\Controllers\BpjskesehatanController;
use App\Http\Controllers\BpjstenagakerjaController;
use App\Http\Controllers\BufferstokController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\CostratioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DpbController;
use App\Http\Controllers\DriverhelperController;
use App\Http\Controllers\FsthpController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\HargaawalhppController;
use App\Http\Controllers\HargaController;
use App\Http\Controllers\HariliburController;
use App\Http\Controllers\HppController;
use App\Http\Controllers\InsentifController;
use App\Http\Controllers\ItemservicekendaraanController;
use App\Http\Controllers\IzinabsenController;
use App\Http\Controllers\IzincutiController;
use App\Http\Controllers\IzindinasController;
use App\Http\Controllers\IzinkeluarController;
use App\Http\Controllers\IzinkoreksiController;
use App\Http\Controllers\IzinpulangController;
use App\Http\Controllers\IzinsakitController;
use App\Http\Controllers\IzinterlambatController;
use App\Http\Controllers\JadwalshiftController;
use App\Http\Controllers\JasamasakerjaController;
use App\Http\Controllers\JenisprodukController;
use App\Http\Controllers\JurnalkoreksiController;
use App\Http\Controllers\JurnalumumController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KasbonController;
use App\Http\Controllers\KaskecilController;
use App\Http\Controllers\KategoriprodukController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\KesepakatanbersamaController;
use App\Http\Controllers\KirimlhpController;
use App\Http\Controllers\KirimlpcController;
use App\Http\Controllers\KirimpusatController;
use App\Http\Controllers\KlaimkaskecilController;
use App\Http\Controllers\KontrabonangkutanController;
use App\Http\Controllers\KontrabonkeuanganController;
use App\Http\Controllers\KontrabonpembelianController;
use App\Http\Controllers\KontrakkaryawanController;
use App\Http\Controllers\KontrakkerjaController;
use App\Http\Controllers\LainnyagudangjadiController;
use App\Http\Controllers\LaporanaccountingController;
use App\Http\Controllers\LaporangeneralaffairController;
use App\Http\Controllers\LaporangudangbahanController;
use App\Http\Controllers\LaporangudangcabangController;
use App\Http\Controllers\LaporangudangjadiController;
use App\Http\Controllers\LaporangudanglogistikController;
use App\Http\Controllers\LaporanhrdController;
use App\Http\Controllers\LaporankeuanganController;
use App\Http\Controllers\LaporankeuangnaController;
use App\Http\Controllers\LaporanmaintenanceController;
use App\Http\Controllers\LaporanmarketingController;
use App\Http\Controllers\LaporanpembelianController;
use App\Http\Controllers\LaporanpenjualanController;
use App\Http\Controllers\LaporanproduksiController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\LogamtokertasController;
use App\Http\Controllers\MonitoringprogramController;
use App\Http\Controllers\MonitoringreturController;
use App\Http\Controllers\MutasibankController;
use App\Http\Controllers\MutasidpbController;
use App\Http\Controllers\MutasikendaraanController;
use App\Http\Controllers\MutasikeuanganController;
use App\Http\Controllers\OmancabangController;
use App\Http\Controllers\OmanController;
use App\Http\Controllers\OpnamegudangbahanController;
use App\Http\Controllers\OpnamegudanglogistikController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PelunasanreturController;
use App\Http\Controllers\PembayarangiroController;
use App\Http\Controllers\PembayarankasbonController;
use App\Http\Controllers\PembayaranpenjualanController;
use App\Http\Controllers\PembayaranpiutangkaryawanController;
use App\Http\Controllers\PembayaranpjpController;
use App\Http\Controllers\PembayarantransferController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PencairanprogramController;
use App\Http\Controllers\PencairanprogramenambulanController;
use App\Http\Controllers\PencairanprogramikatanController;
use App\Http\Controllers\PenilaiankaryawanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenyesuaiangudangcabangController;
use App\Http\Controllers\PenyesuaianupahController;
use App\Http\Controllers\PermintaankirimanController;
use App\Http\Controllers\PermintaanproduksiController;
use App\Http\Controllers\Permission_groupController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PiutangkaryawanController;
use App\Http\Controllers\PjpController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatiodriverhelperController;
use App\Http\Controllers\RegionalController;
use App\Http\Controllers\RejectController;
use App\Http\Controllers\RejectgudangjadiController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\RepackgudangcabangController;
use App\Http\Controllers\RepackgudangjadiController;
use App\Http\Controllers\ResignController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaldoawalbarangproduksiController;
use App\Http\Controllers\SaldoawalbukubesarController;
use App\Http\Controllers\SaldoawalgudangbahanController;
use App\Http\Controllers\SaldoawalgudangcabangController;
use App\Http\Controllers\SaldoawalgudangjadiController;
use App\Http\Controllers\SaldoawalgudanglogistikController;
use App\Http\Controllers\SaldoawalhargagudangbahanController;
use App\Http\Controllers\SaldoawalkasbesarController;
use App\Http\Controllers\SaldoawalkaskecilController;
use App\Http\Controllers\SaldoawalledgerController;
use App\Http\Controllers\SaldoawalmutasikeuanganController;
use App\Http\Controllers\SaldoawalmutasiproduksiController;
use App\Http\Controllers\SaldokasbesarkeuanganController;
use App\Http\Controllers\SalesmanController;
use App\Http\Controllers\ServicekendaraanController;
use App\Http\Controllers\SetorangiroController;
use App\Http\Controllers\SetoranpenjualanController;
use App\Http\Controllers\SetoranpusatController;
use App\Http\Controllers\SetorantransferController;
use App\Http\Controllers\SettingkomisidriverhelperController;
use App\Http\Controllers\SfaControler;

use App\Http\Controllers\SlipgajiController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SuratjalanangkutanController;
use App\Http\Controllers\SuratjalanController;
use App\Http\Controllers\SuratperingatanController;
use App\Http\Controllers\TargetkomisiController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketupdateController;
use App\Http\Controllers\TransitinController;
use App\Http\Controllers\TujuanangkutanController;
use App\Http\Controllers\TutuplaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitpelangganController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\WorksheetomController;
use App\Models\Barangkeluargudangbahan;
use App\Models\Barangproduksi;
use App\Models\Kontrabonpembelian;
use App\Models\Permission_group;
use App\Models\Saldoawalmutasikeungan;
use App\Models\Servicekendaraan;
use App\Models\Settingkomisidriverhelper;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::post('/screen-resolution', [App\Http\Controllers\ScreenController::class, 'store'])->name('screen.resolution');

Route::middleware('auth')->group(function () {




    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //Dashboard
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/dashboard/produksi', 'produksi')->name('dashboard.produksi')->can('dashboard.produksi');
        Route::get('/dashboard/generalaffair', 'generalaffair')->name('dashboard.generalaffair')->can('dashboard.generalaffair');
        Route::get('/dashboard/hrd', 'hrd')->name('dashboard.hrd')->can('dashboard.hrd');
        Route::get('/dashboard/gudang', 'gudang')->name('dashboard.gudang')->can('dashboard.gudang');
        Route::get('/dashboard/marketing', 'marketing')->name('dashboard.marketing')->can('dashboard.marketing');
        Route::get('/dashboard/owner', 'dashboardowner')->name('dashboard.owner');


        //Rekap Penjualan
        Route::post('/dashboard/rekappenjualan', 'rekappenjualan')->name('dashboard.rekappenjualan');
        Route::post('/dashboard/rekapkendaraan', 'rekapkendaraan')->name('dashboard.rekapkendaraan');
        Route::post('/dashboard/rekapdppp', 'rekapdppp')->name('dashboard.rekapdppp');
        Route::post('/dashboard/rekapaup', 'rekapaup')->name('dashboard.rekapaup');
        Route::post('/dashboard/rekapaupcabang ', 'rekapaupcabang')->name('dashboard.rekapaupcabang');

        //Gudang Rekap DPB
        Route::get('/dashboard/rekappersediaan ', 'rekappersediaan')->name('dashboard.rekappersediaan');
        Route::get('/dashboard/rekappersediaancabang ', 'rekappersediaancabang')->name('dashboard.rekappersediaancabang');


        //Salesman
        Route::post('/dashboard/getcheckinsalesman', 'getcheckinsalesman')->name('penjualan.getcheckinsalesman');
        Route::post('/dashboard/getdpbsalesman', 'getdpbsalesman')->name('penjualan.getdpbsalesman');
    });

    //Setings
    //Role
    Route::controller(RoleController::class)->group(function () {
        Route::get('/roles', 'index')->name('roles.index');
        Route::get('/roles/create', 'create')->name('roles.create');
        Route::post('/roles', 'store')->name('roles.store');
        Route::get('/roles/{id}/edit', 'edit')->name('roles.edit');
        Route::put('/roles/{id}/update', 'update')->name('roles.update');
        Route::delete('/roles/{id}/delete', 'destroy')->name('roles.delete');
        Route::get('/roles/{id}/createrolepermission', 'createrolepermission')->name('roles.createrolepermission');
        Route::post('/roles/{id}/storerolepermission', 'storerolepermission')->name('roles.storerolepermission');
    });


    Route::controller(Permission_groupController::class)->group(function () {
        Route::get('/permissiongroups', 'index')->name('permissiongroups.index');
        Route::get('/permissiongroups/create', 'create')->name('permissiongroups.create');
        Route::post('/permissiongroups', 'store')->name('permissiongroups.store');
        Route::get('/permissiongroups/{id}/edit', 'edit')->name('permissiongroups.edit');
        Route::put('/permissiongroups/{id}/update', 'update')->name('permissiongroups.update');
        Route::delete('/permissiongroups/{id}/delete', 'destroy')->name('permissiongroups.delete');
    });


    Route::controller(PermissionController::class)->group(function () {
        Route::get('/permissions', 'index')->name('permissions.index');
        Route::get('/permissions/create', 'create')->name('permissions.create');
        Route::post('/permissions', 'store')->name('permissions.store');
        Route::get('/permissions/{id}/edit', 'edit')->name('permissions.edit');
        Route::put('/permissions/{id}/update', 'update')->name('permissions.update');
        Route::delete('/permissions/{id}/delete', 'destroy')->name('permissions.delete');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('users.index');
        Route::get('/users/create', 'create')->name('users.create');
        Route::post('/users', 'store')->name('users.store');
        Route::get('/users/{id}/edit', 'edit')->name('users.edit');
        Route::get('/users/ubahpassword', 'ubahpassword')->name('users.ubahpassword');
        Route::put('/users/updateprofile', 'updateprofile')->name('users.updateprofile');
        Route::put('/users/{id}/update', 'update')->name('users.update');
        Route::delete('/users/{id}/delete', 'destroy')->name('users.delete');

        Route::get('/users/{id}/createuserpermission', 'createuserpermission')->name('users.createuserpermission');
        Route::post('/users/{id}/storeuserpermission', 'storeuserpermission')->name('users.storeuserpermission');
    });



    Route::controller(RegionalController::class)->group(function () {
        Route::get('/regional', 'index')->name('regional.index')->can('regional.index');
        Route::get('/regional/create', 'create')->name('regional.create')->can('regional.create');
        Route::post('/regional', 'store')->name('regional.store')->can('regional.store');
        Route::get('/regional/{kode_regional}/edit', 'edit')->name('regional.edit')->can('regional.edit');
        Route::put('/regional/{kode_regional}', 'update')->name('regional.update')->can('regional.update');
        Route::delete('/regional/{kode_regional}', 'destroy')->name('regional.delete')->can('regional.delete');
    });

    //DATA MASTER
    Route::controller(CabangController::class)->group(function () {
        Route::get('/cabang', 'index')->name('cabang.index')->can('cabang.index');
        Route::get('/cabang/create', 'create')->name('cabang.create')->can('cabang.create');
        Route::post('/cabang', 'store')->name('cabang.store')->can('cabang.store');
        Route::get('/cabang/{kode_cabang}/edit', 'edit')->name('cabang.edit')->can('cabang.edit');
        Route::put('/cabang/{kode_cabang}', 'update')->name('cabang.update')->can('cabang.update');
        Route::delete('/cabang/{kode_cabang}', 'destroy')->name('cabang.delete')->can('cabang.delete');
    });

    Route::controller(SalesmanController::class)->group(function () {
        Route::get('/salesman', 'index')->name('salesman.index')->can('salesman.index');
        Route::get('/salesman/create', 'create')->name('salesman.create')->can('salesman.create');
        Route::post('/salesman', 'store')->name('salesman.store')->can('salesman.store');
        Route::get('/salesman/{kode_salesman}/edit', 'edit')->name('salesman.edit')->can('salesman.edit');
        Route::put('/salesman/{kode_salesman}', 'update')->name('salesman.update')->can('salesman.update');
        Route::delete('/salesman/{kode_salesman}', 'destroy')->name('salesman.delete')->can('salesman.delete');

        //GET DATA FROM AJAX
        Route::post('/salesman/getsalesmanbycabang', 'getsalesmanbycabang');
    });

    Route::controller(KategoriprodukController::class)->group(function () {
        Route::get('/kategoriproduk', 'index')->name('kategoriproduk.index')->can('kategoriproduk.index');
        Route::get('/kategoriproduk/create', 'create')->name('kategoriproduk.create')->can('kategoriproduk.create');
        Route::post('/kategoriproduk', 'store')->name('kategoriproduk.store')->can('kategoriproduk.store');
        Route::get('/kategoriproduk/{kode_kategori_produk}/edit', 'edit')->name('kategoriproduk.edit')->can('kategoriproduk.edit');
        Route::put('/kategoriproduk/{kode_kategori_produk}', 'update')->name('kategoriproduk.update')->can('kategoriproduk.update');
        Route::delete('/kategoriproduk/{kode_kategori_produk}', 'destroy')->name('kategoriproduk.delete')->can('kategoriproduk.delete');
    });

    Route::controller(JenisprodukController::class)->group(function () {
        Route::get('/jenisproduk', 'index')->name('jenisproduk.index')->can('jenisproduk.index');
        Route::get('/jenisproduk/create', 'create')->name('jenisproduk.create')->can('jenisproduk.create');
        Route::post('/jenisproduk', 'store')->name('jenisproduk.store')->can('jenisproduk.store');
        Route::get('/jenisproduk/{kode_jenis_produk}/edit', 'edit')->name('jenisproduk.edit')->can('jenisproduk.edit');
        Route::put('/jenisproduk/{kode_jenis_produk}', 'update')->name('jenisproduk.update')->can('jenisproduk.update');
        Route::delete('/jenisproduk/{kode_jenis_produk}', 'destroy')->name('jenisproduk.delete')->can('jenisproduk.delete');
    });
    Route::controller(ProdukController::class)->group(function () {
        Route::get('/produk', 'index')->name('produk.index')->can('produk.index');
        Route::get('/produk/create', 'create')->name('produk.create')->can('produk.create');
        Route::post('/produk', 'store')->name('produk.store')->can('produk.store');
        Route::get('/produk/{kode_produk}/edit', 'edit')->name('produk.edit')->can('produk.edit');
        Route::put('/produk/{kode_produk}', 'update')->name('produk.update')->can('produk.update');
        Route::delete('/produk/{kode_produk}', 'destroy')->name('produk.delete')->can('produk.delete');
    });

    Route::controller(HargaController::class)->group(function () {
        Route::get('/harga', 'index')->name('harga.index')->can('harga.index');
        Route::get('/harga/create', 'create')->name('harga.create')->can('harga.create');
        Route::post('/harga', 'store')->name('harga.store')->can('harga.store');
        Route::get('/harga/{kode_harga}/edit', 'edit')->name('harga.edit')->can('harga.edit');
        Route::put('/harga/{kode_harga}', 'update')->name('harga.update')->can('harga.update');
        Route::delete('/harga/{kode_harga}', 'destroy')->name('harga.delete')->can('harga.delete');

        //AjaxRequest
        Route::get('/harga/{kode_pelanggan}/gethargabypelanggan', 'gethargabypelanggan')->name('harga.gethargabypelanggan');
        Route::get('/harga/{kode_pelanggan}/gethargareturbypelanggan', 'gethargareturbypelanggan')->name('harga.gethargareturbypelanggan');
    });

    Route::controller(PelangganController::class)->group(function () {
        Route::get('/pelanggan', 'index')->name('pelanggan.index')->can('pelanggan.index');
        Route::get('/pelanggan/create', 'create')->name('pelanggan.create')->can('pelanggan.create');
        Route::post('/pelanggan', 'store')->name('pelanggan.store')->can('pelanggan.store');
        Route::get('/pelanggan/{kode_pelanggan}/edit', 'edit')->name('pelanggan.edit')->can('pelanggan.edit');
        Route::put('/pelanggan/{kode_pelanggan}', 'update')->name('pelanggan.update')->can('pelanggan.update');
        Route::delete('/pelanggan/{kode_pelanggan}', 'destroy')->name('pelanggan.delete')->can('pelanggan.delete');
        Route::get('/pelanggan/{kode_pelanggan}/show', 'show')->name('pelanggan.show')->can('pelanggan.show');
        Route::get('/pelanggan/export', 'export')->name('pelanggan.export')->can('pelanggan.show');

        //AJAX REQUEST
        Route::get('/pelanggan/{kode_pelanggan}/getPelanggan', 'getPelanggan')->name('pelanggan.getPelanggan');
        Route::post('/pelanggan/getpelangganbysalesman', 'getpelangganbysalesman')->name('pelanggan.getpelangganbysalesman');
        Route::get('/pelanggan/cekfotopelanggan', 'cekfotopelanggan')->name('pelanggan.cekfotopelanggan');
        Route::get('/pelanggan/cekfoto', 'cekfoto')->name('pelanggan.cekfoto');
        Route::get('/pelanggan/{kode_pelanggan}/getPiutangpelanggan', 'getPiutangpelanggan')->name('pelanggan.getPiutangpelanggan');
        Route::get('/pelanggan/{kode_pelanggan}/getFakturkredit', 'getFakturkredit')->name('pelanggan.getFakturkredit');
        Route::get('/pelanggan/{kode_pelanggan}/getlistfakturkredit', 'getlistFakturkredit')->name('pelanggan.getlistFakturkredit');
        Route::get('/pelanggan/{kode_pelanggan}/getlistfakturkreditoption', 'getlistfakturkreditoption')->name('pelanggan.getlistfakturkreditoption');
        Route::get('/pelanggan/getpelangganjson', 'getPelangganjson')->name('pelanggan.getpelangganjson');
        Route::get('/pelanggan/{kode_cabang}/getpelanggancabangjson', 'getPelanggancabangjson')->name('pelanggan.getpelanggancabangjson');
        Route::get('/pelanggan/{no_pengajuan}/getpelanggangagalprogramikatan', 'getpelanggangagalprogramikatan')->name('pelanggan.getpelanggangagalprogramikatan');


        Route::get('/pelanggan/{kode_pelanggan}/{kode_program}/getavgpelanggan', 'getAvgpelanggan')->name('pelanggan.getAvgpelanggan');
        Route::get('/pelanggan/{kode_pelanggan}/{kode_program}/gethistoripelangganprogram', 'gethistoripelangganprogram')->name('pelanggan.gethistoripelangganprogram');
        Route::get('/pelanggan/{kode_pelanggan}/{kode_program}/{no_pengajuan}/gettargetpelanggan', 'gettargetpelanggan')->name('pelanggan.gettargetpelanggan');


        Route::get('/pelanggan/nonaktif', 'nonaktif')->name('pelanggan.nonaktif');
        Route::post('/pelanggan/updatenonaktifpelanggan', 'updatenonaktifpelanggan')->name('pelanggan.updatenonaktifpelanggan');

        // Route::get('/sfa/pelanggan', 'index')->name('sfa.pelanggan');
    });

    Route::controller(WilayahController::class)->group(function () {
        Route::get('/wilayah', 'index')->name('wilayah.index')->can('wilayah.index');
        Route::get('/wilayah/create', 'create')->name('wilayah.create')->can('wilayah.create');
        Route::post('/wilayah', 'store')->name('wilayah.store')->can('wilayah.store');
        Route::get('/wilayah/{kode_wilayah}/edit', 'edit')->name('wilayah.edit')->can('wilayah.edit');
        Route::put('/wilayah/{kode_wilayah}', 'update')->name('wilayah.update')->can('wilayah.update');
        Route::delete('/wilayah/{kode_wilayah}', 'destroy')->name('wilayah.delete')->can('wilayah.delete');
        Route::get('/wilayah/{kode_wilayah}/show', 'show')->name('wilayah.show')->can('wilayah.show');

        //GET DATA FROM AJAX
        Route::post('/wilayah/getwilayahbycabang', 'getwilayahbycabang');
    });

    Route::controller(DriverhelperController::class)->group(function () {
        Route::get('/driverhelper', 'index')->name('driverhelper.index')->can('driverhelper.index');
        Route::get('/driverhelper/create', 'create')->name('driverhelper.create')->can('driverhelper.create');
        Route::post('/driverhelper', 'store')->name('driverhelper.store')->can('driverhelper.store');
        Route::get('/driverhelper/{kode_driverhelper}/edit', 'edit')->name('driverhelper.edit')->can('driverhelper.edit');
        Route::put('/driverhelper/{kode_driverhelper}', 'update')->name('driverhelper.update')->can('driverhelper.update');
        Route::delete('/driverhelper/{kode_driverhelper}', 'destroy')->name('driverhelper.delete')->can('driverhelper.delete');
        Route::get('/driverhelper/{kode_driverhelper}/show', 'show')->name('driverhelper.show')->can('driverhelper.show');

        //GET DATA FROM AJAX
        Route::post('/driverhelper/getdriverhelperbycabang', 'getdriverhelperbycabang');
    });


    Route::controller(KendaraanController::class)->group(function () {
        Route::get('/kendaraan', 'index')->name('kendaraan.index')->can('kendaraan.index');
        Route::get('/kendaraan/create', 'create')->name('kendaraan.create')->can('kendaraan.create');
        Route::post('/kendaraan', 'store')->name('kendaraan.store')->can('kendaraan.store');
        Route::get('/kendaraan/{kode_kendaraan}/edit', 'edit')->name('kendaraan.edit')->can('kendaraan.edit');
        Route::put('/kendaraan/{kode_kendaraan}', 'update')->name('kendaraan.update')->can('kendaraan.update');
        Route::delete('/kendaraan/{kode_kendaraan}', 'destroy')->name('kendaraan.delete')->can('kendaraan.delete');
        Route::get('/kendaraan/{kode_kendaraan}/show', 'show')->name('kendaraan.show')->can('kendaraan.show');

        //GET DATA FROM AJAX
        Route::post('/kendaraan/getkendaraanbycabang', 'getkendaraanbycabang');
        Route::post('/kendaraan/getkendaraandpbbycabang', 'getkendaraandpbbycabang');
    });

    Route::controller(SupplierController::class)->group(function () {
        Route::get('/supplier', 'index')->name('supplier.index')->can('supplier.index');
        Route::get('/supplier/create', 'create')->name('supplier.create')->can('supplier.create');
        Route::post('/supplier', 'store')->name('supplier.store')->can('supplier.store');
        Route::get('/supplier/{kode_supplier}/edit', 'edit')->name('supplier.edit')->can('supplier.edit');
        Route::put('/supplier/{kode_supplier}', 'update')->name('supplier.update')->can('supplier.update');
        Route::delete('/supplier/{kode_supplier}', 'destroy')->name('supplier.delete')->can('supplier.delete');
        Route::get('/supplier/{kode_supplier}/show', 'show')->name('supplier.show')->can('supplier.show');
    });

    Route::controller(BarangpembelianController::class)->group(function () {
        Route::get('/barangpembelian', 'index')->name('barangpembelian.index')->can('barangpembelian.index');
        Route::get('/barangpembelian/create', 'create')->name('barangpembelian.create')->can('barangpembelian.create');
        Route::post('/barangpembelian', 'store')->name('barangpembelian.store')->can('barangpembelian.store');
        Route::get('/barangpembelian/{kode_barang}/edit', 'edit')->name('barangpembelian.edit')->can('barangpembelian.edit');
        Route::put('/barangpembelian/{kode_barang}', 'update')->name('barangpembelian.update')->can('barangpembelian.update');
        Route::delete('/barangpembelian/{kode_barang}', 'destroy')->name('barangpembelian.delete')->can('barangpembelian.delete');
        Route::get('/barangpembelian/{kode_barang}/show', 'show')->name('barangpembelian.show')->can('barangpembelian.show');

        //GET DATA FROM AJAX
        Route::post('/barangpembelian/getbarangbykategori', 'getbarangbykategori');
        Route::get('barangpembelian/{kode_group}/getbarangjson', 'getbarangjson')->name('barangpembelian.getbarangjson');
    });

    Route::controller(KaryawanController::class)->group(function () {
        Route::get('/karyawan', 'index')->name('karyawan.index')->can('karyawan.index');
        Route::get('/karyawan/create', 'create')->name('karyawan.create')->can('karyawan.create');
        Route::post('/karyawan', 'store')->name('karyawan.store')->can('karyawan.store');
        Route::get('/karyawan/{nik}/edit', 'edit')->name('karyawan.edit')->can('karyawan.edit');
        Route::put('/karyawan/{nik}', 'update')->name('karyawan.update')->can('karyawan.update');
        Route::delete('/karyawan/{nik}', 'destroy')->name('karyawan.delete')->can('karyawan.delete');
        Route::get('/karyawan/{nik}/show', 'show')->name('karyawan.show')->can('karyawan.show');
        Route::get('/karyawan/{nik}/unlocklocation', 'unlocklocation')->name('karyawan.unlocklocation')->can('karyawan.unlocklocation');
        Route::get('/karyawan/{nik}/dokumen', 'dokumen')->name('karyawan.dokumen')->can('karyawan.dokumen');

        Route::get('/karyawan/{nik}/getkaryawan', 'getkaryawan')->name('karyawan.getkaryawan');
        Route::get('/karyawan/getkaryawanjson', 'getkaryawanjson')->name('karyawan.getkaryawanjson');
        Route::get('/karyawan/getkaryawanpiutangkaryawanjson', 'getkaryawanpiutangkaryawanjson')->name('karyawan.getkaryawanpiutangkaryawanjson');
    });

    Route::controller(RekeningController::class)->group(function () {
        Route::get('/rekening', 'index')->name('rekening.index')->can('rekening.index');
        Route::get('/rekening/{nik}/edit', 'edit')->name('rekening.edit')->can('rekening.edit');
        Route::put('/rekening/{nik}', 'update')->name('rekening.update')->can('rekening.update');
    });

    Route::controller(GajiController::class)->group(function () {
        Route::get('/gaji', 'index')->name('gaji.index')->can('gaji.index');
        Route::get('/gaji/create', 'create')->name('gaji.create')->can('gaji.create');
        Route::post('/gaji', 'store')->name('gaji.store')->can('gaji.store');
        Route::get('/gaji/{kode_gaji}/edit', 'edit')->name('gaji.edit')->can('gaji.edit');
        Route::put('/gaji/{kode_gaji}', 'update')->name('gaji.update')->can('gaji.update');
        Route::delete('/gaji/{kode_gaji}', 'destroy')->name('gaji.delete')->can('gaji.delete');
        Route::get('/gaji/{kode_gaji}/show', 'show')->name('gaji.show')->can('gaji.show');
    });

    Route::controller(InsentifController::class)->group(function () {
        Route::get('/insentif', 'index')->name('insentif.index')->can('insentif.index');
        Route::get('/insentif/create', 'create')->name('insentif.create')->can('insentif.create');
        Route::post('/insentif', 'store')->name('insentif.store')->can('insentif.store');
        Route::get('/insentif/{kode_insentif}/edit', 'edit')->name('insentif.edit')->can('insentif.edit');
        Route::put('/insentif/{kode_insentif}', 'update')->name('insentif.update')->can('insentif.update');
        Route::delete('/insentif/{kode_insentif}', 'destroy')->name('insentif.delete')->can('insentif.delete');
        Route::get('/insentif/{kode_insentif}/show', 'show')->name('insentif.show')->can('insentif.show');
    });


    Route::controller(BpjskesehatanController::class)->group(function () {
        Route::get('/bpjskesehatan', 'index')->name('bpjskesehatan.index')->can('bpjskesehatan.index');
        Route::get('/bpjskesehatan/create', 'create')->name('bpjskesehatan.create')->can('bpjskesehatan.create');
        Route::post('/bpjskesehatan', 'store')->name('bpjskesehatan.store')->can('bpjskesehatan.store');
        Route::get('/bpjskesehatan/{kode_bpjs_kesehatan}/edit', 'edit')->name('bpjskesehatan.edit')->can('bpjskesehatan.edit');
        Route::put('/bpjskesehatan/{kode_bpjs_kesehatan}', 'update')->name('bpjskesehatan.update')->can('bpjskesehatan.update');
        Route::delete('/bpjskesehatan/{kode_bpjs_kesehatan}', 'destroy')->name('bpjskesehatan.delete')->can('bpjskesehatan.delete');
        Route::get('/bpjskesehatan/{kode_bpjs_kesehatan}/show', 'show')->name('bpjskesehatan.show')->can('bpjskesehatan.show');
    });


    Route::controller(BpjstenagakerjaController::class)->group(function () {
        Route::get('/bpjstenagakerja', 'index')->name('bpjstenagakerja.index')->can('bpjstenagakerja.index');
        Route::get('/bpjstenagakerja/create', 'create')->name('bpjstenagakerja.create')->can('bpjstenagakerja.create');
        Route::post('/bpjstenagakerja', 'store')->name('bpjstenagakerja.store')->can('bpjstenagakerja.store');
        Route::get('/bpjstenagakerja/{kode_bpjs_tenagakerja}/edit', 'edit')->name('bpjstenagakerja.edit')->can('bpjstenagakerja.edit');
        Route::put('/bpjstenagakerja/{kode_bpjs_tenagakerja}', 'update')->name('bpjstenagakerja.update')->can('bpjstenagakerja.update');
        Route::delete('/bpjstenagakerja/{kode_bpjs_tenagakerja}', 'destroy')->name('bpjstenagakerja.delete')->can('bpjstenagakerja.delete');
        Route::get('/bpjstenagakerja/{kode_bpjs_tenagakerja}/show', 'show')->name('bpjstenagakerja.show')->can('bpjstenagakerja.show');
    });

    Route::controller(BufferstokController::class)->group(function () {
        Route::get('/bufferstok', 'index')->name('bufferstok.index')->can('bufferstok.index');
        Route::put('/bufferstok', 'update')->name('bufferstok.update')->can('bufferstok.update');

        //Ajax Request
        Route::get('/bufferstok/{kode_cabang}/getbufferstok', 'getbufferstok');
    });

    Route::controller(BarangproduksiController::class)->group(function () {
        Route::get('/barangproduksi', 'index')->name('barangproduksi.index')->can('barangproduksi.index');
        Route::get('/barangproduksi/create', 'create')->name('barangproduksi.create')->can('barangproduksi.create');
        Route::post('/barangproduksi', 'store')->name('barangproduksi.store')->can('barangproduksi.store');
        Route::get('/barangproduksi/{kode_barang_produksi}/edit', 'edit')->name('barangproduksi.edit')->can('barangproduksi.edit');
        Route::put('/barangproduksi/{kode_barang_produksi}', 'update')->name('barangproduksi.update')->can('barangproduksi.update');
        Route::delete('/barangproduksi/{kode_barang_produksi}', 'destroy')->name('barangproduksi.delete')->can('barangproduksi.delete');
    });
    //Produksi
    Route::controller(BpbjController::class)->group(function () {
        Route::get('/bpbj', 'index')->name('bpbj.index')->can('bpbj.index');
        Route::get('/bpbj/create', 'create')->name('bpbj.create')->can('bpbj.create');
        Route::post('/bpbj', 'store')->name('bpbj.store')->can('bpbj.store');
        Route::delete('/bpbj/{no_mutasi}', 'destroy')->name('bpbj.delete')->can('bpbj.delete');
        Route::get('/bpbj/{no_mutasi}/show', 'show')->name('bpbj.show')->can('bpbj.show');

        //Ajax Request
        Route::post('/bpbj/storedetailtemp', 'storedetailtemp')->name('bpbj.storedetailtemp');
        Route::get('/bpbj/{kode_produk}/getdetailtemp', 'getdetailtemp')->name('bpbj.getdetailtemp');
        Route::post('/bpbj/generatenobpbj', 'generatenobpbj')->name('bpbj.generatenobpbj');
        Route::post('/bpbj/deletetemp', 'deletetemp')->name('bpbj.deletetemp');
        Route::post('/bpbj/cekdetailtemp', 'cekdetailtemp')->name('bpbj.cekdetailtemp');
        Route::post('/bpbj/getrekaphasilproduksi', 'getrekaphasilproduksi')->name('bpbj.getrekaphasilproduksi');
        Route::post('/bpbj/getgrafikhasilproduksi', 'getgrafikhasilproduksi')->name('bpbj.getgrafikhasilproduksi');
    });

    Route::controller(FsthpController::class)->group(function () {
        Route::get('/fsthp', 'index')->name('fsthp.index')->can('fsthp.index');
        Route::get('/fsthpgudang', 'index_gudang')->name('fsthpgudang.index')->can('fsthpgudang.index');
        Route::get('/fsthp/create', 'create')->name('fsthp.create')->can('fsthp.create');
        Route::post('/fsthp', 'store')->name('fsthp.store')->can('fsthp.store');
        Route::delete('/fsthp/{no_mutasi}', 'destroy')->name('fsthp.delete')->can('fsthp.delete');
        Route::get('/fsthp/{no_mutasi}/show', 'show')->name('fsthp.show')->can('fsthp.show');
        Route::get('/fsthp/{no_mutasi}/approve', 'approve')->name('fsthp.approve')->can('fsthp.approve');
        Route::delete('/fsthp/{no_mutasi}/cancel', 'cancel')->name('fsthp.cancel')->can('fsthp.approve');

        //Ajax Request
        Route::post('/fsthp/storedetailtemp', 'storedetailtemp')->name('fsthp.storedetailtemp');
        Route::get('/fsthp/{kode_produk}/getdetailtemp', 'getdetailtemp')->name('fsthp.getdetailtemp');
        Route::post('/fsthp/generatenofsthp', 'generatenofsthp')->name('fsthp.generatenofsthp');
        Route::post('/fsthp/deletetemp', 'deletetemp')->name('fsthp.deletetemp');
        Route::post('/fsthp/cekdetailtemp', 'cekdetailtemp')->name('fsthp.cekdetailtemp');
    });


    Route::controller(SaldoawalmutasiproduksiController::class)->group(function () {
        Route::get('/samutasiproduksi', 'index')->name('samutasiproduksi.index')->can('samutasiproduksi.index');
        Route::get('/samutasiproduksi/create', 'create')->name('samutasiproduksi.create')->can('samutasiproduksi.create');
        Route::post('/samutasiproduksi', 'store')->name('samutasiproduksi.store')->can('samutasiproduksi.store');
        Route::delete('/samutasiproduksi/{kode_saldo_awal}', 'destroy')->name('samutasiproduksi.delete')->can('samutasiproduksi.delete');
        Route::get('/samutasiproduksi/{kode_saldo_awal}/show', 'show')->name('samutasiproduksi.show')->can('samutasiproduksi.show');
        //AJAX REQUEST
        Route::post('/samutasiproduksi/getdetailsaldo', 'getdetailsaldo')->name('samutasiproduksi.getdetailsaldo');
    });

    Route::controller(BarangmasukproduksiController::class)->group(function () {
        Route::get('/barangmasukproduksi', 'index')->name('barangmasukproduksi.index')->can('barangmasukproduksi.index');
        Route::get('/barangmasukproduksi/create', 'create')->name('barangmasukproduksi.create')->can('barangmasukproduksi.create');
        Route::get('/barangmasukproduksi/{no_bukti}/edit', 'edit')->name('barangmasukproduksi.edit')->can('barangmasukproduksi.edit');
        Route::post('/barangmasukproduksi/{no_bukti}/update', 'update')->name('barangmasukproduksi.update')->can('barangmasukproduksi.update');
        Route::post('/barangmasukproduksi', 'store')->name('barangmasukproduksi.store')->can('barangmasukproduksi.store');
        Route::delete('/barangmasukproduksi/{no_bukti}', 'destroy')->name('barangmasukproduksi.delete')->can('barangmasukproduksi.delete');
        Route::get('/barangmasukproduksi/{no_bukti}/show', 'show')->name('barangmasukproduksi.show')->can('barangmasukproduksi.show');

        //AJAX REQUEST
        Route::post('/barangmasukproduksi/storedetailtemp', 'storedetailtemp')->name('barangmasukproduksi.storedetailtemp');
        Route::get('/barangmasukproduksi/{kode_asal_barang}/getdetailtemp', 'getdetailtemp')->name('barangmasukproduksi.getdetailtemp');
        Route::post('/barangmasukproduksi/deletetemp', 'deletetemp')->name('barangmasukproduksi.deletetemp');
        Route::post('/barangmasukproduksi/cekdetailtemp', 'cekdetailtemp')->name('barangmasukproduksi.cekdetailtemp');
        Route::post('/barangmasukproduksi/getbarangbyasalbarang', 'getbarangbyasalbarang')->name('barangmasukproduksi.getbarangbyasalbarang');

        //EDIT
        Route::post('/barangmasukproduksi/storedetailedit', 'storedetailedit')->name('barangmasukproduksi.storedetailedit');
        Route::get('/barangmasukproduksi/{no_bukti}/getdetailedit', 'getdetailedit')->name('barangmasukproduksi.getdetailedit');
        Route::get('/barangmasukproduksi/{id}/editbarang', 'editbarang')->name('barangmasukproduksi.editbarang');
        Route::post('/barangmasukproduksi/cekdetailedit', 'cekdetailedit')->name('barangmasukproduksi.cekdetailedit');
        Route::post('/barangmasukproduksi/updatebarang', 'updatebarang')->name('barangmasukproduksi.updatebarang');
        Route::post('/barangmasukproduksi/deleteedit', 'deleteedit')->name('barangmasukproduksi.deleteedit');
    });


    Route::controller(BarangkeluarproduksiController::class)->group(function () {
        Route::get('/barangkeluarproduksi', 'index')->name('barangkeluarproduksi.index')->can('barangkeluarproduksi.index');
        Route::get('/barangkeluarproduksi/create', 'create')->name('barangkeluarproduksi.create')->can('barangkeluarproduksi.create');
        Route::get('/barangkeluarproduksi/{no_bukti}/edit', 'edit')->name('barangkeluarproduksi.edit')->can('barangkeluarproduksi.edit');
        Route::post('/barangkeluarproduksi/{no_bukti}/update', 'update')->name('barangkeluarproduksi.update')->can('barangkeluarproduksi.update');
        Route::post('/barangkeluarproduksi', 'store')->name('barangkeluarproduksi.store')->can('barangkeluarproduksi.store');
        Route::delete('/barangkeluarproduksi/{no_bukti}', 'destroy')->name('barangkeluarproduksi.delete')->can('barangkeluarproduksi.delete');
        Route::get('/barangkeluarproduksi/{no_bukti}/show', 'show')->name('barangkeluarproduksi.show')->can('barangkeluarproduksi.show');

        //AJAX REQUEST
        Route::post('/barangkeluarproduksi/storedetailtemp', 'storedetailtemp')->name('barangkeluarproduksi.storedetailtemp');
        Route::get('/barangkeluarproduksi/getdetailtemp', 'getdetailtemp')->name('barangkeluarproduksi.getdetailtemp');
        Route::post('/barangkeluarproduksi/deletetemp', 'deletetemp')->name('barangkeluarproduksi.deletetemp');
        Route::post('/barangkeluarproduksi/cekdetailtemp', 'cekdetailtemp')->name('barangkeluarproduksi.cekdetailtemp');


        //EDIT
        Route::post('/barangkeluarproduksi/storedetailedit', 'storedetailedit')->name('barangkeluarproduksi.storedetailedit');
        Route::get('/barangkeluarproduksi/{no_bukti}/getdetailedit', 'getdetailedit')->name('barangkeluarproduksi.getdetailedit');
        Route::get('/barangkeluarproduksi/{id}/editbarang', 'editbarang')->name('barangkeluarproduksi.editbarang');
        Route::post('/barangkeluarproduksi/cekdetailedit', 'cekdetailedit')->name('barangkeluarproduksi.cekdetailedit');
        Route::post('/barangkeluarproduksi/updatebarang', 'updatebarang')->name('barangkeluarproduksi.updatebarang');
        Route::post('/barangkeluarproduksi/deleteedit', 'deleteedit')->name('barangkeluarproduksi.deleteedit');
    });



    Route::controller(SaldoawalbarangproduksiController::class)->group(function () {
        Route::get('/sabarangproduksi', 'index')->name('sabarangproduksi.index')->can('sabarangproduksi.index');
        Route::get('/sabarangproduksi/create', 'create')->name('sabarangproduksi.create')->can('sabarangproduksi.create');
        Route::post('/sabarangproduksi', 'store')->name('sabarangproduksi.store')->can('sabarangproduksi.store');
        Route::delete('/sabarangproduksi/{kode_saldo_awal}', 'destroy')->name('sabarangproduksi.delete')->can('sabarangproduksi.delete');
        Route::get('/sabarangproduksi/{kode_saldo_awal}/show', 'show')->name('sabarangproduksi.show')->can('sabarangproduksi.show');
        //AJAX REQUEST
        Route::post('/sabarangproduksi/getdetailsaldo', 'getdetailsaldo')->name('sabarangproduksi.getdetailsaldo');
    });


    Route::controller(PermintaanproduksiController::class)->group(function () {
        Route::get('/permintaanproduksi', 'index')->name('permintaanproduksi.index')->can('permintaanproduksi.index');
        Route::get('/permintaanproduksi/create', 'create')->name('permintaanproduksi.create')->can('permintaanproduksi.create');
        Route::post('/permintaanproduksi', 'store')->name('permintaanproduksi.store')->can('permintaanproduksi.store');
        Route::get('/permintaanproduksi/{no_permintaan}/edit', 'edit')->name('permintaanproduksi.edit')->can('permintaanproduksi.edit');
        Route::post('/permintaanproduksi/{no_permintaan}/update', 'update')->name('permintaanproduksi.update')->can('permintaanproduksi.update');
        Route::delete('/permintaanproduksi/{no_permintaan}', 'destroy')->name('permintaanproduksi.delete')->can('permintaanproduksi.delete');
        Route::get('/permintaanproduksi/{no_permintaan}/show', 'show')->name('permintaanproduksi.show')->can('permintaanproduksi.show');


        //AJAX REQUEST

        Route::post('/permintaanproduksi/getrealisasi', 'getrealisasi')->name('permintaanproduksi.getrealisasi');
    });

    Route::controller(LaporanproduksiController::class)->group(function () {
        Route::get('/laporanproduksi', 'index')->name('laporanproduksi.index');
        Route::post('/laporanproduksi/cetakmutasiproduksi', 'cetakmutasiproduksi')->name('cetakmutasiproduksi')->can('prd.mutasiproduksi');
        Route::post('/laporanproduksi/cetakrekapmutasiproduksi', 'cetakrekapmutasiproduksi')->name('cetakrekapmutasiproduksi')->can('prd.rekapmutasi');
        Route::post('/laporanproduksi/cetakbarangmasuk', 'cetakbarangmasuk')->name('cetakbarangmasukproduksi')->can('prd.pemasukan');
        Route::post('/laporanproduksi/cetakbarangkeluar', 'cetakbarangkeluar')->name('cetakbarangkeluarproduksi')->can('prd.pengeluaran');
        Route::post('/laporanproduksi/cetakrekappersediaanbarang', 'cetakrekappersediaanbarang')->name('cetakrekappersediaanbarangproduksi')->can('prd.rekappersediaan');
    });
    Route::controller(OmancabangController::class)->group(function () {
        Route::get('/omancabang', 'index')->name('omancabang.index')->can('omancabang.index');
        Route::get('/omancabang/create', 'create')->name('omancabang.create')->can('omancabang.create');
        Route::post('/omancabang', 'store')->name('omancabang.store')->can('omancabang.store');
        Route::get('/omancabang/{kode_oman}/edit', 'edit')->name('omancabang.edit')->can('omancabang.edit');
        Route::post('/omancabang/{kode_oman}/update', 'update')->name('omancabang.update')->can('omancabang.update');
        Route::delete('/omancabang/{kode_oman}', 'destroy')->name('omancabang.delete')->can('omancabang.delete');
        Route::get('/omancabang/{kode_oman}/show', 'show')->name('omancabang.show')->can('omancabang.show');

        //AJAX REQUEST
        Route::post('/omancabang/getomancabang', [OmancabangController::class, 'getomancabang'])->name('omancabang.getomancabang');
        Route::post('/omancabang/editprodukomancabang', [OmancabangController::class, 'editprodukomancabang'])->name('omancabang.editprodukomancabang');
        Route::post('/omancabang/updateprodukomancabang', [OmancabangController::class, 'updateprodukomancabang'])->name('omancabang.updateprodukomancabang');
    });



    Route::controller(OmanController::class)->group(function () {
        Route::get('/oman', 'index')->name('oman.index')->can('oman.index');
        Route::get('/oman/create', 'create')->name('oman.create')->can('oman.create');
        Route::post('/oman', 'store')->name('oman.store')->can('oman.store');
        Route::get('/oman/{kode_oman}/edit', 'edit')->name('oman.edit')->can('oman.edit');
        Route::post('/oman/{kode_oman}/update', 'update')->name('oman.update')->can('oman.update');
        Route::delete('/oman/{kode_oman}', 'destroy')->name('oman.delete')->can('oman.delete');
        Route::get('/oman/{kode_oman}/show', 'show')->name('oman.show')->can('oman.show');

        //AJAX REQUEST
        Route::get('/oman/{kode_oman}/getoman', [OmanController::class, 'getoman'])->name('oman.getoman');
    });

    Route::controller(PermintaankirimanController::class)->group(function () {
        Route::get('/permintaankiriman', 'index')->name('permintaankiriman.index')->can('permintaankiriman.index');
        Route::get('/permintaankiriman/create', 'create')->name('permintaankiriman.create')->can('permintaankiriman.create');
        Route::post('/permintaankiriman', 'store')->name('permintaankiriman.store')->can('permintaankiriman.store');
        Route::get('/permintaankiriman/{no_permintaan}/edit', 'edit')->name('permintaankiriman.edit')->can('permintaankiriman.edit');
        Route::post('/permintaankiriman/{no_permintaan}/update', 'update')->name('permintaankiriman.update')->can('permintaankiriman.update');
        Route::delete('/permintaankiriman/{no_permintaan}', 'destroy')->name('permintaankiriman.delete')->can('permintaankiriman.delete');
        Route::get('/permintaankiriman/{no_permintaan}/show', 'show')->name('permintaankiriman.show')->can('permintaankiriman.show');

        //AJAX REQUEST
        Route::post('/permintaankiriman/storedetailtemp', 'storedetailtemp')->name('permintaankiriman.storedetailtemp');
        Route::post('/permintaankiriman/cekdetailtemp', 'cekdetailtemp')->name('permintaankiriman.cekdetailtemp');
        Route::get('/permintaankiriman/getdetailtemp', 'getdetailtemp')->name('permintaankiriman.getdetailtemp');
        Route::post('/permintaankiriman/deletetemp', 'deletetemp')->name('permintaankiriman.deletetemp');
    });

    //Surat Jalan Gudang Jadi
    Route::controller(SuratjalanController::class)->group(function () {
        Route::get('/suratjalan', 'index')->name('suratjalan.index')->can('suratjalan.index');
        Route::get('/suratjalancabang', 'index_gudangcabang')->name('suratjalancabang.index')->can('suratjalancabang.index');
        Route::get('/suratjalan/{no_permintaan}/create', 'create')->name('suratjalan.create')->can('suratjalan.create');
        Route::post('/suratjalan/{no_permintaan}/store', 'store')->name('suratjalan.store')->can('suratjalan.store');
        Route::get('/suratjalan/{no_mutasi}/show', 'show')->name('suratjalan.show')->can('suratjalan.show');
        Route::get('/suratjalan/{no_mutasi}/edit', 'edit')->name('suratjalan.edit')->can('suratjalan.edit');
        Route::get('/suratjalan/{no_mutasi}/edit', 'edit')->name('suratjalan.edit')->can('suratjalan.edit');
        Route::put('/suratjalan/{no_mutasi}/update', 'update')->name('suratjalan.update')->can('suratjalan.update');
        Route::get('/suratjalan/{no_mutasi}/approveform', 'approveform')->name('suratjalan.approveform')->can('suratjalan.approve');
        Route::post('/suratjalan/{no_mutasi}/approve', 'approve')->name('suratjalan.approve')->can('suratjalan.approve');
        Route::delete('/suratjalan/{no_mutasi}/cancel', 'cancel')->name('suratjalan.cancel')->can('suratjalan.approve');
        Route::delete('/suratjalan/{no_mutasi}', 'destroy')->name('suratjalan.delete')->can('suratjalan.delete');
    });

    Route::controller(TujuanangkutanController::class)->group(function () {
        Route::get('/tujuanangkutan', 'index')->name('tujuanangkutan.index')->can('tujuanangkutan.index');
        Route::get('/tujuanangkutan/create', 'create')->name('tujuanangkutan.create')->can('tujuanangkutan.create');
        Route::post('/tujuanangkutan', 'store')->name('tujuanangkutan.store')->can('tujuanangkutan.store');
        Route::get('/tujuanangkutan/{kode_tujuan}/edit', 'edit')->name('tujuanangkutan.edit')->can('tujuanangkutan.edit');
        Route::post('/tujuanangkutan/{kode_tujuan}/update', 'update')->name('tujuanangkutan.update')->can('tujuanangkutan.update');
        Route::delete('/tujuanangkutan/{kode_tujuan}', 'destroy')->name('tujuanangkutan.delete')->can('tujuanangkutan.delete');
    });


    Route::controller(AngkutanController::class)->group(function () {
        Route::get('/angkutan', 'index')->name('angkutan.index')->can('angkutan.index');
        Route::get('/angkutan/create', 'create')->name('angkutan.create')->can('angkutan.create');
        Route::post('/angkutan', 'store')->name('angkutan.store')->can('angkutan.store');
        Route::get('/angkutan/{kode_angkutan}/edit', 'edit')->name('angkutan.edit')->can('angkutan.edit');
        Route::post('/angkutan/{kode_angkutan}/update', 'update')->name('angkutan.update')->can('angkutan.update');
        Route::delete('/angkutan/{kode_angkutan}', 'destroy')->name('angkutan.delete')->can('angkutan.delete');
    });


    Route::controller(RepackgudangjadiController::class)->group(function () {
        Route::get('/repackgudangjadi', 'index')->name('repackgudangjadi.index')->can('repackgudangjadi.index');
        Route::get('/repackgudangjadi/create', 'create')->name('repackgudangjadi.create')->can('repackgudangjadi.create');
        Route::post('/repackgudangjadi', 'store')->name('repackgudangjadi.store')->can('repackgudangjadi.store');
        Route::get('/repackgudangjadi/{no_mutasi}/show', 'show')->name('repackgudangjadi.show')->can('repackgudangjadi.show');
        Route::get('/repackgudangjadi/{no_mutasi}/edit', 'edit')->name('repackgudangjadi.edit')->can('repackgudangjadi.edit');
        Route::post('/repackgudangjadi/{no_mutasi}/update', 'update')->name('repackgudangjadi.update')->can('repackgudangjadi.update');
        Route::delete('/repackgudangjadi/{no_mutasi}', 'destroy')->name('repackgudangjadi.delete')->can('repackgudangjadi.delete');
    });


    Route::controller(RejectgudangjadiController::class)->group(function () {
        Route::get('/rejectgudangjadi', 'index')->name('rejectgudangjadi.index')->can('rejectgudangjadi.index');
        Route::get('/rejectgudangjadi/create', 'create')->name('rejectgudangjadi.create')->can('rejectgudangjadi.create');
        Route::post('/rejectgudangjadi', 'store')->name('rejectgudangjadi.store')->can('rejectgudangjadi.store');
        Route::get('/rejectgudangjadi/{no_mutasi}/show', 'show')->name('rejectgudangjadi.show')->can('rejectgudangjadi.show');
        Route::get('/rejectgudangjadi/{no_mutasi}/edit', 'edit')->name('rejectgudangjadi.edit')->can('rejectgudangjadi.edit');
        Route::post('/rejectgudangjadi/{no_mutasi}/update', 'update')->name('rejectgudangjadi.update')->can('rejectgudangjadi.update');
        Route::delete('/rejectgudangjadi/{no_mutasi}', 'destroy')->name('rejectgudangjadi.delete')->can('rejectgudangjadi.delete');
    });

    Route::controller(LainnyagudangjadiController::class)->group(function () {
        Route::get('/lainnyagudangjadi', 'index')->name('lainnyagudangjadi.index')->can('lainnyagudangjadi.index');
        Route::get('/lainnyagudangjadi/create', 'create')->name('lainnyagudangjadi.create')->can('lainnyagudangjadi.create');
        Route::post('/lainnyagudangjadi', 'store')->name('lainnyagudangjadi.store')->can('lainnyagudangjadi.store');
        Route::get('/lainnyagudangjadi/{no_mutasi}/show', 'show')->name('lainnyagudangjadi.show')->can('lainnyagudangjadi.show');
        Route::get('/lainnyagudangjadi/{no_mutasi}/edit', 'edit')->name('lainnyagudangjadi.edit')->can('lainnyagudangjadi.edit');
        Route::post('/lainnyagudangjadi/{no_mutasi}/update', 'update')->name('lainnyagudangjadi.update')->can('lainnyagudangjadi.update');
        Route::delete('/lainnyagudangjadi/{no_mutasi}', 'destroy')->name('lainnyagudangjadi.delete')->can('lainnyagudangjadi.delete');
    });

    Route::controller(SaldoawalgudangjadiController::class)->group(function () {
        Route::get('/sagudangjadi', 'index')->name('sagudangjadi.index')->can('sagudangjadi.index');
        Route::get('/sagudangjadi/create', 'create')->name('sagudangjadi.create')->can('sagudangjadi.create');
        Route::post('/sagudangjadi', 'store')->name('sagudangjadi.store')->can('sagudangjadi.store');
        Route::delete('/sagudangjadi/{kode_saldo_awal}', 'destroy')->name('sagudangjadi.delete')->can('sagudangjadi.delete');
        Route::get('/sagudangjadi/{kode_saldo_awal}/show', 'show')->name('sagudangjadi.show')->can('sagudangjadi.show');
        //AJAX REQUEST
        Route::post('/sagudangjadi/getdetailsaldo', 'getdetailsaldo')->name('sagudangjadi.getdetailsaldo');
    });

    Route::controller(SuratjalanangkutanController::class)->group(function () {
        Route::get('/suratjalanangkutan', 'index')->name('suratjalanangkutan.index')->can('suratjalanangkutan.index');
        Route::get('/suratjalanangkutan/{no_dok}/edit', 'edit')->name('suratjalanangkutan.edit')->can('suratjalanangkutan.edit');
        Route::put('/suratjalanangkutan/{no_dok}/update', 'update')->name('suratjalanangkutan.update')->can('suratjalanangkutan.update');
        // Route::get('/suratjalanangkutan/create', 'create')->name('suratjalanangkutan.create')->can('suratjalanangkutan.create');
        // Route::post('/suratjalanangkutan', 'store')->name('suratjalanangkutan.store')->can('suratjalanangkutan.store');
        // Route::delete('/suratjalanangkutan/{kode_saldo_awal}', 'destroy')->name('suratjalanangkutan.delete')->can('suratjalanangkutan.delete');
        // Route::get('/suratjalanangkutan/{kode_saldo_awal}/show', 'show')->name('suratjalanangkutan.show')->can('suratjalanangkutan.show');

        Route::get('/suratjalanangkutan/{kode_angkutan}/getsuratjalanbyangkutan', 'getsuratjalanbyangkutan')->name('suratjalanangkutan.getsuratjalanbyangkutan');
    });

    Route::controller(LaporangudangjadiController::class)->group(function () {
        Route::get('/laporangudangjadi', 'index')->name('laporangudangjadi.index');
        Route::post('/laporangudangjadi/cetakpersediaan', 'cetakpersediaan')->name('laporangudangjadi.cetakpersediaan')->can('gj.persediaan');
        Route::post('/laporangudangjadi/cetakrekappersediaan', 'cetakrekappersediaan')->name('laporangudangjadi.cetakrekappersediaan')->can('gj.rekappersediaan');
        Route::post('/laporangudangjadi/cetakrekaphasilproduksi', 'cetakrekaphasilproduksi')->name('laporangudangjadi.cetakrekaphasilproduksi')->can('gj.rekaphasilproduksi');
        Route::post('/laporangudangjadi/cetakrekappengeluaran', 'cetakrekappengeluaran')->name('laporangudangjadi.cetakrekappengeluaran')->can('gj.rekappengeluaran');
        Route::post('/laporangudangjadi/cetakrealisasikiriman', 'cetakrealisasikiriman')->name('laporangudangjadi.cetakrealisasikiriman')->can('gj.realisasikiriman');
        Route::post('/laporangudangjadi/cetakrealisasioman', 'cetakrealisasioman')->name('laporangudangjadi.cetakrealisasioman')->can('gj.realisasioman');
        Route::post('/laporangudangjadi/cetakangkutan', 'cetakangkutan')->name('laporangudangjadi.cetakangkutan')->can('gj.angkutan');
    });


    //Gudang Bahan
    Route::controller(BarangmasukgudangbahanController::class)->group(function () {
        Route::get('/barangmasukgudangbahan', 'index')->name('barangmasukgudangbahan.index')->can('barangmasukgb.index');
        Route::get('/barangmasukgudangbahan/create', 'create')->name('barangmasukgudangbahan.create')->can('barangmasukgb.create');
        Route::get('/barangmasukgudangbahan/{no_bukti}/edit', 'edit')->name('barangmasukgudangbahan.edit')->can('barangmasukgb.edit');
        Route::put('/barangmasukgudangbahan/{no_bukti}/update', 'update')->name('barangmasukgudangbahan.update')->can('barangmasukgb.update');
        Route::post('/barangmasukgudangbahan', 'store')->name('barangmasukgudangbahan.store')->can('barangmasukgb.store');
        Route::delete('/barangmasukgudangbahan/{no_bukti}', 'destroy')->name('barangmasukgudangbahan.delete')->can('barangmasukgb.delete');
        Route::get('/barangmasukgudangbahan/{no_bukti}/show', 'show')->name('barangmasukgudangbahan.show')->can('barangmasukgb.show');
    });

    Route::controller(BarangkeluargudangbahanController::class)->group(function () {
        Route::get('/barangkeluargudangbahan', 'index')->name('barangkeluargudangbahan.index')->can('barangkeluargb.index');
        Route::get('/barangkeluargudangbahan/create', 'create')->name('barangkeluargudangbahan.create')->can('barangkeluargb.create');
        Route::get('/barangkeluargudangbahan/{no_bukti}/edit', 'edit')->name('barangkeluargudangbahan.edit')->can('barangkeluargb.edit');
        Route::put('/barangkeluargudangbahan/{no_bukti}/update', 'update')->name('barangkeluargudangbahan.update')->can('barangkeluargb.update');
        Route::post('/barangkeluargudangbahan', 'store')->name('barangkeluargudangbahan.store')->can('barangkeluargb.store');
        Route::delete('/barangkeluargudangbahan/{no_bukti}', 'destroy')->name('barangkeluargudangbahan.delete')->can('barangkeluargb.delete');
        Route::get('/barangkeluargudangbahan/{no_bukti}/show', 'show')->name('barangkeluargudangbahan.show')->can('barangkeluargb.show');
    });

    Route::controller(SaldoawalgudangbahanController::class)->group(function () {
        Route::get('/sagudangbahan', 'index')->name('sagudangbahan.index')->can('sagudangbahan.index');
        Route::get('/sagudangbahan/create', 'create')->name('sagudangbahan.create')->can('sagudangbahan.create');
        Route::post('/sagudangbahan', 'store')->name('sagudangbahan.store')->can('sagudangbahan.store');
        Route::delete('/sagudangbahan/{kode_saldo_awal}', 'destroy')->name('sagudangbahan.delete')->can('sagudangbahan.delete');
        Route::get('/sagudangbahan/{kode_saldo_awal}/show', 'show')->name('sagudangbahan.show')->can('sagudangbahan.show');
        //AJAX REQUEST
        Route::post('/sagudangbahan/getdetailsaldo', 'getdetailsaldo')->name('sagudangbahan.getdetailsaldo');
    });

    Route::controller(SaldoawalhargagudangbahanController::class)->group(function () {
        Route::get('/sahargagb', 'index')->name('sahargagb.index')->can('sahargagb.index');
        Route::get('/sahargagb/create', 'create')->name('sahargagb.create')->can('sahargagb.create');
        Route::post('/sahargagb', 'store')->name('sahargagb.store')->can('sahargagb.store');
        Route::delete('/sahargagb/{kode_saldo_awal}', 'destroy')->name('sahargagb.delete')->can('sahargagb.delete');
        Route::get('/sahargagb/{kode_saldo_awal}/show', 'show')->name('sahargagb.show')->can('sahargagb.show');
        //AJAX REQUEST
        Route::post('/sahargagb/getdetailsaldo', 'getdetailsaldo')->name('sahargagb.getdetailsaldo');
    });

    Route::controller(OpnamegudangbahanController::class)->group(function () {
        Route::get('/opgudangbahan', 'index')->name('opgudangbahan.index')->can('opgudangbahan.index');
        Route::get('/opgudangbahan/create', 'create')->name('opgudangbahan.create')->can('opgudangbahan.create');
        Route::post('/opgudangbahan', 'store')->name('opgudangbahan.store')->can('opgudangbahan.store');
        Route::delete('/opgudangbahan/{kode_opname}', 'destroy')->name('opgudangbahan.delete')->can('opgudangbahan.delete');
        Route::get('/opgudangbahan/{kode_opname}/show', 'show')->name('opgudangbahan.show')->can('opgudangbahan.show');
        Route::get('/opgudangbahan/{kode_opname}/edit', 'edit')->name('opgudangbahan.edit')->can('opgudangbahan.edit');
        //AJAX REQUEST
        Route::post('/opgudangbahan/getdetailsaldo', 'getdetailsaldo')->name('opgudangbahan.getdetailsaldo');
    });


    Route::controller(LaporangudangbahanController::class)->group(function () {
        Route::get('/laporangudangbahan', 'index')->name('laporangudangbahan.index');
        Route::post('/laporangudangbahan/cetakbarangmasuk', 'cetakbarangmasuk')->name('laporangudangbahan.cetakbarangmasuk')->can('gb.barangmasuk');
        Route::post('/laporangudangbahan/cetakbarangkeluar', 'cetakbarangkeluar')->name('laporangudangbahan.cetakbarangkeluar')->can('gb.barangkeluar');
        Route::post('/laporangudangbahan/cetakpersediaan', 'cetakpersediaan')->name('laporangudangbahan.cetakpersediaan')->can('gb.persediaan');
        Route::post('/laporangudangbahan/cetakrekappersediaan', 'cetakrekappersediaan')->name('laporangudangbahan.cetakrekappersediaan')->can('gb.rekappersediaan');
        Route::post('/laporangudangbahan/cetakkartugudang', 'cetakkartugudang')->name('laporangudangbahan.cetakkartugudang')->can('gb.kartugudang');
    });

    //Gudang Logistik
    Route::controller(BarangmasukgudanglogistikController::class)->group(function () {
        Route::get('/barangmasukgudanglogistik', 'index')->name('barangmasukgudanglogistik.index')->can('barangmasukgl.index');
        Route::get('/barangmasukgudanglogistik/create', 'create')->name('barangmasukgudanglogistik.create')->can('barangmasukgl.create');
        Route::get('/barangmasukgudanglogistik/{no_bukti}/edit', 'edit')->name('barangmasukgudanglogistik.edit')->can('barangmasukgl.edit');
        Route::put('/barangmasukgudanglogistik/{no_bukti}/update', 'update')->name('barangmasukgudanglogistik.update')->can('barangmasukgl.update');
        Route::post('/barangmasukgudanglogistik', 'store')->name('barangmasukgudanglogistik.store')->can('barangmasukgl.store');
        Route::delete('/barangmasukgudanglogistik/{no_bukti}', 'destroy')->name('barangmasukgudanglogistik.delete')->can('barangmasukgl.delete');
        Route::get('/barangmasukgudanglogistik/{no_bukti}/show', 'show')->name('barangmasukgudanglogistik.show')->can('barangmasukgl.show');
    });

    Route::controller(BarangkeluargudanglogistikController::class)->group(function () {
        Route::get('/barangkeluargudanglogistik', 'index')->name('barangkeluargudanglogistik.index')->can('barangkeluargl.index');
        Route::get('/barangkeluargudanglogistik/create', 'create')->name('barangkeluargudanglogistik.create')->can('barangkeluargl.create');
        Route::get('/barangkeluargudanglogistik/{no_bukti}/edit', 'edit')->name('barangkeluargudanglogistik.edit')->can('barangkeluargl.edit');
        Route::put('/barangkeluargudanglogistik/{no_bukti}/update', 'update')->name('barangkeluargudanglogistik.update')->can('barangkeluargl.update');
        Route::post('/barangkeluargudanglogistik', 'store')->name('barangkeluargudanglogistik.store')->can('barangkeluargl.store');
        Route::delete('/barangkeluargudanglogistik/{no_bukti}', 'destroy')->name('barangkeluargudanglogistik.delete')->can('barangkeluargl.delete');
        Route::get('/barangkeluargudanglogistik/{no_bukti}/show', 'show')->name('barangkeluargudanglogistik.show')->can('barangkeluargl.show');
    });

    Route::controller(SaldoawalgudanglogistikController::class)->group(function () {
        Route::get('/sagudanglogistik', 'index')->name('sagudanglogistik.index')->can('sagudanglogistik.index');
        Route::get('/sagudanglogistik/create', 'create')->name('sagudanglogistik.create')->can('sagudanglogistik.create');
        Route::post('/sagudanglogistik', 'store')->name('sagudanglogistik.store')->can('sagudanglogistik.store');
        Route::delete('/sagudanglogistik/{kode_saldo_awal}', 'destroy')->name('sagudanglogistik.delete')->can('sagudanglogistik.delete');
        Route::get('/sagudanglogistik/{kode_saldo_awal}/show', 'show')->name('sagudanglogistik.show')->can('sagudanglogistik.show');
        //AJAX REQUEST
        Route::post('/sagudanglogistik/getdetailsaldo', 'getdetailsaldo')->name('sagudanglogistik.getdetailsaldo');
    });

    Route::controller(OpnamegudanglogistikController::class)->group(function () {
        Route::get('/opgudanglogistik', 'index')->name('opgudanglogistik.index')->can('opgudanglogistik.index');
        Route::get('/opgudanglogistik/create', 'create')->name('opgudanglogistik.create')->can('opgudanglogistik.create');
        Route::post('/opgudanglogistik', 'store')->name('opgudanglogistik.store')->can('opgudanglogistik.store');
        Route::get('/opgudanglogistik/{kode_opname}/edit', 'edit')->name('opgudanglogistik.edit')->can('opgudanglogistik.edit');
        Route::put('/opgudanglogistik/{kode_opname}/update', 'update')->name('opgudanglogistik.update')->can('opgudanglogistik.update');
        Route::delete('/opgudanglogistik/{kode_saldo_awal}', 'destroy')->name('opgudanglogistik.delete')->can('opgudanglogistik.delete');
        Route::get('/opgudanglogistik/{kode_saldo_awal}/show', 'show')->name('opgudanglogistik.show')->can('opgudanglogistik.show');
        //AJAX REQUEST
        Route::post('/opgudanglogistik/getdetailsaldo', 'getdetailsaldo')->name('opgudanglogistik.getdetailsaldo');
    });

    Route::controller(LaporangudanglogistikController::class)->group(function () {
        Route::get('/laporangudanglogistik', 'index')->name('laporangudanglogistik.index');
        Route::post('/laporangudanglogistik/cetakbarangmasuk', 'cetakbarangmasuk')->name('laporangudanglogistik.cetakbarangmasuk')->can('gl.barangmasuk');
        Route::post('/laporangudanglogistik/cetakbarangkeluar', 'cetakbarangkeluar')->name('laporangudanglogistik.cetakbarangkeluar')->can('gl.barangkeluar');
        Route::post('/laporangudanglogistik/cetakpersediaan', 'cetakpersediaan')->name('laporangudanglogistik.cetakpersediaan')->can('gl.persediaan');
        Route::post('/laporangudanglogistik/cetakpersediaanopname', 'cetakpersediaanopname')->name('laporangudanglogistik.cetakpersediaanopname')->can('gl.persediaanopname');
    });

    //Gudang Jadi Cabang
    Route::controller(DpbController::class)->group(function () {
        Route::get('/dpb', 'index')->name('dpb.index')->can('dpb.index');
        Route::get('/dpb/create', 'create')->name('dpb.create')->can('dpb.create');
        Route::get('/dpb/{no_dpb}/edit', 'edit')->name('dpb.edit')->can('dpb.edit');
        Route::put('/dpb/{no_dpb}/update', 'update')->name('dpb.update')->can('dpb.update');
        Route::post('/dpb', 'store')->name('dpb.store')->can('dpb.store');
        Route::delete('/dpb/{no_dpb}', 'destroy')->name('dpb.delete')->can('dpb.delete');
        Route::get('/dpb/{no_dpb}/show', 'show')->name('dpb.show')->can('dpb.show');

        //AJAX REQUEST
        Route::get('/dpb/{no_dpb}/getdetailmutasidpb', 'getdetailmutasidpb')->name('dpb.getdetailmutasidpb');
        Route::post('/dpb/generatenodpb', 'generatenodpb')->name('dpb.generatenodpb');
        Route::post('/dpb/getautocompletedpb', 'getautocompletedpb')->name('dpb.getautocompletedpb');
    });

    Route::controller(MutasidpbController::class)->group(function () {
        Route::get('/mutasidpb/create', 'create')->name('mutasidpb.create')->can('mutasidpb.create');
        Route::post('/mutasidpb', 'store')->name('mutasidpb.store')->can('mutasidpb.store');
        Route::get('/mutasidpb/{no_mutasi}/show', 'show')->name('mutasidpb.show')->can('mutasidpb.show');
        Route::post('/mutasidpb/delete', 'destroy')->name('mutasidpb.delete')->can('mutasidpb.delete');
        Route::get('/mutasidpb/{no_mutasi}/edit', 'edit')->name('mutasidpb.edit')->can('mutasidpb.edit');
        Route::post('/mutasidpb/update', 'update')->name('mutasidpb.update')->can('mutasidpb.update');
        Route::get('/mutasidpb/{no_dpb}/{jenis_mutasi}/getmutasidpb', 'getmutasidpb')->name('mutasidpb.getmutasidpb');
    });

    Route::controller(TransitinController::class)->group(function () {
        Route::get('/transitin', 'index')->name('transitin.index')->can('transitin.index');
        Route::get('/transitin/{no_mutasi}/create', 'create')->name('transitin.create')->can('transitin.create');
        Route::post('/transitin/{no_mutasi}', 'store')->name('transitin.store')->can('transitin.store');
        Route::delete('/transitin/{no_surat_jalan}', 'destroy')->name('transitin.delete')->can('transitin.delete');
    });

    Route::controller(RejectController::class)->group(function () {
        Route::get('/reject', 'index')->name('reject.index')->can('reject.index');
        Route::get('/reject/create', 'create')->name('reject.create')->can('reject.create');
        Route::get('/reject/{no_mutasi}/show', 'show')->name('reject.show')->can('reject.show');
        Route::get('/reject/{no_mutasi}/edit', 'edit')->name('reject.edit')->can('reject.edit');
        Route::post('/reject', 'store')->name('reject.store')->can('reject.store');
        Route::put('/reject/{no_mutasi}', 'update')->name('reject.update')->can('reject.update');
        Route::delete('/reject/{no_mutasi}', 'destroy')->name('reject.delete')->can('reject.delete');
    });

    Route::controller(RepackgudangcabangController::class)->group(function () {
        Route::get('/repackcbg', 'index')->name('repackcbg.index')->can('repackcbg.index');
        Route::get('/repackcbg/create', 'create')->name('repackcbg.create')->can('repackcbg.create');
        Route::get('/repackcbg/{no_mutasi}/show', 'show')->name('repackcbg.show')->can('repackcbg.show');
        Route::get('/repackcbg/{no_mutasi}/edit', 'edit')->name('repackcbg.edit')->can('repackcbg.edit');
        Route::post('/repackcbg', 'store')->name('repackcbg.store')->can('repackcbg.store');
        Route::put('/repackcbg/{no_mutasi}', 'update')->name('repackcbg.update')->can('repackcbg.update');
        Route::delete('/repackcbg/{no_mutasi}', 'destroy')->name('repackcbg.delete')->can('repackcbg.delete');
    });

    Route::controller(KirimpusatController::class)->group(function () {
        Route::get('/kirimpusat', 'index')->name('kirimpusat.index')->can('kirimpusat.index');
        Route::get('/kirimpusat/create', 'create')->name('kirimpusat.create')->can('kirimpusat.create');
        Route::get('/kirimpusat/{no_mutasi}/show', 'show')->name('kirimpusat.show')->can('kirimpusat.show');
        Route::get('/kirimpusat/{no_mutasi}/edit', 'edit')->name('kirimpusat.edit')->can('kirimpusat.edit');
        Route::post('/kirimpusat', 'store')->name('kirimpusat.store')->can('kirimpusat.store');
        Route::put('/kirimpusat/{no_mutasi}', 'update')->name('kirimpusat.update')->can('kirimpusat.update');
        Route::delete('/kirimpusat/{no_mutasi}', 'destroy')->name('kirimpusat.delete')->can('kirimpusat.delete');
    });

    Route::controller(PenyesuaiangudangcabangController::class)->group(function () {
        Route::get('/penygudangcbg', 'index')->name('penygudangcbg.index')->can('penygudangcbg.index');
        Route::get('/penygudangcbg/create', 'create')->name('penygudangcbg.create')->can('penygudangcbg.create');
        Route::get('/penygudangcbg/{no_mutasi}/show', 'show')->name('penygudangcbg.show')->can('penygudangcbg.show');
        Route::get('/penygudangcbg/{no_mutasi}/edit', 'edit')->name('penygudangcbg.edit')->can('penygudangcbg.edit');
        Route::post('/penygudangcbg', 'store')->name('penygudangcbg.store')->can('penygudangcbg.store');
        Route::put('/penygudangcbg/{no_mutasi}', 'update')->name('penygudangcbg.update')->can('penygudangcbg.update');
        Route::delete('/penygudangcbg/{no_mutasi}', 'destroy')->name('penygudangcbg.delete')->can('penygudangcbg.delete');
    });

    Route::controller(SaldoawalgudangcabangController::class)->group(function () {
        Route::get('/sagudangcabang', 'index')->name('sagudangcabang.index')->can('sagudangcabang.index');
        Route::get('/sagudangcabang/create', 'create')->name('sagudangcabang.create')->can('sagudangcabang.create');
        Route::post('/sagudangcabang', 'store')->name('sagudangcabang.store')->can('sagudangcabang.store');
        Route::delete('/sagudangcabang/{kode_saldo_awal}', 'destroy')->name('sagudangcabang.delete')->can('sagudangcabang.delete');
        Route::get('/sagudangcabang/{kode_saldo_awal}/show', 'show')->name('sagudangcabang.show')->can('sagudangcabang.show');
        //AJAX REQUEST
        Route::post('/sagudangcabang/getdetailsaldo', 'getdetailsaldo')->name('sagudangcabang.getdetailsaldo');
    });


    Route::controller(LaporangudangcabangController::class)->group(function () {
        Route::get('/laporangudangcabang', 'index')->name('laporangudangcabang.index');
        Route::post('/laporangudangcabang/cetakpersediaangs', 'cetakpersediaangs')->name('laporangudangcabang.cetakpersediaangs')->can('gc.goodstok');
        Route::post('/laporangudangcabang/cetakpersediaanbs', 'cetakpersediaanbs')->name('laporangudangcabang.cetakpersediaanbs')->can('gc.badstok');
        Route::post('/laporangudangcabang/cetakrekappersediaan', 'cetakrekappersediaan')->name('laporangudangcabang.cetakrekappersediaan')->can('gc.rekappersediaan');
        Route::post('/laporangudangcabang/cetakmutasidpb', 'cetakmutasidpb')->name('laporangudangcabang.cetakmutasidpb')->can('gc.mutasidpb');
        Route::post('/laporangudangcabang/cetakrekkonsiliasibj', 'cetakrekonsiliasibj')->name('laporangudangcabang.cetakrekonsiliasibj')->can('gc.rekonsiliasibj');
    });


    //Marketing
    //Target Komisi
    Route::controller(TargetkomisiController::class)->group(function () {
        Route::get('/targetkomisi', 'index')->name('targetkomisi.index')->can('targetkomisi.index');
        Route::get('/targetkomisi/create', 'create')->name('targetkomisi.create')->can('targetkomisi.create');
        Route::post('/targetkomisi', 'store')->name('targetkomisi.store')->can('targetkomisi.store');
        Route::get('/targetkomisi/{kode_target}/edit', 'edit')->name('targetkomisi.edit')->can('targetkomisi.edit');
        Route::put('/targetkomisi/{kode_target}/update', 'update')->name('targetkomisi.update')->can('targetkomisi.update');
        Route::get('/targetkomisi/{kode_target}/show', 'show')->name('targetkomisi.show')->can('targetkomisi.show');
        Route::get('/targetkomisi/{kode_target}/approve', 'approve')->name('targetkomisi.approve')->can('targetkomisi.approve');
        Route::get('/targetkomisi/{kode_target}/approvestore', 'approvestore')->name('targetkomisi.approvestore')->can('targetkomisi.approve');
        Route::delete('/targetkomisi/{kode_target}/cancel', 'cancel')->name('targetkomisi.cancel')->can('targetkomisi.approve');
        Route::delete('/targetkomisi/{kode_target}', 'destroy')->name('targetkomisi.delete')->can('targetkomisi.delete');

        Route::post('/targetkomisi/gettargetsalesman', 'gettargetsalesman')->name('targetkomisi.gettargetsalesman');
        Route::post('/targetkomisi/gettarget', 'gettarget')->name('targetkomisi.gettarget');
        Route::post('/targetkomisi/gettargetsalesmandashboard', 'gettargetsalesmandashboard')->name('targetkomisi.gettargetsalesmandashboard');
        Route::post('/targetkomisi/gettargetsalesmanedit', 'gettargetsalesmanedit')->name('targetkomisi.gettargetsalesmanedit');
    });

    Route::controller(RatiodriverhelperController::class)->group(function () {
        Route::get('/ratiodriverhelper', 'index')->name('ratiodriverhelper.index')->can('ratiodriverhelper.index');
        Route::get('/ratiodriverhelper/create', 'create')->name('ratiodriverhelper.create')->can('ratiodriverhelper.create');
        Route::get('/ratiodriverhelper/{kode_ratio}', 'show')->name('ratiodriverhelper.show')->can('ratiodriverhelper.show');
        Route::get('/ratiodriverhelper/{kode_ratio}/edit', 'edit')->name('ratiodriverhelper.edit')->can('ratiodriverhelper.edit');
        Route::put('/ratiodriverhelper/{kode_ratio}', 'update')->name('ratiodriverhelper.update')->can('ratiodriverhelper.update');
        Route::post('/ratiodriverhelper', 'store')->name('ratiodriverhelper.store')->can('ratiodriverhelper.store');
        Route::delete('/ratiodriverhelper/{kode_ratio}', 'destroy')->name('ratiodriverhelper.delete')->can('ratiodriverhelper.delete');

        Route::post('/ratiodriverhelper/getratiodriverhelper', 'getratiodriverhelper')->name('ratiodriverhelper.getratiodriverhelper');
        Route::post('/ratiodriverhelper/getratiodriverhelperedit', 'getratiodriverhelperedit')->name('ratiodriverhelper.getratiodriverhelperedit');
    });


    Route::controller(SettingkomisidriverhelperController::class)->group(function () {
        Route::get('/settingkomisidriverhelper', 'index')->name('settingkomisidriverhelper.index');
        Route::get('/settingkomisidriverhelper/create', 'create')->name('settingkomisidriverhelper.create');
        Route::post('/settingkomisidriverhelper', 'store')->name('settingkomisidriverhelper.store');
        Route::get('/settingkomisidriverhelper/{kode_komisi}/edit', 'edit')->name('settingkomisidriverhelper.edit');
        Route::put('/settingkomisidriverhelper/{kode_komisi}', 'update')->name('settingkomisidriverhelper.update');
        Route::delete('/settingkomisidriverhelper/{kode_komisi}', 'destroy')->name('settingkomisidriverhelper.delete');
        Route::get('/settingkomisidriverhelper/{kode_komisi}/cetak', 'cetak')->name('settingkomisidriverhelper.cetak');
    });

    Route::controller(PenjualanController::class)->group(function () {
        Route::get('/penjualan', 'index')->name('penjualan.index')->can('penjualan.index');
        Route::get('/penjualan/create', 'create')->name('penjualan.create')->can('penjualan.create');
        Route::post('/penjualan/store', 'store')->name('penjualan.store')->can('penjualan.store');
        Route::get('/penjualan/{no_faktur}/edit', 'edit')->name('penjualan.edit')->can('penjualan.edit');
        Route::put('/penjualan/{no_faktur}/update', 'update')->name('penjualan.update')->can('penjualan.update');
        Route::delete('/penjualan/{no_faktur}/delete', 'destroy')->name('penjualan.delete')->can('penjualan.delete');

        Route::get('/penjualan/{no_faktur}/show', 'show')->name('penjualan.show')->can('penjualan.show');
        Route::get('/penjualan/{no_faktur}/cetakfaktur', 'cetakfaktur')->name('penjualan.cetakfaktur')->can('penjualan.cetakfaktur');
        Route::get('/penjualan/{no_faktur}/{type}/cetaksuratjalan', 'cetaksuratjalan')->name('penjualan.cetaksuratjalan')->can('penjualan.cetaksuratjalan');
        Route::get('/penjualan/filtersuratjalan', 'filtersuratjalan')->name('penjualan.filtersuratjalan')->can('penjualan.cetaksuratjalan');
        Route::get('/penjualan/{no_faktur}/batalfaktur', 'batalfaktur')->name('penjualan.batalfaktur')->can('penjualan.batalfaktur');
        Route::put('/penjualan/{no_faktur}/updatefakturbatal', 'updatefakturbatal')->name('penjualan.updatefakturbatal')->can('penjualan.batalfaktur');
        Route::post('/penjualan/cetaksuratjalanrange', 'cetaksuratjalanrange')->name('penjualan.cetaksuratjalanrange')->can('penjualan.cetaksuratjalan');
        Route::get('/penjualan/{no_faktur}/generatefaktur', 'generatefaktur')->name('penjualan.generatefaktur')->can('penjualan.update');

        Route::get('/penjualan/{no_faktur}/updatelockprint', 'updatelockprint')->name('penjualan.updatelockprint')->can('penjualan.updatelockprint');

        //AJAX REQUEST
        Route::post('/penjualan/generatenofaktur', 'generatenofaktur')->name('penjualan.generatenofaktur');
        Route::post('/penjualan/editproduk', 'editproduk')->name('penjualan.editproduk');
        Route::post('/penjualan/getfakturbypelanggan', 'getfakturbypelanggan')->name('penjualan.getfakturbypelanggan');
        Route::get('/penjualan/{no_faktur}/getpiutangfaktur', 'getpiutangfaktur')->name('penjualan.getpiutangfaktur');
    });

    Route::controller(ReturController::class)->group(function () {
        Route::get('/retur', 'index')->name('retur.index')->can('retur.index');
        Route::get('/retur/create', 'create')->name('retur.create')->can('retur.create');
        Route::post('/retur/store', 'store')->name('retur.store')->can('retur.store');
        Route::get('/retur/{no_retur}/edit', 'edit')->name('retur.edit')->can('retur.edit');
        Route::put('/retur/{no_retur}/update', 'update')->name('retur.update')->can('retur.update');
        Route::get('/retur/{no_retur}/show', 'show')->name('retur.show')->can('penjualan.show');
        Route::delete('/retur/{no_retur}/delete', 'destroy')->name('retur.delete')->can('retur.delete');

        //AJAX REQUEST
        Route::post('/retur/editproduk', 'editproduk')->name('retur.editproduk');
    });
    Route::controller(PembayaranpenjualanController::class)->group(function () {
        Route::get('/pembayaranpenjualan/{no_faktur}/create', 'create')->name('pembayaranpenjualan.create')->can('pembayaranpenjualan.create');
        Route::post('/pembayaranpenjualan/{no_faktur}/store', 'store')->name('pembayaranpenjualan.store')->can('pembayaranpenjualan.store');
        Route::get('/pembayaranpenjualan/{no_bukti}/edit', 'edit')->name('pembayaranpenjualan.edit')->can('pembayaranpenjualan.edit');
        Route::put('/pembayaranpenjualan/{no_bukti}/update', 'update')->name('pembayaranpenjualan.update')->can('pembayaranpenjualan.update');
        Route::delete('/pembayaranpenjualan/{no_bukti}/delete', 'destroy')->name('pembayaranpenjualan.delete')->can('pembayaranpenjualan.delete');
    });


    Route::controller(PembayarangiroController::class)->group(function () {
        Route::get('/pembayarangiro', 'index')->name('pembayarangiro.index')->can('pembayarangiro.index');
        Route::get('/pembayarangiro/{no_faktur}/create', 'create')->name('pembayarangiro.create')->can('pembayarangiro.create');
        Route::get('/pembayarangiro/creategroup', 'creategroup')->name('pembayarangiro.creategroup')->can('pembayarangiro.create');
        Route::post('/pembayarangiro/{no_faktur}/store', 'store')->name('pembayarangiro.store')->can('pembayarangiro.store');
        Route::post('/pembayarangiro/storegroup', 'storegroup')->name('pembayarangiro.storegroup')->can('pembayarangiro.store');
        Route::get('/pembayarangiro/{no_faktur}/{kode_giro}/edit', 'edit')->name('pembayarangiro.edit')->can('pembayarangiro.edit');
        Route::put('/pembayarangiro/{no_faktur}/{kode_giro}/update', 'update')->name('pembayarangiro.update')->can('pembayarangiro.update');
        Route::delete('/pembayarangiro/{no_faktur}/{kode_giro}/delete', 'destroy')->name('pembayarangiro.delete')->can('pembayarangiro.delete');
        Route::delete('/pembayarangiro/{kode_giro}/deletegiro', 'destroygiro')->name('pembayarantransfer.deletegiro')->can('pembayarantransfer.delete');
        Route::get('/pembayarangiro/{kode_giro}/approve', 'approve')->name('pembayarangiro.approve')->can('pembayarangiro.approve');
        Route::post('/pembayarangiro/{kode_giro}/approvestore', 'approvestore')->name('pembayarangiro.approvestore')->can('pembayarangiro.approve');
        Route::get('/pembayarangiro/{kode_giro}/show', 'show')->name('pembayarangiro.show')->can('pembayarangiro.show');
    });

    Route::controller(PembayarantransferController::class)->group(function () {
        Route::get('/pembayarantransfer', 'index')->name('pembayarantransfer.index')->can('pembayarantransfer.index');
        Route::get('/pembayarantransfer/{no_faktur}/create', 'create')->name('pembayarantransfer.create')->can('pembayarantransfer.create');
        Route::get('/pembayarantransfer/creategroup', 'creategroup')->name('pembayarantransfer.creategroup')->can('pembayarantransfer.create');
        Route::get('/pembayarantransfer/{kode_transfer}/show', 'show')->name('pembayarantransfer.show')->can('pembayarantransfer.show');
        Route::get('/pembayarantransfer/{kode_transfer}/approve', 'approve')->name('pembayarantransfer.approve')->can('pembayarantransfer.approve');
        Route::post('/pembayarantransfer/{kode_transfer}/approvestore', 'approvestore')->name('pembayarantransfer.approvestore')->can('pembayarantransfer.approve');

        Route::post('/pembayarantransfer/{no_faktur}/store', 'store')->name('pembayarantransfer.store')->can('pembayarantransfer.store');
        Route::post('/pembayarantransfer/storegroup', 'storegroup')->name('pembayarantransfer.storegroup')->can('pembayarantransfer.store');
        Route::get('/pembayarantransfer/{no_faktur}/{kode_transfer}/edit', 'edit')->name('pembayarantransfer.edit')->can('pembayarantransfer.edit');
        Route::put('/pembayarantransfer/{no_faktur}/{kode_transfer}/update', 'update')->name('pembayarantransfer.update')->can('pembayarantransfer.update');
        Route::delete('/pembayarantransfer/{no_faktur}/{kode_transfer}/delete', 'destroy')->name('pembayarantransfer.delete')->can('pembayarantransfer.delete');
        Route::delete('/pembayarantransfer/{kode_transfer}/deletetransfer', 'destroytransfer')->name('pembayarantransfer.deletetransfer')->can('pembayarantransfer.delete');
    });

    Route::controller(AjuanlimitkreditController::class)->group(function () {
        Route::get('/ajuanlimit', 'index')->name('ajuanlimit.index')->can('ajuanlimit.index');
        Route::get('/ajuanlimit/create', 'create')->name('ajuanlimit.create')->can('ajuanlimit.create');
        Route::post('/ajuanlimit/store', 'store')->name('ajuanlimit.store')->can('ajuanlimit.store');
        Route::delete('/ajuanlimit/{no_pengajuan}/delete', 'destroy')->name('ajuanlimit.delete')->can('ajuanlimit.delete');
        Route::get('/ajuanlimit/{no_pengajuan}/show', 'show')->name('ajuanlimit.show')->can('ajuanlimit.show');
        Route::get('/ajuanlimit/{no_pengajuan}/edit', 'edit')->name('ajuanlimit.edit')->can('ajuanlimit.edit');
        Route::get('/ajuanlimit/{no_pengajuan}/cetak', 'cetak')->name('ajuanlimit.cetak')->can('ajuanlimit.show');

        Route::get('/ajuanlimit/{no_pengajuan}/approve', 'approve')->name('ajuanlimit.approve')->can('ajuanlimit.approve');
        Route::post('/ajuanlimit/{no_pengajuan}/approvestore', 'approvestore')->name('ajuanlimit.approvestore')->can('ajuanlimit.approve');
        Route::delete('/ajuanlimit/{no_pengajuan}/cancel', 'cancel')->name('ajuanlimit.cancel')->can('ajuanlimit.approve');
        Route::get('/ajuanlimit/{no_pengajuan}/adjust', 'adjust')->name('ajuanlimit.adjust')->can('ajuanlimit.adjust');
        Route::post('/ajuanlimit/{no_pengajuan}/adjuststore', 'adjuststore')->name('ajuanlimit.adjuststore')->can('ajuanlimit.adjust');
        //AJAX REQUEST
        Route::post('/ajuanlimit/gettopupterakhir', 'gettopupTerakhir')->name('ajuanlimit.gettopupterakhir');
    });


    Route::controller(AjuanfakturkreditController::class)->group(function () {
        Route::get('/ajuanfaktur', 'index')->name('ajuanfaktur.index')->can('ajuanfaktur.index');
        Route::get('/ajuanfaktur/create', 'create')->name('ajuanfaktur.create')->can('ajuanfaktur.create');
        Route::post('/ajuanfaktur/store', 'store')->name('ajuanfaktur.store')->can('ajuanfaktur.store');
        Route::delete('/ajuanfaktur/{no_pengajuan}/delete', 'destroy')->name('ajuanfaktur.delete')->can('ajuanfaktur.delete');
        Route::get('/ajuanfaktur/{no_pengajuan}/show', 'show')->name('ajuanfaktur.show')->can('ajuanfaktur.show');
        Route::get('/ajuanfaktur/{no_pengajuan}/edit', 'edit')->name('ajuanfaktur.edit')->can('ajuanfaktur.edit');
        Route::get('/ajuanfaktur/{no_pengajuan}/cetak', 'cetak')->name('ajuanfaktur.cetak')->can('ajuanfaktur.show');

        Route::get('/ajuanfaktur/{no_pengajuan}/approve', 'approve')->name('ajuanfaktur.approve')->can('ajuanfaktur.approve');
        Route::post('/ajuanfaktur/{no_pengajuan}/approvestore', 'approvestore')->name('ajuanfaktur.approvestore')->can('ajuanfaktur.approve');
        Route::delete('/ajuanfaktur/{no_pengajuan}/cancel', 'cancel')->name('ajuanfaktur.cancel')->can('ajuanfaktur.approve');
    });

    Route::controller(SetoranpenjualanController::class)->group(function () {
        Route::get('/setoranpenjualan', 'index')->name('setoranpenjualan.index')->can('setoranpenjualan.index');
        Route::get('/setoranpenjualan/create', 'create')->name('setoranpenjualan.create')->can('setoranpenjualan.create');
        Route::get('/setoranpenjualan/{kode_setoran}/edit', 'edit')->name('setoranpenjualan.edit')->can('setoranpenjualan.edit');
        Route::post('/setoranpenjualan/store', 'store')->name('setoranpenjualan.store')->can('setoranpenjualan.store');
        Route::put('/setoranpenjualan/{kode_setoran}/update', 'update')->name('setoranpenjualan.update')->can('setoranpenjualan.update');
        Route::delete('/setoranpenjualan/{kode_setoran}/delete', 'destroy')->name('setoranpenjualan.delete')->can('setoranpenjualan.delete');
        Route::get('/setoranpenjualan/cetak', 'cetak')->name('setoranpenjualan.cetak')->can('setoranpenjualan.show');

        //AJAX REQUEST
        Route::post('/setoranpenjualan/getlhp', 'getlhp')->name('setoranpenjualan.getlhp');
        Route::post('/setoranpenjualan/showlhp', 'showlhp')->name('setoranpenjualan.showlhp')->can('setoranpenjualan.show');
    });


    Route::controller(SetorantransferController::class)->group(function () {
        Route::get('/setorantransfer', 'index')->name('setorantransfer.index')->can('setorantransfer.index');
        Route::get('/setorantransfer/{kode_transfer}/create', 'create')->name('setorantransfer.create')->can('setorantransfer.create');
        Route::post('/setorantransfer/{kode_transfer}/store', 'store')->name('setorantransfer.store')->can('setorantransfer.store');
        Route::delete('/setorantransfer/{kode_setoran}/delete', 'destroy')->name('setorantransfer.delete')->can('setorantransfer.delete');
    });

    Route::controller(SetorangiroController::class)->group(function () {
        Route::get('/setorangiro', 'index')->name('setorangiro.index')->can('setorangiro.index');
        Route::get('/setorangiro/{kode_giro}/create', 'create')->name('setorangiro.create')->can('setorangiro.create');
        Route::post('/setorangiro/{kode_giro}/store', 'store')->name('setorangiro.store')->can('setorangiro.store');
        Route::delete('/setorangiro/{kode_setoran}/delete', 'destroy')->name('setorangiro.delete')->can('setorangiro.delete');
    });

    Route::controller(SetoranpusatController::class)->group(function () {
        Route::get('/setoranpusat', 'index')->name('setoranpusat.index')->can('setoranpusat.index');
        Route::get('/setoranpusat/create', 'create')->name('setoranpusat.create')->can('setoranpusat.create');
        Route::post('/setoranpusat/store', 'store')->name('setoranpusat.store')->can('setoranpusat.store');
        Route::get('/setoranpusat/{kode_setoran}/edit', 'edit')->name('setoranpusat.edit')->can('setoranpusat.edit');
        Route::put('/setoranpusat/{kode_setoran}/update', 'update')->name('setoranpusat.update')->can('setoranpusat.update');
        Route::get('/setoranpusat/{kode_setoran}/approve', 'approve')->name('setoranpusat.approve')->can('setoranpusat.approve');
        Route::post('/setoranpusat/{kode_setoran}/approvestore', 'approvestore')->name('setoranpusat.approvestore')->can('setoranpusat.approve');
        Route::delete('/setoranpusat/{kode_setoran}/cancel', 'cancel')->name('setoranpusat.cancel')->can('setoranpusat.approve');
        Route::delete('/setoranpusat/{kode_setoran}/delete', 'destroy')->name('setoranpusat.delete')->can('setoranpusat.delete');
    });

    Route::controller(LogamtokertasController::class)->group(function () {
        Route::get('/logamtokertas', 'index')->name('logamtokertas.index')->can('logamtokertas.index');
        Route::get('/logamtokertas/create', 'create')->name('logamtokertas.create')->can('logamtokertas.create');
        Route::post('/logamtokertas/store', 'store')->name('logamtokertas.store')->can('logamtokertas.store');
        Route::get('/logamtokertas/{kode_logamtokertas}/edit', 'edit')->name('logamtokertas.edit')->can('logamtokertas.edit');
        Route::put('/logamtokertas/{kode_logamtokertas}/update', 'update')->name('logamtokertas.update')->can('logamtokertas.update');
        Route::delete('/logamtokertas/{kode_logamtokertas}/delete', 'destroy')->name('logamtokertas.delete')->can('logamtokertas.delete');
    });


    Route::controller(SaldoawalkasbesarController::class)->group(function () {
        Route::get('/sakasbesar', 'index')->name('sakasbesar.index')->can('sakasbesar.index');
        Route::get('/sakasbesar/create', 'create')->name('sakasbesar.create')->can('sakasbesar.create');
        Route::post('/sakasbesar/store', 'store')->name('sakasbesar.store')->can('sakasbesar.store');
        Route::get('/sakasbesar/{kode_saldo_awal}/edit', 'edit')->name('sakasbesar.edit')->can('sakasbesar.edit');
        Route::put('/sakasbesar/{kode_saldo_awal}/update', 'update')->name('sakasbesar.update')->can('sakasbesar.update');
        Route::delete('/sakasbesar/{kode_saldo_awal}/delete', 'destroy')->name('sakasbesar.delete')->can('sakasbesar.delete');

        Route::post('/sakasbesar/getsaldo', 'getsaldo')->name('sakasbesar.getsaldo');
    });

    Route::controller(AjuantransferdanaController::class)->group(function () {
        Route::get('/ajuantransfer', 'index')->name('ajuantransfer.index')->can('ajuantransfer.index');
        Route::get('/ajuantransfer/create', 'create')->name('ajuantransfer.create')->can('ajuantransfer.create');
        Route::post('/ajuantransfer/store', 'store')->name('ajuantransfer.store')->can('ajuantransfer.store');
        Route::get('/ajuantransfer/{no_pengajuan}/edit', 'edit')->name('ajuantransfer.edit')->can('ajuantransfer.edit');
        Route::put('/ajuantransfer/{no_pengajuan}/update', 'update')->name('ajuantransfer.update')->can('ajuantransfer.update');
        Route::get('/ajuantransfer/{no_pengajuan}/approve', 'approve')->name('ajuantransfer.approve')->can('ajuantransfer.approve');
        Route::get('/ajuantransfer/{no_pengajuan}/cancelapprove', 'cancelApprove')->name('ajuantransfer.cancelapprove')->can('ajuantransfer.approve');
        Route::get('/ajuantransfer/{no_pengajuan}/proses', 'proses')->name('ajuantransfer.proses')->can('ajuantransfer.proses');
        Route::post('/ajuantransfer/{no_pengajuan}/prosesstore', 'prosesstore')->name('ajuantransfer.prosesstore')->can('ajuantransfer.proses');
        Route::delete('/ajuantransfer/{no_pengajuan}/cancelproses', 'cancelProses')->name('ajuantransfer.cancelproses')->can('ajuantransfer.proses');
        // Route::post('/ajuantransfer/{no_pengajuan}/cancelproses', 'cancelProses')->name('ajuantransfer.cancelproses')->can('ajuantransfer.proses');
        Route::delete('/ajuantransfer/{no_pengajuan}/delete', 'delete')->name('ajuantransfer.delete')->can('ajuantransfer.delete');
        Route::get('/ajuantransfer/cetak', 'cetak')->name('ajuantransfer.cetak')->can('ajuantransfer.show');
    });

    Route::controller(KaskecilController::class)->group(function () {
        Route::get('/kaskecil', 'index')->name('kaskecil.index')->can('kaskecil.index');
        Route::get('/kaskecil/create', 'create')->name('kaskecil.create')->can('kaskecil.create');
        Route::post('/kaskecil/store', 'store')->name('kaskecil.store')->can('kaskecil.store');
        Route::get('/kaskecil/{id}/edit', 'edit')->name('kaskecil.edit')->can('kaskecil.edit');
        Route::put('/kaskecil/{id}/update', 'update')->name('kaskecil.update')->can('kaskecil.update');
        Route::delete('/kaskecil/{id}/delete', 'destroy')->name('kaskecil.delete')->can('kaskecil.delete');
    });

    Route::controller(KlaimkaskecilController::class)->group(function () {
        Route::get('/klaimkaskecil', 'index')->name('klaimkaskecil.index')->can('klaimkaskecil.index');
        Route::get('/klaimkaskecil/create', 'create')->name('klaimkaskecil.create')->can('klaimkaskecil.create');
        Route::post('/klaimkaskecil/store', 'store')->name('klaimkaskecil.store')->can('klaimkaskecil.store');
        Route::get('/klaimkaskecil/{kode_klaim}/edit', 'edit')->name('klaimkaskecil.edit')->can('klaimkaskecil.edit');
        Route::put('/klaimkaskecil/{kode_klaim}/update', 'update')->name('klaimkaskecil.update')->can('klaimkaskecil.update');
        Route::delete('/klaimkaskecil/{kode_klaim}/delete', 'destroy')->name('klaimkaskecil.delete')->can('klaimkaskecil.delete');
        Route::get('/klaimkaskecil/{kode_klaim}/show', 'show')->name('klaimkaskecil.show')->can('klaimkaskecil.show');
        Route::get('/klaimkaskecil/{kode_klaim}/{export}/cetak', 'cetak')->name('klaimkaskecil.cetak')->can('klaimkaskecil.show');
        Route::get('/klaimkaskecil/{kode_klaim}/proses', 'proses')->name('klaimkaskecil.proses')->can('klaimkaskecil.proses');
        Route::post('/klaimkaskecil/{kode_klaim}/storeproses', 'storeproses')->name('klaimkaskecil.storeproses')->can('klaimkaskecil.proses');
        Route::delete('/klaimkaskecil/{no_bukti}/cancelproses', 'cancelproses')->name('klaimkaskecil.cancelproses')->can('klaimkaskecil.proses');

        Route::get('/klaimkaskecil/{kode_klaim}/approve', 'approve')->name('klaimkaskecil.approve')->can('klaimkaskecil.approve');
        Route::delete('/klaimkaskecil/{no_bukti}/cancelapprove', 'cancelapprove')->name('klaimkaskecil.cancelapprove')->can('klaimkaskecil.approve');

        Route::post('/klaimkaskecil/getdata', 'getData')->name('klaimkaskecil.getdata')->can('klaimkaskecil.create');
    });

    Route::controller(LedgerController::class)->group(function () {
        Route::get('/ledger', 'index')->name('ledger.index')->can('ledger.index');
        Route::get('/ledger/create', 'create')->name('ledger.create')->can('ledger.create');
        Route::post('/ledger/store', 'store')->name('ledger.store')->can('ledger.store');
        Route::get('/ledger/{no_bukti}/edit', 'edit')->name('ledger.edit')->can('ledger.edit');
        Route::put('/ledger/{no_bukti}/update', 'update')->name('ledger.update')->can('ledger.update');
        Route::delete('/ledger/{no_bukti}/delete', 'destroy')->name('ledger.delete')->can('ledger.delete');
    });

    Route::controller(SaldoawalledgerController::class)->group(function () {
        Route::get('/saledger', 'index')->name('saledger.index')->can('saledger.index');
        Route::get('/samutasibank', 'index')->name('samutasibank.index')->can('samutasibank.index');
        Route::get('/saledger/create', 'create')->name('saledger.create')->can('saledger.create');
        Route::post('/saledger/store', 'store')->name('saledger.store')->can('saledger.store');
        Route::get('/saledger/{no_bukti}/edit', 'edit')->name('saledger.edit')->can('saledger.edit');
        Route::put('/saledger/{no_bukti}/update', 'update')->name('saledger.update')->can('saledger.update');
        Route::delete('/saledger/{no_bukti}/delete', 'destroy')->name('saledger.delete')->can('saledger.delete');

        Route::post('/saledger/getsaldo', 'getsaldo')->name('saledger.getsaldo');
    });

    Route::controller(SaldoawalmutasikeuanganController::class)->group(function () {
        Route::get('/samutasikeuangan', 'index')->name('samutasikeuangan.index')->can('samutasikeuangan.index');
        Route::get('/samutasikeuangan/create', 'create')->name('samutasikeuangan.create')->can('samutasikeuangan.create');
        Route::post('/samutasikeuangan/store', 'store')->name('samutasikeuangan.store')->can('samutasikeuangan.store');
        Route::get('/samutasikeuangan/{no_bukti}/edit', 'edit')->name('samutasikeuangan.edit')->can('samutasikeuangan.edit');
        Route::put('/samutasikeuangan/{no_bukti}/update', 'update')->name('samutasikeuangan.update')->can('samutasikeuangan.update');
        Route::delete('/samutasikeuangan/{no_bukti}/delete', 'destroy')->name('samutasikeuangan.delete')->can('samutasikeuangan.delete');

        Route::post('/samutasikeuangan/getsaldo', 'getsaldo')->name('samutasikeuangan.getsaldo');
    });

    Route::controller(SaldokasbesarkeuanganController::class)->group(function () {
        Route::get('/sakasbesarkeuangan', 'index')->name('sakasbesarkeuangan.index')->can('sakasbesarkeuangan.index');
        Route::get('/sakasbesarkeuanganpusat', 'index')->name('sakasbesarkeuanganpusat.index')->can('sakasbesarkeuangan.index');
        Route::get('/sakasbesarkeuangan/create', 'create')->name('sakasbesarkeuangan.create')->can('sakasbesarkeuangan.create');
        Route::get('/sakasbesarkeuangan/createpusat', 'create')->name('sakasbesarkeuangan.create')->can('sakasbesarkeuangan.create');
        Route::post('/sakasbesarkeuangan/store', 'store')->name('sakasbesarkeuangan.store')->can('sakasbesarkeuangan.store');
        Route::get('/sakasbesarkeuangan/{id}/edit', 'edit')->name('sakasbesarkeuangan.edit')->can('sakasbesarkeuangan.edit');
        Route::put('/sakasbesarkeuangan/{id}/update', 'update')->name('sakasbesarkeuangan.update')->can('sakasbesarkeuangan.update');
        Route::delete('/sakasbesarkeuangan/{id}/delete', 'destroy')->name('sakasbesarkeuangan.delete')->can('sakasbesarkeuangan.delete');
    });


    Route::controller(MutasibankController::class)->group(function () {
        Route::get('/mutasibank', 'index')->name('mutasibank.index')->can('mutasibank.index');
        Route::get('/mutasibank/create', 'create')->name('mutasibank.create')->can('mutasibank.create');
        Route::post('/mutasibank/store', 'store')->name('mutasibank.store')->can('mutasibank.store');
        Route::get('/mutasibank/{no_bukti}/edit', 'edit')->name('mutasibank.edit')->can('mutasibank.edit');
        Route::put('/mutasibank/{no_bukti}/update', 'update')->name('mutasibank.update')->can('mutasibank.update');
        Route::delete('/mutasibank/{no_bukti}/delete', 'destroy')->name('mutasibank.delete')->can('mutasibank.delete');
    });

    Route::controller(PjpController::class)->group(function () {
        Route::get('/pjp', 'index')->name('pjp.index')->can('pjp.index');
        Route::get('/pjp/create', 'create')->name('pjp.create')->can('pjp.create');
        Route::post('/pjp/store', 'store')->name('pjp.store')->can('pjp.store');
        Route::get('/pjp/{no_pinjaman}/show', 'show')->name('pjp.show')->can('pjp.show');
        Route::get('/pjp/{no_pinjaman}/edit', 'edit')->name('pjp.edit')->can('pjp.edit');
        Route::put('/pjp/{no_pinjaman}/update', 'update')->name('pjp.update')->can('pjp.update');
        Route::delete('/pjp/{no_pinjaman}/delete', 'destroy')->name('pjp.delete')->can('pjp.delete');
        Route::get('/pjp/{no_pinjaman}/cetak', 'cetak')->name('pjp.cetak')->can('pjp.show');
        Route::post('/pjp/getrencanacicilan', 'getrencanacicilan')->name('pjp.getrencanacicilan');
        Route::get('/pjp/{no_pinjaman}/approve', 'approve')->name('pjp.approve')->can('pjp.approve');
        Route::post('/pjp/{no_pinjaman}/approvestore', 'approvestore')->name('pjp.approvestore')->can('pjp.approve');
        Route::delete('/pjp/{no_pinjaman}/cancel', 'cancel')->name('pjp.cancel')->can('pjp.approve');
    });

    Route::controller(PembayaranpjpController::class)->group(function () {
        Route::get('/pembayaranpjp', 'index')->name('pembayaranpjp.index')->can('pembayaranpjp.index');
        Route::get('/pembayaranpjp/{no_pinjman}/create', 'create')->name('pembayaranpjp.create')->can('pembayaranpjp.create');
        Route::get('/pembayaranpjp/{kode_potongan}/{export}/show', 'show')->name('pembayaranpjp.show')->can('pembayaranpjp.show');
        Route::get('/pembayaranpjp/create', 'creategenerate')->name('pembayaranpjp.creategenerate')->can('pembayaranpjp.create');
        Route::post('/pembayaranpjp/store', 'store')->name('pembayaranpjp.store')->can('pembayaranpjp.store');
        Route::post('/pembayaranpjp/generatepjp', 'generatepjp')->name('pembayaranpjp.generatepjp')->can('pembayaranpjp.store');
        Route::delete('/pembayaranpjp/{kode_potongan}/deletegenerate', 'destroygenerate')->name('pembayaranpjp.deletegenerate')->can('pembayaranpjp.delete');


        // Route::put('/pembayaranpjp/{id}/update', 'update')->name('pembayaranpjp.update')->can('pembayaranpjp.update');
        Route::post('/pembayaranpjp/delete', 'destroy')->name('pembayaranpjp.delete')->can('pembayaranpjp.delete');
        Route::post('/pembayaranpjp/gethistoribayar', 'gethistoribayar')->name('pembayaranpjp.gethistoribayar');
    });

    Route::controller(KasbonController::class)->group(function () {
        Route::get('/kasbon', 'index')->name('kasbon.index')->can('kasbon.index');
        Route::get('/kasbon/create', 'create')->name('kasbon.create')->can('kasbon.create');
        Route::post('/kasbon/store', 'store')->name('kasbon.store')->can('kasbon.store');
        Route::get('/kasbon/{no_kasbon}/edit', 'edit')->name('kasbon.edit')->can('kasbon.edit');
        Route::get('/kasbon/{no_kasbon}/cetak', 'cetak')->name('kasbon.cetak')->can('kasbon.show');
        Route::put('/kasbon/{no_kasbon}/update', 'update')->name('kasbon.update')->can('kasbon.update');
        Route::delete('/kasbon/{no_kasbon}/delete', 'destroy')->name('kasbon.delete')->can('kasbon.delete');
        Route::get('/kasbon/{no_kasbon}/approve', 'approve')->name('kasbon.approve')->can('kasbon.approve');
        Route::post('/kasbon/{no_kasbon}/approvestore', 'approvestore')->name('kasbon.approvestore')->can('kasbon.approve');
        Route::delete('/kasbon/{no_kasbon}/cancel', 'cancel')->name('kasbon.cancel')->can('kasbon.approve');
    });



    Route::controller(PembayarankasbonController::class)->group(function () {
        Route::get('/pembayarankasbon', 'index')->name('pembayarankasbon.index')->can('pembayarankasbon.index');
        Route::get('/pembayarankasbon/{no_pinjman}/create', 'create')->name('pembayarankasbon.create')->can('pembayarankasbon.create');
        Route::get('/pembayarankasbon/{kode_potongan}/{export}/show', 'show')->name('pembayarankasbon.show')->can('pembayarankasbon.show');
        Route::get('/pembayarankasbon/create', 'creategenerate')->name('pembayarankasbon.creategenerate')->can('pembayarankasbon.create');
        Route::post('/pembayarankasbon/store', 'store')->name('pembayarankasbon.store')->can('pembayarankasbon.store');
        Route::post('/pembayarankasbon/generatekasbon', 'generatekasbon')->name('pembayarankasbon.generatekasbon')->can('pembayarankasbon.store');
        Route::delete('/pembayarankasbon/{kode_potongan}/deletegenerate', 'destroygenerate')->name('pembayarankasbon.deletegenerate')->can('pembayarankasbon.delete');


        // Route::put('/pembayarankasbon/{id}/update', 'update')->name('pembayarankasbon.update')->can('pembayarankasbon.update');
        Route::post('/pembayarankasbon/delete', 'destroy')->name('pembayarankasbon.delete')->can('pembayarankasbon.delete');
        Route::post('/pembayarankasbon/gethistoribayar', 'gethistoribayar')->name('pembayarankasbon.gethistoribayar');
    });


    Route::controller(PiutangkaryawanController::class)->group(function () {
        Route::get('/piutangkaryawan', 'index')->name('piutangkaryawan.index')->can('piutangkaryawan.index');
        Route::get('/piutangkaryawan/create', 'create')->name('piutangkaryawan.create')->can('piutangkaryawan.create');
        Route::post('/piutangkaryawan/store', 'store')->name('piutangkaryawan.store')->can('piutangkaryawan.store');
        Route::delete('/piutangkaryawan/{no_pinjaman}/delete', 'destroy')->name('piutangkaryawan.delete')->can('piutangkaryawan.delete');
        Route::get('/piutangkaryawan/{no_pinjaman}/show', 'show')->name('piutangkaryawan.show')->can('piutangkaryawan.show');
        Route::get('/piutangkaryawan/{no_pinjaman}/getpiutangkaryawan', 'getpiutangkaryawan')->name('piutangkaryawan.getpiutangkaryawan')->can('piutangkaryawan.show');
    });

    Route::controller(PembayaranpiutangkaryawanController::class)->group(function () {
        Route::get('/pembayaranpiutangkaryawan', 'index')->name('pembayaranpiutangkaryawan.index')->can('pembayaranpk.index');
        Route::get('/pembayaranpiutangkaryawan/{no_pinjaman}/create', 'create')->name('pembayaranpiutangkaryawan.create')->can('pembayaranpk.create');
        Route::post('/pembayaranpiutangkaryawan/store', 'store')->name('pembayaranpiutangkaryawan.store')->can('pembayaranpk.store');
        Route::get('/pembayaranpiutangkaryawan/{no_pinjaman}/show', 'show')->name('pembayaranpiutangkaryawan.show')->can('pembayaranpk.show');
        Route::post('/pembayaranpiutangkaryawan/delete', 'destroy')->name('pembayaranpiutangkaryawan.delete')->can('pembayaranpk.delete');
        Route::post('/pembayaranpiutangkaryawan/gethistoribayar', 'gethistoribayar')->name('pembayaranpiutangkaryawan.gethistoribayar');
    });

    Route::controller(PembelianController::class)->group(function () {
        Route::get('/pembelian', 'index')->name('pembelian.index')->can('pembelian.index');
        Route::get('/pembelian/create', 'create')->name('pembelian.create')->can('pembelian.create');
        Route::get('/pembelian/{no_bukti}/show', 'show')->name('pembelian.show')->can('pembelian.show');
        Route::get('/pembelian/{no_bukti}/approvegdl', 'approvegdl')->name('pembelian.approvegdl')->can('pembelian.approvegdl');
        Route::post('/pembelian/{no_bukti}/storeapprovegdl', 'storeapprovegdl')->name('pembelian.storeapprovegdl')->can('pembelian.approvegdl');
        Route::delete('/pembelian/{no_bukti}/cancelapprovegdl', 'cancelapprovegdl')->name('pembelian.cancelapprovegdl')->can('pembelian.approvegdl');
        Route::get('/pembelian/{no_bukti}/approvemtc', 'approvemtc')->name('pembelian.approvemtc')->can('pembelian.approvemtc');
        Route::post('/pembelian/{no_bukti}/storeapprovemtc', 'storeapprovemtc')->name('pembelian.storeapprovemtc')->can('pembelian.approvemtc');
        Route::delete('/pembelian/{no_bukti}/cancelapprovemtc', 'cancelapprovemtc')->name('pembelian.cancelapprovemtc')->can('pembelian.approvemtc');
        Route::get('/pembelian/{no_bukti}/edit', 'edit')->name('pembelian.edit')->can('pembelian.edit');
        Route::put('/pembelian/{no_bukti}/update', 'update')->name('pembelian.update')->can('pembelian.update');
        Route::get('/pembelian/createpotongan', 'createpotongan')->name('pembelian.createpotongan')->can('pembelian.edit');
        Route::post('/pembelian/store', 'store')->name('pembelian.store')->can('pembelian.store');
        Route::delete('/pembelian/{id}/delete', 'destroy')->name('pembelian.delete')->can('pembelian.delete');
        Route::get('/pembelian/jatuhtempo', 'jatuhtempo')->name('pembelian.jatuhtempo')->can('pembelian.jatuhtempo');

        Route::post('/pembelian/editbarang', 'editbarang')->name('pembelian.editbarang')->can('pembelian.edit');
        Route::post('/pembelian/splitbarang', 'splitbarang')->name('pembelian.splitbarang')->can('pembelian.edit');
        Route::get('/pembelian/{kode_supplier}/getpembelianbysupplier', 'getpembelianbysupplier')->name('pembelian.getpembelianbysupplier');
        Route::get('/pembelian/{kode_supplier}/getpembelianbysupplierjson', 'getpembelianbysupplierjson')->name('pembelian.getpembelianbysupplierjson');
        Route::post('/pembelian/getbarangpembelian', 'getbarangpembelian')->name('pembelian.getbarangpembelian');
    });

    Route::controller(JurnalkoreksiController::class)->group(function () {
        Route::get('/jurnalkoreksi', 'index')->name('jurnalkoreksi.index')->can('jurnalkoreksi.index');
        Route::get('/jurnalkoreksi/create', 'create')->name('jurnalkoreksi.create')->can('jurnalkoreksi.create');
        Route::post('/jurnalkoreksi/store', 'store')->name('jurnalkoreksi.store')->can('jurnalkoreksi.store');
        Route::delete('/jurnalkoreksi/{kode_jurnalkoreksi}/delete', 'destroy')->name('jurnalkoreksi.delete')->can('jurnalkoreksi.delete');
    });

    Route::controller(KontrabonpembelianController::class)->group(function () {
        Route::get('/kontrabonpembelian', 'index')->name('kontrabonpmb.index')->can('kontrabonpmb.index');
        Route::get('/kontrabonpembelian/create', 'create')->name('kontrabonpmb.create')->can('kontrabonpmb.create');
        Route::post('/kontrabonpembelian/store', 'store')->name('kontrabonpmb.store')->can('kontrabonpmb.store');
        Route::get('/kontrabonpembelian/{no_kontrabon}/show', 'show')->name('kontrabonpmb.show')->can('kontrabonpmb.show');
        Route::get('/kontrabonpembelian/{no_kontrabon}/cetak', 'cetak')->name('kontrabonpmb.cetak')->can('kontrabonpmb.show');
        Route::get('/kontrabonpembelian/{no_kontrabon}/edit', 'edit')->name('kontrabonpmb.edit')->can('kontrabonpmb.edit');
        Route::put('/kontrabonpembelian/{no_kontrabon}/update', 'update')->name('kontrabonpmb.update')->can('kontrabonpmb.update');
        Route::delete('/kontrabonpembelian/{no_kontrabon}/delete', 'destroy')->name('kontrabonpmb.delete')->can('kontrabonpmb.delete');
        Route::get('/kontrabonpembelian/{no_kontrabon}/approve', 'approve')->name('kontrabonpmb.approve')->can('kontrabonpmb.approve');
        Route::get('/kontrabonpembelian/{no_kontrabon}/cancel', 'cancel')->name('kontrabonpmb.cancel')->can('kontrabonpmb.approve');
        Route::get('/kontrabonpembelian/{no_kontrabon}/proses', 'proses')->name('kontrabonpmb.proses')->can('kontrabonpmb.proses');
        Route::post('/kontrabonpembelian/{no_kontrabon}/storeproses', 'storeproses')->name('kontrabonpmb.storeproses')->can('kontrabonpmb.proses');
        Route::delete('/kontrabonpembelian/{no_kontrabon}/cancelproses', 'cancelproses')->name('kontrabonpmb.cancelproses')->can('kontrabonpmb.proses');

        Route::get('/kontrabonkeuangan/pembelian', 'index')->name('kontrabonkeuangan.pembelian')->can('kontrabonpmb.index');
    });

    Route::controller(KontrabonangkutanController::class)->group(function () {
        Route::get('/kontrabonangkutan', 'index')->name('kontrabonangkutan.index')->can('kontrabonangkutan.index');
        Route::get('/kontrabonangkutan/create', 'create')->name('kontrabonangkutan.create')->can('kontrabonangkutan.create');
        Route::post('/kontrabonangkutan/store', 'store')->name('kontrabonangkutan.store')->can('kontrabonangkutan.store');
        Route::get('/kontrabonangkutan/{no_kontrabon}/show', 'show')->name('kontrabonangkutan.show')->can('kontrabonangkutan.show');
        Route::get('/kontrabonangkutan/{no_kontrabon}/edit', 'edit')->name('kontrabonangkutan.edit')->can('kontrabonangkutan.edit');
        Route::put('/kontrabonangkutan/{no_kontrabon}/update', 'update')->name('kontrabonangkutan.update')->can('kontrabonangkutan.update');
        Route::delete('/kontrabonangkutan/{no_kontrabon}/delete', 'destroy')->name('kontrabonangkutan.delete')->can('kontrabonangkutan.delete');
        Route::get('/kontrabonangkutan/{no_kontrabon}/proses', 'proses')->name('kontrabonangkutan.proses')->can('kontrabonangkutan.proses');
        Route::post('/kontrabonangkutan/{no_kontrabon}/storeproses', 'storeproses')->name('kontrabonangkutan.storeproses')->can('kontrabonangkutan.proses');
        Route::delete('/kontrabonangkutan/{no_kontrabon}/cancelproses', 'cancelproses')->name('kontrabonangkutan.cancelproses')->can('kontrabonangkutan.proses');
        Route::get('/kontrabonkeuangan/angkutan', 'index')->name('kontrabonkeuangan.angkutan')->can('kontrabonangkutan.index');
    });

    // Route::controller(KontrabonkeuanganController::class)->group(function () {
    //     Route::get('/kontrabonkeuangan/pembelian', 'pembelian')->name('kontrabonkeuangan.pembelian')->can('kontrabonpembelian.index');
    // });

    Route::controller(CoaController::class)->group(function () {
        Route::get('/coa', 'index')->name('coa.index')->can('coa.index');
        Route::get('/coa/create', 'create')->name('coa.create')->can('coa.create');
        Route::post('/coa/store', 'store')->name('coa.store')->can('coa.store');
        Route::get('/coa/{kode_akun}/edit', 'edit')->name('coa.edit')->can('coa.edit');
        Route::put('/coa/{kode_akun}/update', 'update')->name('coa.update')->can('coa.update');
        Route::delete('/coa/{kode_akun}/delete', 'destroy')->name('coa.delete')->can('coa.delete');
    });

    Route::controller(CostratioController::class)->group(function () {
        Route::get('/costratio', 'index')->name('costratio.index')->can('costratio.index');
        Route::get('/costratio/create', 'create')->name('costratio.create')->can('costratio.create');
        Route::post('/costratio/store', 'store')->name('costratio.store')->can('costratio.store');
        Route::get('/costratio/{id}/edit', 'edit')->name('costratio.edit')->can('costratio.edit');
        Route::put('/costratio/{id}/update', 'update')->name('costratio.update')->can('costratio.update');
        Route::delete('/costratio/{id}/delete', 'destroy')->name('costratio.delete')->can('costratio.delete');
        Route::get('/costratio/cetak', 'cetak')->name('costratio.cetak')->can('costratio.index');
    });

    Route::controller(JurnalumumController::class)->group(function () {
        Route::get('/jurnalumum', 'index')->name('jurnalumum.index')->can('jurnalumum.index');
        Route::get('/jurnalumum/create', 'create')->name('jurnalumum.create')->can('jurnalumum.create');
        Route::post('/jurnalumum/store', 'store')->name('jurnalumum.store')->can('jurnalumum.store');
        Route::get('/jurnalumum/{id}/show', 'show')->name('jurnalumum.show')->can('jurnalumum.show');
        Route::get('/jurnalumum/{id}/edit', 'edit')->name('jurnalumum.edit')->can('jurnalumum.edit');
        Route::put('/jurnalumum/{id}/update', 'update')->name('jurnalumum.update')->can('jurnalumum.update');
        Route::delete('/jurnalumum/{id}/delete', 'destroy')->name('jurnalumum.delete')->can('jurnalumum.delete');
    });


    Route::controller(HppController::class)->group(function () {
        Route::get('/hpp', 'index')->name('hpp.index')->can('hpp.index');
        Route::get('/hpp/create', 'create')->name('hpp.create')->can('hpp.create');
        Route::post('/hpp/store', 'store')->name('hpp.store')->can('hpp.store');
        Route::get('/hpp/{kode_hpp}/show', 'show')->name('hpp.show')->can('hpp.show');
        Route::get('/hpp/{id}/edit', 'edit')->name('hpp.edit')->can('hpp.edit');
        Route::put('/hpp/{id}/update', 'update')->name('hpp.update')->can('hpp.update');
        Route::delete('/hpp/{id}/delete', 'destroy')->name('hpp.delete')->can('hpp.delete');
    });


    Route::controller(HargaawalhppController::class)->group(function () {
        Route::get('/hargaawalhpp', 'index')->name('hargaawalhpp.index')->can('hargaawalhpp.index');
        Route::get('/hargaawalhpp/create', 'create')->name('hargaawalhpp.create')->can('hargaawalhpp.create');
        Route::post('/hargaawalhpp/store', 'store')->name('hargaawalhpp.store')->can('hargaawalhpp.store');
        Route::get('/hargaawalhpp/{id}/show', 'show')->name('hargaawalhpp.show')->can('hargaawalhpp.show');
        Route::get('/hargaawalhpp/{id}/edit', 'edit')->name('hargaawalhpp.edit')->can('hargaawalhpp.edit');
        Route::put('/hargaawalhpp/{id}/update', 'update')->name('hargaawalhpp.update')->can('hargaawalhpp.update');
        Route::delete('/hargaawalhpp/{id}/delete', 'destroy')->name('hargaawalhpp.delete')->can('hargaawalhpp.delete');

        Route::post('/hargaawalhpp/gethargaawal', 'gethargaawal')->name('hargaawalhpp.gethargaawal')->can('hargaawalhpp.create');
    });


    //Maintenance
    Route::controller(BarangmasukmaintenanceController::class)->group(function () {
        Route::get('/barangmasukmaintenance', 'index')->name('barangmasukmtc.index')->can('barangmasukmtc.index');
        Route::get('/barangmasukmaintenance/create', 'create')->name('barangmasukmtc.create')->can('barangmasukmtc.create');
        Route::post('/barangmasukmaintenance/store', 'store')->name('barangmasukmtc.store')->can('barangmasukmtc.store');
        Route::get('/barangmasukmaintenance/{id}/show', 'show')->name('barangmasukmtc.show')->can('barangmasukmtc.show');
        Route::get('/barangmasukmaintenance/{id}/edit', 'edit')->name('barangmasukmtc.edit')->can('barangmasukmtc.edit');
        Route::put('/barangmasukmaintenance/{id}/update', 'update')->name('barangmasukmtc.update')->can('barangmasukmtc.update');
        Route::delete('/barangmasukmaintenance/{id}/delete', 'destroy')->name('barangmasukmtc.delete')->can('barangmasukmtc.delete');
    });


    Route::controller(BarangkeluarmaintenanceController::class)->group(function () {
        Route::get('/barangkeluarmaintenance', 'index')->name('barangkeluarmtc.index')->can('barangkeluarmtc.index');
        Route::get('/barangkeluarmaintenance/create', 'create')->name('barangkeluarmtc.create')->can('barangkeluarmtc.create');
        Route::post('/barangkeluarmaintenance/store', 'store')->name('barangkeluarmtc.store')->can('barangkeluarmtc.store');
        Route::get('/barangkeluarmaintenance/{id}/show', 'show')->name('barangkeluarmtc.show')->can('barangkeluarmtc.show');
        Route::get('/barangkeluarmaintenance/{id}/edit', 'edit')->name('barangkeluarmtc.edit')->can('barangkeluarmtc.edit');
        Route::put('/barangkeluarmaintenance/{id}/update', 'update')->name('barangkeluarmtc.update')->can('barangkeluarmtc.update');
        Route::delete('/barangkeluarmaintenance/{id}/delete', 'destroy')->name('barangkeluarmtc.delete')->can('barangkeluarmtc.delete');
    });


    Route::controller(MutasikendaraanController::class)->group(function () {
        Route::get('/mutasikendaraan', 'index')->name('mutasikendaraan.index')->can('mutasikendaraan.index');
        Route::get('/mutasikendaraan/create', 'create')->name('mutasikendaraan.create')->can('mutasikendaraan.create');
        Route::post('/mutasikendaraan/store', 'store')->name('mutasikendaraan.store')->can('mutasikendaraan.store');
        Route::get('/mutasikendaraan/{id}/show', 'show')->name('mutasikendaraan.show')->can('mutasikendaraan.show');
        Route::get('/mutasikendaraan/{id}/edit', 'edit')->name('mutasikendaraan.edit')->can('mutasikendaraan.edit');
        Route::put('/mutasikendaraan/{id}/update', 'update')->name('mutasikendaraan.update')->can('mutasikendaraan.update');
        Route::delete('/mutasikendaraan/{id}/delete', 'destroy')->name('mutasikendaraan.delete')->can('mutasikendaraan.delete');
    });


    Route::controller(ServicekendaraanController::class)->group(function () {
        Route::get('/servicekendaraan', 'index')->name('servicekendaraan.index')->can('servicekendaraan.index');
        Route::get('/servicekendaraan/create', 'create')->name('servicekendaraan.create')->can('servicekendaraan.create');
        Route::post('/servicekendaraan/store', 'store')->name('servicekendaraan.store')->can('servicekendaraan.store');
        Route::get('/servicekendaraan/{id}/show', 'show')->name('servicekendaraan.show')->can('servicekendaraan.show');
        Route::get('/servicekendaraan/{id}/edit', 'edit')->name('servicekendaraan.edit')->can('servicekendaraan.edit');
        Route::put('/servicekendaraan/{id}/update', 'update')->name('servicekendaraan.update')->can('servicekendaraan.update');
        Route::delete('/servicekendaraan/{id}/delete', 'destroy')->name('servicekendaraan.delete')->can('servicekendaraan.delete');
    });


    Route::controller(BengkelController::class)->group(function () {
        Route::get('/bengkel', 'index')->name('bengkel.index')->can('servicekendaraan.index');
        Route::get('/bengkel/create', 'create')->name('bengkel.create')->can('servicekendaraan.create');
        Route::post('/bengkel/store', 'store')->name('bengkel.store')->can('servicekendaraan.store');
        // Route::get('/bengkel/{id}/show', 'show')->name('bengkel.show')->can('servicekendaraan.show');
        // Route::get('/bengkel/{id}/edit', 'edit')->name('bengkel.edit')->can('servicekendaraan.edit');
        // Route::put('/bengkel/{id}/update', 'update')->name('bengkel.update')->can('servicekendaraan.update');
        // Route::delete('/bengkel/{id}/delete', 'destroy')->name('bengkel.delete')->can('servicekendaraan.delete');

        Route::get('/bengkel/getbengkel', 'getbengkel')->name('bengkel.getbengkel');
    });


    Route::controller(ItemservicekendaraanController::class)->group(function () {
        Route::get('/itemservicekendaraan', 'index')->name('itemservicekendaraan.index')->can('servicekendaraan.index');
        Route::get('/itemservicekendaraan/create', 'create')->name('itemservicekendaraan.create')->can('servicekendaraan.create');
        Route::post('/itemservicekendaraan/store', 'store')->name('itemservicekendaraan.store')->can('servicekendaraan.store');
        // Route::get('/itemservicekendaraan/{id}/show', 'show')->name('itemservicekendaraan.show')->can('itemservicekendaraan.show');
        // Route::get('/itemservicekendaraan/{id}/edit', 'edit')->name('itemservicekendaraan.edit')->can('itemservicekendaraan.edit');
        // Route::put('/itemservicekendaraan/{id}/update', 'update')->name('itemservicekendaraan.update')->can('itemservicekendaraan.update');
        // Route::delete('/itemservicekendaraan/{id}/delete', 'destroy')->name('itemservicekendaraan.delete')->can('itemservicekendaraan.delete');

        Route::get('/itemservicekendaraan/getitem', 'getitem')->name('itemservicekendaraan.getitem');
    });

    Route::controller(BadstokgaController::class)->group(function () {
        Route::get('/badstokga', 'index')->name('badstokga.index')->can('badstokga.index');
        Route::get('/badstokga/create', 'create')->name('badstokga.create')->can('badstokga.create');
        Route::post('/badstokga/store', 'store')->name('badstokga.store')->can('badstokga.store');
        Route::get('/badstokga/{id}/show', 'show')->name('badstokga.show')->can('badstokga.show');
        Route::get('/badstokga/{id}/edit', 'edit')->name('badstokga.edit')->can('badstokga.edit');
        Route::put('/badstokga/{id}/update', 'update')->name('badstokga.update')->can('badstokga.update');
        Route::delete('/badstokga/{id}/delete', 'destroy')->name('badstokga.delete')->can('badstokga.delete');
    });

    Route::controller(KontrakkaryawanController::class)->group(function () {
        Route::get('/kontrakkerja', 'index')->name('kontrakkerja.index')->can('kontrakkerja.index');
        Route::get('/kontrakkerja/create', 'create')->name('kontrakkerja.create')->can('kontrakkerja.create');
        Route::post('/kontrakkerja/store', 'store')->name('kontrakkerja.store')->can('kontrakkerja.store');
        Route::get('/kontrakkerja/{no_kontrak}/cetak', 'cetak')->name('kontrakkerja.cetak')->can('kontrakkerja.show');
        Route::get('/kontrakkerja/{no_kontrak}/edit', 'edit')->name('kontrakkerja.edit')->can('kontrakkerja.edit');
        Route::put('/kontrakkerja/{no_kontrak}/update', 'update')->name('kontrakkerja.update')->can('kontrakkerja.update');
        Route::delete('/kontrakkerja/{no_kontrak}/delete', 'destroy')->name('kontrakkerja.delete')->can('kontrakkerja.delete');

        Route::post('/kontrakkerja/getlastkontrak', 'getlastkontrak')->name('kontrakkerja.getlastkontrak');
    });


    Route::controller(SuratperingatanController::class)->group(function () {
        Route::get('/suratperingatan', 'index')->name('suratperingatan.index')->can('suratperingatan.index');
        Route::get('/suratperingatan/create', 'create')->name('suratperingatan.create')->can('suratperingatan.create');
        Route::post('/suratperingatan/store', 'store')->name('suratperingatan.store')->can('suratperingatan.store');
        Route::get('/suratperingatan/{id}/show', 'show')->name('suratperingatan.show')->can('suratperingatan.show');
        Route::get('/suratperingatan/{id}/edit', 'edit')->name('suratperingatan.edit')->can('suratperingatan.edit');
        Route::put('/suratperingatan/{id}/update', 'update')->name('suratperingatan.update')->can('suratperingatan.update');
        Route::delete('/suratperingatan/{id}/delete', 'destroy')->name('suratperingatan.delete')->can('suratperingatan.delete');
    });

    Route::controller(JasamasakerjaController::class)->group(function () {
        Route::get('/jasamasakerja', 'index')->name('jasamasakerja.index')->can('jasamasakerja.index');
        Route::get('/jasamasakerja/create', 'create')->name('jasamasakerja.create')->can('jasamasakerja.create');
        Route::post('/jasamasakerja/store', 'store')->name('jasamasakerja.store')->can('jasamasakerja.store');
        Route::get('/jasamasakerja/{kode_jmk}/show', 'show')->name('jasamasakerja.show')->can('jasamasakerja.show');
        Route::get('/jasamasakerja/{kode_jmk}/edit', 'edit')->name('jasamasakerja.edit')->can('jasamasakerja.edit');
        Route::put('/jasamasakerja/{kode_jmk}/update', 'update')->name('jasamasakerja.update')->can('jasamasakerja.update');
        Route::delete('/jasamasakerja/{kode_jmk}/delete', 'destroy')->name('jasamasakerja.delete')->can('jasamasakerja.delete');
    });

    Route::controller(ResignController::class)->group(function () {
        Route::get('/resign', 'index')->name('resign.index')->can('resign.index');
        Route::get('/resign/create', 'create')->name('resign.create')->can('resign.create');
        Route::post('/resign/store', 'store')->name('resign.store')->can('resign.store');
        Route::get('/resign/{kode_resign}/show', 'show')->name('resign.show')->can('resign.show');
        Route::get('/resign/{kode_resign}/cetak', 'cetak')->name('resign.cetak')->can('resign.show');
        Route::get('/resign/{kode_resign}/edit', 'edit')->name('resign.edit')->can('resign.edit');
        Route::put('/resign/{kode_resign}/update', 'update')->name('resign.update')->can('resign.update');
        Route::delete('/resign/{kode_resign}/delete', 'destroy')->name('resign.delete')->can('resign.delete');
    });



    Route::controller(KesepakatanbersamaController::class)->group(function () {
        Route::get('/kesepakatanbersama', 'index')->name('kesepakatanbersama.index')->can('kb.index');
        Route::get('/kesepakatanbersama/{kode_penilaian}/create', 'create')->name('kesepakatanbersama.create')->can('kb.create');
        Route::post('/kesepakatanbersama/{kode_penilaian}/store', 'store')->name('kesepakatanbersama.store')->can('kb.store');
        Route::get('/kesepakatanbersama/{no_kb}/show', 'show')->name('kesepakatanbersama.show')->can('kb.show');
        Route::get('/kesepakatanbersama/{no_kb}/cetak', 'cetak')->name('kesepakatanbersama.cetak')->can('kb.show');
        Route::get('/kesepakatanbersama/{no_kb}/potongan', 'potongan')->name('kesepakatanbersama.potongan')->can('kb.edit');
        Route::post('/kesepakatanbersama/{no_kb}/storepotongan', 'storepotongan')->name('kesepakatanbersama.storepotongan')->can('kb.edit');
        Route::put('/kesepakatanbersama/{no_kb}/update', 'update')->name('kesepakatanbersama.update')->can('kb.update');
        Route::delete('/kesepakatanbersama/{no_kb}/delete', 'destroy')->name('kesepakatanbersama.delete')->can('kb.delete');
        Route::get('/kesepakatanbersama/{kode_penilaian}/createkontrak', 'createkontrak')->name('kesepakatanbersama.createkontrak')->can('kontrakkerja.create');
        Route::post('/kesepakatanbersama/{kode_penilaian}/storekontrak', 'storekontrak')->name('kesepakatanbersama.storekontrak')->can('kontrakkerja.create');
    });

    Route::controller(PenilaiankaryawanController::class)->group(function () {
        Route::get('/penilaiankaryawan', 'index')->name('penilaiankaryawan.index')->can('penilaiankaryawan.index');
        Route::get('/penilaiankaryawan/create', 'create')->name('penilaiankaryawan.create')->can('penilaiankaryawan.create');
        Route::post('/penilaiankaryawan/createpenilaian', 'createpenilaian')->name('penilaiankaryawan.createpenilaian')->can('penilaiankaryawan.create');
        Route::post('/penilaiankaryawan/{no_kontrak}/store', 'store')->name('penilaiankaryawan.store')->can('penilaiankaryawan.store');
        Route::get('/penilaiankaryawan/{kode_penilaian}/cetak', 'cetak')->name('penilaiankaryawan.cetak')->can('penilaiankaryawan.show');
        Route::get('/penilaiankaryawan/{id}/edit', 'edit')->name('penilaiankaryawan.edit')->can('penilaiankaryawan.edit');
        Route::put('/penilaiankaryawan/{kode_penilaian}/update', 'update')->name('penilaiankaryawan.update')->can('penilaiankaryawan.update');
        Route::delete('/penilaiankaryawan/{id}/delete', 'destroy')->name('penilaiankaryawan.delete')->can('penilaiankaryawan.delete');

        Route::get('/penilaiankaryawan/{kode_penilaian}/approve', 'approve')->name('penilaiankaryawan.approve')->can('penilaiankaryawan.approve');
        Route::post('/penilaiankaryawan/{kode_penilaian}/storeapprove', 'storeapprove')->name('penilaiankaryawan.storeapprove')->can('penilaiankaryawan.approve');
        Route::delete('/penilaiankaryawan/{kode_penilaian}/cancel', 'cancel')->name('penilaiankaryawan.cancel')->can('penilaiankaryawan.approve');
    });

    Route::controller(JadwalshiftController::class)->group(function () {
        Route::get('/jadwalshift', 'index')->name('jadwalshift.index')->can('jadwalshift.index');
        Route::get('/jadwalshift/create', 'create')->name('jadwalshift.create')->can('jadwalshift.create');
        Route::post('/jadwalshift/store', 'store')->name('jadwalshift.store')->can('jadwalshift.store');
        Route::get('/jadwalshift/{kode_jadwalshift}/show', 'show')->name('jadwalshift.show')->can('jadwalshift.show');
        Route::get('/jadwalshift/{kode_jadwalshift}/edit', 'edit')->name('jadwalshift.edit')->can('jadwalshift.edit');
        Route::put('/jadwalshift/{kode_jadwalshift}/update', 'update')->name('jadwalshift.update')->can('jadwalshift.update');
        Route::delete('/jadwalshift/{kode_jadwalshift}/delete', 'destroy')->name('jadwalshift.delete')->can('jadwalshift.delete');

        Route::get('/jadwalshift/{kode_jadwalshift}/aturjadwal', 'aturjadwal')->name('jadwalshift.aturjadwal')->can('jadwalshift.setjadwal');
        Route::post('/jadwalshift/getshift', 'getshift')->name('jadwalshift.getshift');
        Route::get('/jadwalshift/{shift}/{kode_jadwalshift}/aturshift', 'aturshift')->name('jadwalshift.aturshift');
        Route::get('/jadwalshift/{kode_group}/{kode_jadwalshift}/getgroup', 'getgroup')->name('jadwalshift.getgroup');
        Route::post('/jadwalshift/updatejadwal', 'updatejadwal')->name('jadwalshift.updatejadwal');
        Route::post('/jadwalshift/tambahkansemua', 'tambahkansemua')->name('jadwalshift.tambahkansemua');
        Route::post('/jadwalshift/batalkansemua', 'batalkansemua')->name('jadwalshift.batalkansemua');
        Route::post('/jadwalshift/deleteshift', 'deleteshift')->name('jadwalshift.deleteshift');

        //Ganti Shift
        Route::get('/jadwalshift/{kode_jadwalshift}/gantishift', 'gantishift')->name('jadwalshift.gantishift');
        Route::get('/jadwalshift/{kode_jadwalshift}/getgantishift', 'getgantishift')->name('jadwalshift.getgantishift');
        Route::post('/jadwalshift/storegantishift', 'storegantishift')->name('jadwalshift.storegantishift');
        Route::post('/jadwalshift/deletegantishift', 'deletegantishift')->name('jadwalshift.deletegantishift');
    });

    Route::controller(HariliburController::class)->group(function () {
        Route::get('/harilibur', 'index')->name('harilibur.index')->can('harilibur.index');
        Route::get('/harilibur/create', 'create')->name('harilibur.create')->can('harilibur.create');
        Route::post('/harilibur/store', 'store')->name('harilibur.store')->can('harilibur.store');
        Route::get('/harilibur/{kode_libur}/show', 'show')->name('harilibur.show')->can('harilibur.show');
        Route::get('/harilibur/{kode_libur}/edit', 'edit')->name('harilibur.edit')->can('harilibur.edit');
        Route::put('/harilibur/{kode_libur}/update', 'update')->name('harilibur.update')->can('harilibur.update');
        Route::delete('/harilibur/{kode_libur}/delete', 'destroy')->name('harilibur.delete')->can('harilibur.delete');
        Route::get('/harilibur/{kode_libur}/approve', 'approve')->name('harilibur.approve')->can('harilibur.approve');
        Route::post('/harilibur/{kode_libur}/storeapprove', 'storeapprove')->name('harilibur.storeapprove')->can('harilibur.approve');
        Route::delete('/harilibur/{kode_libur}/cancel', 'cancel')->name('harilibur.cancel')->can('harilibur.approve');

        Route::get('/harilibur/{kode_libur}/aturharilibur', 'aturharilibur')->name('harilibur.aturharilibur')->can('harilibur.setharilibur');
        Route::get('/harilibur/{kode_libur}/getkaryawanlibur', 'getkaryawanlibur')->name('harilibur.getkaryawanlibur');
        Route::get('/harilibur/{kode_libur}/aturkaryawan', 'aturkaryawan')->name('harilibur.aturkaryawan');
        Route::post('/harilibur/getkaryawan', 'getkaryawan')->name('harilibur.getkaryawan');
        Route::post('/harilibur/updateliburkaryawan', 'updateliburkaryawan')->name('harilibur.updateliburkaryawan');
        Route::post('/harilibur/tambahkansemua', 'tambahkansemua')->name('harilibur.tambahkansemua');
        Route::post('/harilibur/batalkansemua', 'batalkansemua')->name('harilibur.batalkansemua');
        Route::post('/harilibur/deletekaryawanlibur', 'deletekaryawanlibur')->name('harilibur.deletekaryawanlibur');
    });

    Route::controller(LemburController::class)->group(function () {
        Route::get('/lembur', 'index')->name('lembur.index')->can('lembur.index');
        Route::get('/lembur/create', 'create')->name('lembur.create')->can('lembur.create');
        Route::post('/lembur/store', 'store')->name('lembur.store')->can('lembur.store');
        Route::get('/lembur/{kode_lembur}/show', 'show')->name('lembur.show')->can('lembur.show');
        Route::get('/lembur/{kode_lembur}/edit', 'edit')->name('lembur.edit')->can('lembur.edit');
        Route::put('/lembur/{kode_lembur}/update', 'update')->name('lembur.update')->can('lembur.update');
        Route::delete('/lembur/{kode_lembur}/delete', 'destroy')->name('lembur.delete')->can('lembur.delete');
        Route::get('/lembur/{kode_lembur}/approve', 'approve')->name('lembur.approve')->can('lembur.approve');
        Route::post('/lembur/{kode_lembur}/storeapprove', 'storeapprove')->name('lembur.storeapprove')->can('lembur.approve');
        Route::delete('/lembur/{kode_lembur}/cancel', 'cancel')->name('lembur.cancel')->can('lembur.approve');
        Route::get('/lembur/{kode_lembur}/aturlembur', 'aturlembur')->name('lembur.aturlembur')->can('lembur.setlembur');

        Route::get('/lembur/{kode_lembur}/getkaryawanlembur', 'getkaryawanlembur')->name('lembur.getkaryawanlembur');
        Route::get('/lembur/{kode_lembur}/aturkaryawan', 'aturkaryawan')->name('lembur.aturkaryawan');
        Route::post('/lembur/getkaryawan', 'getkaryawan')->name('lembur.getkaryawan');
        Route::post('/lembur/updatelemburkaryawan', 'updatelemburkaryawan')->name('lembur.updatelemburkaryawan');
        Route::post('/lembur/tambahkansemua', 'tambahkansemua')->name('lembur.tambahkansemua');
        Route::post('/lembur/batalkansemua', 'batalkansemua')->name('lembur.batalkansemua');
        Route::post('/lembur/deletekaryawanlembur', 'deletekaryawanlembur')->name('lembur.deletekaryawanlembur');
    });

    Route::controller(IzinabsenController::class)->group(function () {
        Route::get('/izinabsen', 'index')->name('izinabsen.index')->can('izinabsen.index');
        Route::get('/izinabsen/create', 'create')->name('izinabsen.create')->can('izinabsen.create');
        Route::post('/izinabsen/store', 'store')->name('izinabsen.store')->can('izinabsen.store');
        Route::get('/izinabsen/{kode_izin}/show', 'show')->name('izinabsen.show')->can('izinabsen.show');
        Route::get('/izinabsen/{kode_izin}/edit', 'edit')->name('izinabsen.edit')->can('izinabsen.edit');
        Route::put('/izinabsen/{kode_izin}/update', 'update')->name('izinabsen.update')->can('izinabsen.update');
        Route::delete('/izinabsen/{kode_izin}/delete', 'destroy')->name('izinabsen.delete')->can('izinabsen.delete');
        Route::get('/izinabsen/{kode_izin}/approve', 'approve')->name('izinabsen.approve')->can('izinabsen.approve');
        Route::post('/izinabsen/{kode_izin}/storeapprove', 'storeapprove')->name('izinabsen.storeapprove')->can('izinabsen.approve');
        Route::delete('/izinabsen/{kode_izin}/cancel', 'cancel')->name('izinabsen.cancel')->can('izinabsen.approve');
    });

    Route::controller(IzinkeluarController::class)->group(function () {
        Route::get('/izinkeluar', 'index')->name('izinkeluar.index')->can('izinkeluar.index');
        Route::get('/izinkeluar/create', 'create')->name('izinkeluar.create')->can('izinkeluar.create');
        Route::post('/izinkeluar/store', 'store')->name('izinkeluar.store')->can('izinkeluar.store');
        Route::get('/izinkeluar/{kode_izin_keluar}/show', 'show')->name('izinkeluar.show')->can('izinkeluar.show');
        Route::get('/izinkeluar/{kode_izin_keluar}/edit', 'edit')->name('izinkeluar.edit')->can('izinkeluar.edit');
        Route::put('/izinkeluar/{kode_izin_keluar}/update', 'update')->name('izinkeluar.update')->can('izinkeluar.update');
        Route::delete('/izinkeluar/{kode_izin_keluar}/delete', 'destroy')->name('izinkeluar.delete')->can('izinkeluar.delete');
        Route::get('/izinkeluar/{kode_izin_keluar}/approve', 'approve')->name('izinkeluar.approve')->can('izinkeluar.approve');
        Route::get('/izinkeluar/{kode_izin_keluar}/updatejamkembali', 'updatejamkembali')->name('izinkeluar.updatejamkembali')->can('izinkeluar.update');
        Route::post('/izinkeluar/{kode_izin_keluar}/storeapprove', 'storeapprove')->name('izinkeluar.storeapprove')->can('izinkeluar.approve');
        Route::post('/izinkeluar/{kode_izin_keluar}/storeupdatejamkembali', 'storeupdatejamkembali')->name('izinkeluar.storeupdatejamkembali')->can('izinkeluar.approve');
        Route::delete('/izinkeluar/{kode_izin_keluar}/cancel', 'cancel')->name('izinkeluar.cancel')->can('izinkeluar.approve');
    });

    Route::controller(IzinpulangController::class)->group(function () {
        Route::get('/izinpulang', 'index')->name('izinpulang.index')->can('izinpulang.index');
        Route::get('/izinpulang/create', 'create')->name('izinpulang.create')->can('izinpulang.create');
        Route::post('/izinpulang/store', 'store')->name('izinpulang.store')->can('izinpulang.store');
        Route::get('/izinpulang/{kode_izin_pulang}/show', 'show')->name('izinpulang.show')->can('izinpulang.show');
        Route::get('/izinpulang/{kode_izin_pulang}/edit', 'edit')->name('izinpulang.edit')->can('izinpulang.edit');
        Route::put('/izinpulang/{kode_izin_pulang}/update', 'update')->name('izinpulang.update')->can('izinpulang.update');
        Route::delete('/izinpulang/{kode_izin_pulang}/delete', 'destroy')->name('izinpulang.delete')->can('izinpulang.delete');
        Route::get('/izinpulang/{kode_izin_pulang}/approve', 'approve')->name('izinpulang.approve')->can('izinpulang.approve');
        Route::post('/izinpulang/{kode_izin_pulang}/storeapprove', 'storeapprove')->name('izinpulang.storeapprove')->can('izinpulang.approve');
        Route::delete('/izinpulang/{kode_izin_pulang}/cancel', 'cancel')->name('izinpulang.cancel')->can('izinpulang.approve');
    });

    Route::controller(IzinterlambatController::class)->group(function () {
        Route::get('/izinterlambat', 'index')->name('izinterlambat.index')->can('izinterlambat.index');
        Route::get('/izinterlambat/create', 'create')->name('izinterlambat.create')->can('izinterlambat.create');
        Route::post('/izinterlambat/store', 'store')->name('izinterlambat.store')->can('izinterlambat.store');
        Route::get('/izinterlambat/{kode_izin_terlambat}/show', 'show')->name('izinterlambat.show')->can('izinterlambat.show');
        Route::get('/izinterlambat/{kode_izin_terlambat}/edit', 'edit')->name('izinterlambat.edit')->can('izinterlambat.edit');
        Route::put('/izinterlambat/{kode_izin_terlambat}/update', 'update')->name('izinterlambat.update')->can('izinterlambat.update');
        Route::delete('/izinterlambat/{kode_izin_terlambat}/delete', 'destroy')->name('izinterlambat.delete')->can('izinterlambat.delete');
        Route::get('/izinterlambat/{kode_izin_terlambat}/approve', 'approve')->name('izinterlambat.approve')->can('izinterlambat.approve');
        Route::post('/izinterlambat/{kode_izin_terlambat}/storeapprove', 'storeapprove')->name('izinterlambat.storeapprove')->can('izinterlambat.approve');
        Route::delete('/izinterlambat/{kode_izin_terlambat}/cancel', 'cancel')->name('izinterlambat.cancel')->can('izinterlambat.approve');
    });

    Route::controller(IzinsakitController::class)->group(function () {
        Route::get('/izinsakit', 'index')->name('izinsakit.index')->can('izinsakit.index');
        Route::get('/izinsakit/create', 'create')->name('izinsakit.create')->can('izinsakit.create');
        Route::post('/izinsakit/store', 'store')->name('izinsakit.store')->can('izinsakit.store');
        Route::get('/izinsakit/{kode_izin_sakit}/show', 'show')->name('izinsakit.show')->can('izinsakit.show');
        Route::get('/izinsakit/{kode_izin_sakit}/edit', 'edit')->name('izinsakit.edit')->can('izinsakit.edit');
        Route::put('/izinsakit/{kode_izin_sakit}/update', 'update')->name('izinsakit.update')->can('izinsakit.update');
        Route::delete('/izinsakit/{kode_izin_sakit}/delete', 'destroy')->name('izinsakit.delete')->can('izinsakit.delete');
        Route::get('/izinsakit/{kode_izin_sakit}/approve', 'approve')->name('izinsakit.approve')->can('izinsakit.approve');
        Route::post('/izinsakit/{kode_izin_sakit}/storeapprove', 'storeapprove')->name('izinsakit.storeapprove')->can('izinsakit.approve');
        Route::delete('/izinsakit/{kode_izin_sakit}/cancel', 'cancel')->name('izinsakit.cancel')->can('izinsakit.approve');
    });

    Route::controller(IzincutiController::class)->group(function () {
        Route::get('/izincuti', 'index')->name('izincuti.index')->can('izincuti.index');
        Route::get('/izincuti/create', 'create')->name('izincuti.create')->can('izincuti.create');
        Route::post('/izincuti/store', 'store')->name('izincuti.store')->can('izincuti.store');
        Route::get('/izincuti/{kode_izin_cuti}/show', 'show')->name('izincuti.show')->can('izincuti.show');
        Route::get('/izincuti/{kode_izin_cuti}/edit', 'edit')->name('izincuti.edit')->can('izincuti.edit');
        Route::put('/izincuti/{kode_izin_cuti}/update', 'update')->name('izincuti.update')->can('izincuti.update');
        Route::delete('/izincuti/{kode_izin_cuti}/delete', 'destroy')->name('izincuti.delete')->can('izincuti.delete');
        Route::get('/izincuti/{kode_izin_cuti}/approve', 'approve')->name('izincuti.approve')->can('izincuti.approve');
        Route::post('/izincuti/{kode_izin_cuti}/storeapprove', 'storeapprove')->name('izincuti.storeapprove')->can('izincuti.approve');
        Route::delete('/izincuti/{kode_izin_cuti}/cancel', 'cancel')->name('izincuti.cancel')->can('izincuti.approve');
    });

    Route::controller(IzindinasController::class)->group(function () {
        Route::get('/izindinas', 'index')->name('izindinas.index')->can('izindinas.index');
        Route::get('/izindinas/create', 'create')->name('izindinas.create')->can('izindinas.create');
        Route::post('/izindinas/store', 'store')->name('izindinas.store')->can('izindinas.store');
        Route::get('/izindinas/{kode_izin_dinas}/show', 'show')->name('izindinas.show')->can('izindinas.show');
        Route::get('/izindinas/{kode_izin_dinas}/edit', 'edit')->name('izindinas.edit')->can('izindinas.edit');
        Route::put('/izindinas/{kode_izin_dinas}/update', 'update')->name('izindinas.update')->can('izindinas.update');
        Route::delete('/izindinas/{kode_izin_dinas}/delete', 'destroy')->name('izindinas.delete')->can('izindinas.delete');
        Route::get('/izindinas/{kode_izin_dinas}/approve', 'approve')->name('izindinas.approve')->can('izindinas.approve');
        Route::post('/izindinas/{kode_izin_dinas}/storeapprove', 'storeapprove')->name('izindinas.storeapprove')->can('izindinas.approve');
        Route::delete('/izindinas/{kode_izin_dinas}/cancel', 'cancel')->name('izindinas.cancel')->can('izindinas.approve');
    });

    Route::controller(IzinkoreksiController::class)->group(function () {
        Route::get('/izinkoreksi', 'index')->name('izinkoreksi.index')->can('izinkoreksi.index');
        Route::get('/izinkoreksi/create', 'create')->name('izinkoreksi.create')->can('izinkoreksi.create');
        Route::post('/izinkoreksi/store', 'store')->name('izinkoreksi.store')->can('izinkoreksi.store');
        Route::get('/izinkoreksi/{kode_izin_koreksi}/show', 'show')->name('izinkoreksi.show')->can('izinkoreksi.show');
        Route::get('/izinkoreksi/{kode_izin_koreksi}/edit', 'edit')->name('izinkoreksi.edit')->can('izinkoreksi.edit');
        Route::put('/izinkoreksi/{kode_izin_koreksi}/update', 'update')->name('izinkoreksi.update')->can('izinkoreksi.update');
        Route::delete('/izinkoreksi/{kode_izin_koreksi}/delete', 'destroy')->name('izinkoreksi.delete')->can('izinkoreksi.delete');
        Route::get('/izinkoreksi/{kode_izin_koreksi}/approve', 'approve')->name('izinkoreksi.approve')->can('izinkoreksi.approve');
        Route::post('/izinkoreksi/{kode_izin_koreksi}/storeapprove', 'storeapprove')->name('izinkoreksi.storeapprove')->can('izinkoreksi.approve');
        Route::delete('/izinkoreksi/{kode_izin_koreksi}/cancel', 'cancel')->name('izinkoreksi.cancel')->can('izinkoreksi.approve');

        Route::get('/getjadwalkerja/{kode_jadwal}', 'getjadwalkerja')->name('getjadwalkerja');
        Route::get('/getjamkerja/{kode_jadwwal}/{kode_jam_kerja}', 'getjamkerja')->name('getjamkerja');
        Route::post('/izinkoreksi/getpresensi', 'getpresensi')->name('izinkoreksi.getpresensi');
    });

    Route::controller(PresensiController::class)->group(function () {
        Route::get('/presensi', 'index')->name('presensi.index')->can('presensi.index');
        Route::get('/presensikaryawan', 'presensikaryawan')->name('presensi.presensikaryawan')->can('presensi.index');
        Route::post('/presensi/getdatamesin', 'getdatamesin')->name('presensi.getdatamesin')->can('presensi.index');
        Route::post('/presensi/{pin}/{status_scan}/updatefrommachine', 'updatefrommachine')->name('presensi.updatefrommachine')->can('presensi.index');
        Route::post('/presensi/koreksipresensi', 'koreksipresensi')->name('presensi.koreksipresensi')->can('presensi.index');
        Route::get('/presensi/getjamkerja', 'getjamkerja')->name('presensi.getjamkerja')->can('presensi.index');
        Route::post('/presensi/{id}/updatepresensi', 'updatepresensi')->name('presensi.updatepresensi')->can('presensi.index');
        Route::get('/presensi/{id}/{status}/show', 'show')->name('presensi.show')->can('presensi.index');
    });

    Route::controller(SlipgajiController::class)->group(function () {
        Route::get('/slipgaji', 'index')->name('slipgaji.index')->can('slipgaji.index');
        Route::get('/slipgaji/create', 'create')->name('slipgaji.create')->can('slipgaji.create');
        Route::post('/slipgaji/store', 'store')->name('slipgaji.store')->can('slipgaji.store');
        Route::get('/slipgaji/{kode_slip}/show', 'show')->name('slipgaji.show')->can('slipgaji.show');
        Route::get('/slipgaji/{kode_slip}/edit', 'edit')->name('slipgaji.edit')->can('slipgaji.edit');
        Route::put('/slipgaji/{kode_slip}/update', 'update')->name('slipgaji.update')->can('slipgaji.update');
        Route::delete('/slipgaji/{kode_slip}/delete', 'destroy')->name('slipgaji.delete')->can('slipgaji.delete');
        Route::get('/slipgaji/{nik}/{bulan}/{tahun}/cetakslip', 'cetakslipgaji')->name('slipgaji.cetakslip')->can('slipgaji.show');
    });

    Route::controller(PenyesuaianupahController::class)->group(function () {
        Route::get('/penyupah', 'index')->name('penyupah.index')->can('penyupah.index');
        Route::get('/penyupah/create', 'create')->name('penyupah.create')->can('penyupah.create');
        Route::post('/penyupah/store', 'store')->name('penyupah.store')->can('penyupah.store');
        Route::get('/penyupah/{kode_penyupah}/show', 'show')->name('penyupah.show')->can('penyupah.show');
        Route::get('/penyupah/{kode_penyupah}/edit', 'edit')->name('penyupah.edit')->can('penyupah.edit');
        Route::put('/penyupah/{kode_penyupah}/update', 'update')->name('penyupah.update')->can('penyupah.update');
        Route::delete('/penyupah/{kode_penyupah}/delete', 'destroy')->name('penyupah.delete')->can('penyupah.delete');

        Route::get('/penyupah/{kode_gaji}/tambahkaryawan', 'tambahkaryawan')->name('penyupah.create')->can('penyupah.create');
        Route::post('/penyupah/{kode_gaji}/storekaryawan', 'storekaryawan')->name('penyupah.storekaryawan')->can('penyupah.store');
        Route::delete('/penyupah/{kode_gaji}/{nik}/deletekaryawan', 'deletekaryawan')->name('penyupah.deletekaryawan')->can('penyupah.delete');
    });
    Route::controller(LaporangeneralaffairController::class)->group(function () {
        Route::get('/laporanga', 'index')->name('laporanga.index')->can('ga.servicekendaraan', 'ga.rekapbadstok');
        Route::post('/laporanga/cetakservicekendaraaan', 'cetakservicekendaraan')->name('laporanga.cetakservicekendaraan')->can('ga.servicekendaraan');
        Route::post('/laporanga/cetakrekapbadstok', 'cetakrekapbadstok')->name('laporanga.cetakrekapbadstok')->can('ga.rekapbadstok');
    });

    Route::controller(LaporanmaintenanceController::class)->group(function () {
        Route::get('/laporanmtc', 'index')->name('laporanmtc.index')->can('mtc.bahanbakar');
        Route::post('/laporanmtc/cetakbahanbakar', 'cetakbahanbakar')->name('laporanmtc.cetakbahanbakar')->can('mtc.bahanbakar');
    });

    Route::controller(LaporanpembelianController::class)->group(function () {
        Route::get('/laporanpembelian', 'index')->name('laporanpembelian.index');
        Route::post('/laporanpembelian/cetakpembelian', 'cetakpembelian')->name('laporanpembelian.cetakpembelian')->can('pb.pembelian');
        Route::post('/laporanpembelian/cetakpembayaran', 'cetakpembayaran')->name('laporanpembelian.cetakpembayaran')->can('pb.pembayaran');
        Route::post('/laporanpembelian/cetakrekapsupplier', 'cetakrekapsupplier')->name('laporanpembelian.cetakrekapsupplier')->can('pb.rekapsupplier');
        Route::post('/laporanpembelian/cetakrekappembelian', 'cetakrekappembelian')->name('laporanpembelian.cetakrekappembelian')->can('pb.rekappembelian');
        Route::post('/laporanpembelian/cetakkartuhutang', 'cetakkartuhutang')->name('laporanpembelian.cetakkartuhutang')->can('pb.kartuhutang');
        Route::post('/laporanpembelian/cetakauh', 'cetakauh')->name('laporanpembelian.cetakauh')->can('pb.auh');
        Route::post('/laporanpembelian/cetakbahankemasan', 'cetakbahankemasan')->name('laporanpembelian.cetakbahankemasan')->can('pb.bahankemasan');
        Route::post('/laporanpembelian/cetakrekapbahankemasan', 'cetakrekapbahankemasan')->name('laporanpembelian.cetakrekapbahankemasan')->can('pb.rekapbahankemasan');
        Route::post('/laporanpembelian/cetakjurnalkoreksi', 'cetakjurnalkoreksi')->name('laporanpembelian.cetakjurnalkoreksi')->can('pb.jurnalkoreksi');
        Route::post('/laporanpembelian/cetakrekapakun', 'cetakrekapakun')->name('laporanpembelian.cetakrekapakun')->can('pb.rekapakun');
        Route::post('/laporanpembelian/cetakrekapkontrabon', 'cetakrekapkontrabon')->name('laporanpembelian.cetakrekapkontrabon')->can('pb.rekapkontrabon');
    });


    Route::controller(LaporankeuanganController::class)->group(function () {
        Route::get('/laporankeuangan', 'index')->name('laporankeuangan.index');
        Route::post('/laporankeuangan/cetakkaskecil', 'cetakkaskecil')->name('laporankeuangan.cetakkaskecil')->can('keu.kaskecil');
        Route::post('/laporankeuangan/cetakledger', 'cetakledger')->name('laporankeuangan.cetakledger')->can('keu.ledger');
        Route::post('/laporankeuangan/cetakmutasikeuangan', 'cetakmutasikeuangan')->name('laporankeuangan.cetakmutasikeuangan')->can('keu.mutasikeuangan');
        Route::post('/laporankeuangan/cetakrekapledger', 'cetakrekapledger')->name('laporankeuangan.cetakrekapledger')->can('keu.ledger');
        Route::post('/laporankeuangan/cetaksaldokasbesar', 'cetaksaldokasbesar')->name('laporankeuangan.cetaksaldokasbesar')->can('keu.saldokasbesar');
        Route::post('/laporankeuangan/cetaklpu', 'cetaklpu')->name('laporankeuangan.cetaklpu')->can('keu.lpu');
        Route::post('/laporankeuangan/cetakpenjualan', 'cetakpenjualan')->name('laporankeuangan.cetakpenjualan')->can('keu.penjualan');
        Route::post('/laporankeuangan/cetakuanglogam', 'cetakuanglogam')->name('laporankeuangan.cetakuanglogam')->can('keu.uanglogam');
        Route::post('/laporankeuangan/cetakrekapbg', 'cetakrekapbg')->name('laporankeuangan.cetakrekapbg')->can('keu.rekapbg');
        Route::post('/laporankeuangan/cetakpinjaman', 'cetakpinjaman')->name('laporankeuangan.cetakpinjaman')->can('keu.pinjaman');
        Route::post('/laporankeuangan/cetakkasbon', 'cetakkasbon')->name('laporankeuangan.cetakkasbon')->can('keu.kasbon');
        Route::post('/laporankeuangan/cetakpiutangkaryawan', 'cetakpiutangkaryawan')->name('laporankeuangan.cetakpiutangkaryawan')->can('keu.piutangkaryawan');
        Route::post('/laporankeuangan/cetakrekapkartupiutang', 'cetakrekapkartupiutang')->name('laporankeuangan.cetakrekapkartupiutang')->can('keu.rekapkartupiutang');
        Route::post('/laporankeuangan/cetakkartupjp', 'cetakkartupjp')->name('laporankeuangan.cetakkartupjp')->can('keu.kartupinjaman');
        Route::post('/laporankeuangan/cetakkartukasbon', 'cetakkartukasbon')->name('laporankeuangan.cetakkartukasbon')->can('keu.kartukasbon');
        Route::post('/laporankeuangan/cetakkartupiutangkaryawan', 'cetakkartupiutangkaryawan')->name('laporankeuangan.cetakkartupiutangkaryawan')->can('keu.kartupiutangkaryawan');
        Route::get('/laporankeuangan/{dari}/{sampai}/{exportButton}/cetakmutasikategori', 'cetakmutasikategori')->name('laporankeuangan.cetakmutasikategori');
    });

    Route::controller(LaporanmarketingController::class)->group(function () {
        Route::get('/laporanmarketing', 'index')->name('laporanmarketing.index');
        Route::post('/laporanmarketing/cetakpenjualan', 'cetakpenjualan')->name('laporanmarketing.cetakpenjualan')->can('mkt.penjualan');
        Route::post('/laporanmarketing/cetakkasbesar', 'cetakkasbesar')->name('laporanmarketing.cetakkasbesar')->can('mkt.kasbesar');
        Route::post('/laporanmarketing/cetakretur', 'cetakretur')->name('laporanmarketing.cetakretur')->can('mkt.retur');
        Route::post('/laporanmarketing/cetaktunaikredit', 'cetaktunaikredit')->name('laporanmarketing.cetaktunaikredit')->can('mkt.tunaikredit');
        Route::post('/laporanmarketing/cetakkartupiutang', 'cetakkartupiutang')->name('laporanmarketing.cetakkartupiutang')->can('mkt.kartupiutang');
        Route::post('/laporanmarketing/cetakaup', 'cetakaup')->name('laporanmarketing.cetakaup')->can('mkt.aup');
        Route::post('/laporanmarketing/cetaklebihsatufaktur', 'cetaklebihsatufaktur')->name('laporanmarketing.cetaklebihsatufaktur')->can('mkt.lebihsatufaktur');
        Route::post('/laporanmarketing/cetakdppp', 'cetakdppp')->name('laporanmarketing.cetakdppp')->can('mkt.dppp');
        Route::post('/laporanmarketing/cetakdpp', 'cetakdpp')->name('laporanmarketing.cetakdpp')->can('mkt.dpp');
        Route::post('/laporanmarketing/cetakomsetpelanggan', 'cetakomsetpelanggan')->name('laporanmarketing.cetakomsetpelanggan')->can('mkt.omsetpelanggan');
        Route::post('/laporanmarketing/cetakrekappelanggan', 'cetakrekappelanggan')->name('laporanmarketing.cetakrekappelanggan')->can('mkt.rekappelanggan');
        Route::post('/laporanmarketing/cetakrekappenjualan', 'cetakrekappenjualan')->name('laporanmarketing.cetakrekappenjualan')->can('mkt.rekappenjualan');
        Route::post('/laporanmarketing/cetakrekapkendaraan', 'cetakrekapkendaraan')->name('laporanmarketing.cetakrekapkendaraan')->can('mkt.rekapkendaraan');
        Route::post('/laporanmarketing/cetakharganet', 'cetakharganet')->name('laporanmarketing.cetakharganet')->can('mkt.harganet');
        Route::post('/laporanmarketing/cetaktandaterimafaktur', 'cetaktandaterimafaktur')->name('laporanmarketing.cetaktandaterimafaktur')->can('mkt.tandaterimafaktur');
        Route::post('/laporanmarketing/cetakrekapwilayah', 'cetakrekapwilayah')->name('laporanmarketing.cetakrekapwilayah')->can('mkt.rekapwilayah');
        Route::post('/laporanmarketing/cetakeffectivecall', 'cetakeffectivecall')->name('laporanmarketing.cetakeffectivecall')->can('mkt.effectivecall');
        Route::post('/laporanmarketing/cetakanalisatransaksi', 'cetakanalisatransaksi')->name('laporanmarketing.cetakanalisatransaksi')->can('mkt.analisatransaksi');
        Route::post('/laporanmarketing/cetaktunaitransfer', 'cetaktunaitransfer')->name('laporanmarketing.cetaktunaitransfer')->can('mkt.tunaitransfer');
        Route::post('/laporanmarketing/cetaklhp', 'cetaklhp')->name('laporanmarketing.cetaklhp')->can('mkt.lhp');
        Route::post('/laporanmarketing/cetarroutingsalesman', 'cetarroutingsalesman')->name('laporanmarketing.cetarroutingsalesman')->can('mkt.routingsalesman');
        Route::post('/laporanmarketing/cetaksalesperfomance', 'cetaksalesperfomance')->name('laporanmarketing.cetaksalesperfomance')->can('mkt.salesperfomance');
        Route::post('/laporanmarketing/cetakpersentasesfa', 'cetakpersentasesfa')->name('laporanmarketing.cetakpersentasesfa')->can('mkt.persentasesfa');
        Route::post('/laporanmarketing/cetakpersentasedatapelanggan', 'cetakpersentasedatapelanggan')->name('laporanmarketing.cetakpersentasedatapelanggan')->can('mkt.persentasesfa');
        Route::post('/laporanmarketing/cetaksmmactivity', 'cetaksmmactivity')->name('laporanmarketing.cetaksmmactivity')->can('mkt.smmactivity');
        Route::post('/laporanmarketing/cetaksrsmactivity', 'cetaksrsmactivity')->name('laporanmarketing.cetaksrsmactivity')->can('mkt.rsmactivity');
        Route::post('/laporanmarketing/cetakkomisisalesman', 'cetakkomisisalesman')->name('laporanmarketing.cetakkomisisalesman')->can('mkt.komisisalesman');
        Route::post('/laporanmarketing/cetakkomisidriverhelper', 'cetakkomisidriverhelper')->name('laporanmarketing.cetakkomisidriverhelper')->can('mkt.komisidriverhelper');
        Route::post('/laporanmarketing/cetakroutingsalesman', 'cetakroutingsalesman')->name('laporanmarketing.cetakroutingsalesman')->can('mkt.routingsalesman');
        Route::post('/laporanmarketing/cetakinsentifom', 'cetakinsentifom')->name('laporanmarketing.cetakinsentifom')->can('worksheetom.insentifom');
        Route::post('/laporanmarketing/cetakratiobs', 'cetakratiobs')->name('laporanmarketing.cetakratiobs')->can('worksheetom.ratiobs');
    });

    Route::controller(LaporanaccountingController::class)->group(function () {
        Route::get('/laporanaccounting', 'index')->name('laporanaccounting.index');
        Route::post('/laporanaccounting/cetakrekapbj', 'cetakrekapbj')->name('laporanaccounting.cetakrekapbj')->can('akt.rekapbj');
        Route::post('/laporanaccounting/cetakrekappersediaan', 'cetakrekappersediaan')->name('laporanaccounting.cetakrekappersediaan')->can('akt.rekappersediaan');
        Route::post('/laporanaccounting/cetakcostratio', 'cetakcostratio')->name('laporanaccounting.cetakcostratio')->can('akt.costratio');
        Route::post('/laporanaccounting/cetakjurnalumum', 'cetakjurnalumum')->name('laporanaccounting.cetakjurnalumum')->can('akt.jurnalumum');

        //Laporan Keuangan
        Route::post('/laporanaccounting/cetakbukubesar', 'cetakbukubesar')->name('laporanaccounting.cetakbukubesar')->can('lk.bukubesar');
    });

    Route::controller(LaporanhrdController::class)->group(function () {
        Route::get('/laporanhrd', 'index')->name('laporanhrd.index');
        Route::post('/laporanhrd/cetakpresensi', 'cetakpresensi')->name('laporanhrd.cetakpresensi')->can('hrd.presensi');
        Route::post('/laporanhrd/cetakgaji', 'cetakpresensi')->name('laporanhrd.cetakgaji')->can('hrd.gaji');
        Route::post('/laporanhrd/cetakslipegaji', 'cetakslipegaji')->name('laporanhrd.cetakslipegaji')->can('hrd.slipgaji');
        Route::post('/laporanhrd/cetakcuti', 'cetakcuti')->name('laporanhrd.cetakcuti')->can('hrd.presensi');


        Route::post('/laporanhrd/getdepartemen', 'getdepartemen')->name('laporanhrd.getdepartemen')->can('hrd.presensi');
        Route::post('/laporanhrd/getgroup', 'getgroup')->name('laporanhrd.getgroup')->can('hrd.presensi');
    });

    Route::controller(VisitpelangganController::class)->group(function () {
        Route::get('/visitpelanggan', 'index')->name('visitpelanggan.index')->can('visitpelanggan.index');
        Route::get('/visitpelanggan/{no_faktur}/create', 'create')->name('visitpelanggan.create')->can('visitpelanggan.create');
        Route::post('/visitpelanggan/{no_faktur}/store', 'store')->name('visitpelanggan.store')->can('visitpelanggan.store');
        Route::get('/visitpelanggan/{kode_visit}/edit', 'edit')->name('visitpelanggan.edit')->can('visitpelanggan.edit');
        Route::put('/visitpelanggan/{kode_visit}', 'update')->name('visitpelanggan.update')->can('visitpelanggan.update');
        Route::delete('/visitpelanggan/{kode_visit}/delete', 'destroy')->name('visitpelanggan.delete')->can('visitpelanggan.delete');
        Route::get('/visitpelanggan/{kode_visit}/show', 'show')->name('visitpelanggan.show')->can('visitpelanggan.show');
        Route::get('/visitpelanggan/cetak', 'cetak')->name('visitpelanggan.cetak')->can('visitpelanggan.show');
    });

    Route::controller(MonitoringreturController::class)->group(function () {
        Route::get('/monitoringretur/{no_retur}/create', 'create')->name('monitoringretur.index')->can('worksheetom.monitoringretur');
        Route::post('/monitoringretur/{no_retur}/store', 'store')->name('monitoringretur.store')->can('worksheetom.monitoringretur');
        Route::get('/monitoringretur/cetak', 'cetak')->name('monitoringretur.cetak')->can('worksheetom.monitoringretur');
    });

    Route::controller(PelunasanreturController::class)->group(function () {
        Route::get('/pelunasanretur/{no_retur}/create', 'create')->name('monitoringretur.index')->can('worksheetom.monitoringretur');
        Route::post('/pelunasanretur/{no_retur}/store', 'store')->name('pelunasanretur.store')->can('worksheetom.monitoringretur');
    });

    Route::controller(KirimlhpController::class)->group(function () {
        Route::get('/kirimlhp', 'index')->name('kirimlhp.index')->can('kirimlhp.index');
        Route::get('/kirimlhp/create', 'create')->name('kirimlhp.create')->can('kirimlhp.create');
        Route::post('/kirimlhp/store', 'store')->name('kirimlhp.store')->can('kirimlhp.store');
        Route::delete('/kirimlhp/{kode_kirim_lhp}/delete', 'destroy')->name('kirimlhp.delete')->can('kirimlhp.delete');
        Route::get('/kirimlhp/{kode_kirim_lhp}/approve', 'approve')->name('kirimlhp.approve')->can('kirimlhp.approve');
        Route::post('/kirimlhp/{kode_kirim_lhp}/storeapprove', 'storeapprove')->name('kirimlhp.storeapprove')->can('kirimlhp.approve');
        Route::delete('/kirimlhp/{kode_kirim_lhp}/cancelapprove', 'cancelapprove')->name('kirimlhp.cancelapprove')->can('kirimlhp.approve');
    });


    Route::controller(KirimlpcController::class)->group(function () {
        Route::get('/kirimlpc', 'index')->name('kirimlpc.index')->can('kirimlpc.index');
        Route::get('/kirimlpc/create', 'create')->name('kirimlpc.create')->can('kirimlpc.create');
        Route::post('/kirimlpc/store', 'store')->name('kirimlpc.store')->can('kirimlpc.store');
        Route::delete('/kirimlpc/{kode_kirim_lpc}/delete', 'destroy')->name('kirimlpc.delete')->can('kirimlpc.delete');
        Route::get('/kirimlpc/{kode_kirim_lpc}/approve', 'approve')->name('kirimlpc.approve')->can('kirimlpc.approve');
        Route::post('/kirimlpc/{kode_kirim_lpc}/storeapprove', 'storeapprove')->name('kirimlpc.storeapprove')->can('kirimlpc.approve');
        Route::delete('/kirimlpc/{kode_kirim_lpc}/cancelapprove', 'cancelapprove')->name('kirimlpc.cancelapprove')->can('kirimlpc.approve');
    });



    //SFA
    Route::controller(SfaControler::class)->group(function () {
        Route::get('/sfa/pelanggan', 'pelanggan')->name('sfa.pelanggan')->can('sfa.pelanggan');
        Route::get('/sfa/pelanggan/create', 'createpelanggan')->name('sfa.createpelanggan')->can('sfa.pelanggan');
        Route::get('/sfa/pelanggan/{kode_pelanggan}/edit', 'editpelanggan')->name('sfa.editpelanggan')->can('sfa.pelanggan');
        Route::post('/sfa/pelanggan/store', 'storepelanggan')->name('sfa.storepelanggan')->can('sfa.pelanggan');
        Route::put('/sfa/pelanggan/{kode_pelanggan}/updatepelanggan', 'updatepelanggan')->name('sfa.updatepelanggan')->can('sfa.pelanggan');
        Route::get('/sfa/pelanggan/{kode_pelanggan}/show', 'showpelanggan')->name('sfa.showpelanggan')->can('sfa.pelanggan');
        Route::get('/sfa/pelanggan/{kode_pelanggan}/capture', 'capture')->name('sfa.capture')->can('sfa.pelanggan');
        Route::post('/sfa/checkinstore', 'checkinstore')->name('sfa.checkinstore')->can('sfa.penjualan');
        Route::get('/sfa/{kode_pelanggan}/checkout', 'checkout')->name('sfa.checkout')->can('sfa.penjualan');
        Route::post('/sfa/storepelanggancapture', 'storepelanggancapture')->name('sfa.storepelangancapture')->can('sfa.pelanggan');
        Route::get('/sfa/penjualan/{no_faktur}/show', 'showpenjualan')->name('sfa.showpenjualan')->can('sfa.penjualan');
        Route::get('/sfa/penjualan/{no_faktur}/cetak', 'cetakfaktur')->name('sfa.cetakfaktur')->can('sfa.penjualan');
        Route::post('/sfa/uploadsignature', 'uploadsignature')->name('sfa.uploadsignature')->can('sfa.penjualan');
        Route::delete('/sfa/{no_faktur}/deletesignature', 'deletesignature')->name('sfa.deletesignature')->can('sfa.penjualan');

        Route::get('/sfa/penjualan', 'penjualan')->name('sfa.penjualan')->can('sfa.penjualan');
        Route::get('/sfa/penjualan/create', 'createpenjualan')->name('sfa.createpenjualan')->can('sfa.penjualan');
        Route::get('/sfa/penjualan/{no_faktur}/edit', 'editpenjualan')->name('sfa.editpenjualan')->can('sfa.penjualan');
        Route::get('/sfa/penjualan/{kode_pelanggan}/addproduk', 'addproduk')->name('sfa.addproduk')->can('sfa.penjualan');
        Route::get('/sfa/penjualan/{no_faktur}/ubahfakturbatal', 'ubahfakturbatal')->name('sfa.ubahfakturbatal')->can('sfa.penjualan');
        Route::get('/sfa/penjualan/{no_faktur}/batalkanubahfakturbatal', 'batalkanubahfakturbatal')->name('sfa.batalkanubahfakturbatal')->can('sfa.penjualan');
        Route::post('/sfa/penjualan/{no_faktur}/storeubahfakturbatal', 'storeubahfakturbatal')->name('sfa.storeubahfakturbatal')->can('sfa.penjualan');


        Route::get('/sfa/ajuanfaktur/{kode_pelanggan}/create', 'createajuanfaktur')->name('sfa.createajuanfaktur')->can('sfa.ajuanfaktur');
        Route::post('/sfa/ajuanfaktur/{kode_pelanggan}/storeajuanfaktur', 'storeajuanfaktur')->name('sfa.storeajuanfaktur')->can('sfa.ajuanfaktur');

        Route::get('/sfa/ajuanlimit/{kode_pelanggan}/create', 'createajuanlimit')->name('sfa.createajuanlimit')->can('sfa.limitkredit');
        Route::post('/sfa/ajuanlimit/{kode_pelanggan}/storeajuanlimit', 'storeajuanlimit')->name('sfa.storeajuanlimit')->can('sfa.limitkredit');


        Route::get('/sfa/retur/create', 'createretur')->name('sfa.createretur')->can('sfa.retur');
        Route::get('/sfa/retur/{kode_pelanggan}/addproduk', 'addprodukretur')->name('sfa.addprodukretur')->can('sfa.retur');
        Route::get('/sfa/retur/{no_retur}/show', 'showretur')->name('sfa.showretur')->can('sfa.retur');
        Route::get('/sfa/retur/{no_retur}/edit', 'editretur')->name('sfa.editretur')->can('sfa.retur');

        Route::get('/sfa/trackingsalesman', 'trackingsalesman')->name('sfa.trackingsalesman')->can('sfa.trackingsalesman');
        Route::get('/sfa/getlocationcheckin', 'getlocationcheckin')->name('sfa.getlocationcheckin')->can('sfa.trackingsalesman');

        Route::get('/sfa/dashboard', 'dashboard')->name('dashboard.sfa')->can('dashboard.sfa');
    });

    Route::controller(TutuplaporanController::class)->group(function () {
        //Ajax Request
        Route::get('/tutuplaporan', 'index')->name('tutuplaporan.index')->can('tutuplaporan.index');
        Route::get('/tutuplaporan/create', 'create')->name('tutuplaporan.create')->can('tutuplaporan.create');
        Route::post('/tutuplaporan/store', 'store')->name('tutuplaporan.store')->can('tutuplaporan.store');
        Route::get('/tutuplaporan/{kode_tutup_laporan}/lockunlock', 'lockunlock')->name('tutuplaporan.lockunlock')->can('tutuplaporan.create');
        Route::post('/tutuplaporan/cektutuplaporan', 'cektutuplaporan');
    });

    Route::controller(AktifitassmmController::class)->group(function () {
        Route::get('/aktifitassmm', 'index')->name('aktifitassmm.index');
        Route::get('/aktifitassmm/create', 'create')->name('aktifitassmm.create');
        Route::post('/aktifitassmm/store', 'store')->name('aktifitassmm.store');
        Route::post('/aktifitassmm/getaktifitas', 'getaktifitas')->name('aktifitassmm.getaktifitas');
        Route::get('/aktifitassmm/{id_user}/{tanggal}/getdetailaktifitas', 'getdetailaktifitas')->name('aktifitassmm.getdetailaktifitas');
    });


    Route::controller(MonitoringprogramController::class)->group(function () {
        Route::get('/monitoringprogram', 'index')->name('monitoringprogram.index')->can('monitoringprogram.index');
        Route::get('/monitoringprogram/create', 'create')->name('monitoringprogram.create')->can('monitoringprogram.create');
        Route::post('/monitoringprogram/store', 'store')->name('monitoringprogram.store')->can('monitoringprogram.store');
        Route::get('/monitoringprogram/{id}/edit', 'edit')->name('monitoringprogram.edit')->can('monitoringprogram.edit');
        Route::post('/monitoringprogram/{id}/update', 'update')->name('monitoringprogram.update')->can('monitoringprogram.update');
        Route::delete('/monitoringprogram/{id}/destroy', 'destroy')->name('monitoringprogram.destroy')->can('monitoringprogram.delete');

        Route::get('/monitoringprogram/{kode_pelanggan}/{kode_program}/{bulan}/{tahun}/detailfaktur', 'detailfaktur')->name('monitoringprogram.detailfaktur');
        Route::get('/monitoringprogram/saldosimpanan', 'saldosimpanan')->name('monitoringprogram.saldosimpanan')->can('monitoringprogram.index');
        Route::get('/monitoringprogram/saldovoucher', 'saldovoucher')->name('monitoringprogram.saldovoucher')->can('monitoringprogram.index');
        Route::get('/monitoringprogram/{kode_pelanggan}/getdetailsimpanan', 'getdetailsimpanan')->name('monitoringprogram.getdetailsimpanan');
        Route::get('/monitoringprogram/{kode_pelanggan}/createpencairansimpanan', 'createpencairansimpanan')->name('monitoringprogram.createpencairansimpanan');
        Route::post('/monitoringprogram/{kode_pelanggan}/storepencairansimpanan', 'storepencairansimpanan')->name('monitoringprogram.storepencairansimpanan');
        Route::get('/monitoringprogram/pencairansimpanan', 'pencairansimpanan')->name('monitoringprogram.pencairansimpanan')->can('monitoringprogram.index');
        Route::get('/monitoringprogram/{kode_pencairan}/approvepencairansimpanan', 'approvepencairansimpanan')->name('monitoringprogram.approvepencairansimpanan')->can('monitoringprogram.index');
        Route::post('/monitoringprogram/{kode_pencairan}/storeapprovepencairansimpanan', 'storeapprovepencairansimpanan')->name('monitoringprogram.storeapprovepencairansimpanan')->can('monitoringprogram.index');
        Route::delete('/monitoringprogram/{kode_pencairan}/deletepencairansimpanan', 'deletepencairansimpanan')->name('monitoringprogram.deletepencairansimpanan');
        Route::get('/monitoringprogram/{kode_pencairan}/cetakpencairansimpanan', 'cetakpencairansimpanan')->name('monitoringprogram.cetakpencairansimpanan');


        Route::get('/monitoringprogram/cetak', 'cetak')->name('monitoringprogram.cetak');
    });

    Route::controller(PencairanprogramController::class)->group(function () {
        Route::get('/pencairanprogram', 'index')->name('pencairanprogram.index')->can('pencairanprogram.index');
        Route::get('/pencairanprogram/create', 'create')->name('pencairanprogram.create')->can('pencairanprogram.create');
        Route::post('/pencairanprogram/store', 'store')->name('pencairanprogram.store')->can('pencairanprogram.store');
        Route::get('/pencairanprogram/{kode_pencairan}/edit', 'edit')->name('pencairanprogram.edit')->can('pencairanprogram.edit');
        Route::post('/pencairanprogram/{kode_pencairan}/update', 'update')->name('pencairanprogram.update')->can('pencairanprogram.update');
        Route::delete('/pencairanprogram/{kode_pencairan}/delete', 'destroy')->name('pencairanprogram.delete')->can('pencairanprogram.delete');

        Route::get('/pencairanprogram/{kode_pencairan}/setpencairan', 'setpencairan')->name('pencairanprogram.setpencairan')->can('pencairanprogram.create');
        Route::get('/pencairanprogram/{kode_pencairan}/tambahpelanggan', 'tambahpelanggan')->name('pencairanprogram.tambahpelanggan')->can('pencairanprogram.create');
        Route::get('/pencairanprogram/{kode_pelanggan}/{kode_pencairan}/{top}/detailfaktur', 'detailfaktur')->name('pencairanprogram.detailfaktur')->can('pencairanprogram.create');
        Route::post('/pencairanprogram/getpelanggan', 'getpelanggan')->name('pencairanprogram.getpelanggan')->can('pencairanprogram.create');
        Route::post('/pencairanprogram/getpelanggantop30', 'getpelanggantop30')->name('pencairanprogram.getpelanggantop30')->can('pencairanprogram.create');
        Route::post('/pencairanprogram/storepelanggan', 'storepelanggan')->name('pencairanprogram.storepelanggan')->can('pencairanprogram.create');
        Route::post('/pencairanprogram/getdetailpencairan', 'getdetailpencairan')->name('pencairanprogram.getdetailpencairan')->can('pencairanprogram.create');
        Route::post('/pencairanprogram/deletedetailpencairan', 'deletedetailpencairan')->name('pencairanprogram.deletedetailpencairan')->can('pencairanprogram.create');

        Route::get('/pencairanprogram/{kode_pencairan}/approve', 'approve')->name('pencairanprogram.approve')->can('pencairanprogram.approve');
        Route::post('/pencairanprogram/{kode_pencairan}/storeapprove', 'storeapprove')->name('pencairanprogram.storeapprove')->can('pencairanprogram.approve');
        Route::get('/pencairanprogram/{kode_pencairan}/cetak', 'cetak')->name('pencairanprogram.cetak')->can('pencairanprogram.show');

        Route::get('/pencairanprogram/{kode_pencairan}/upload', 'upload')->name('pencairanprogram.upload')->can('pencairanprogramikt.upload');
        Route::post('/pencairanprogram/{kode_pencairan}/storeupload', 'storeupload')->name('pencairanprogram.storeupload')->can('pencairanprogramikt.upload');
    });

    Route::controller(PencairanprogramikatanController::class)->group(function () {
        Route::get('/pencairanprogramikatan', 'index')->name('pencairanprogramikatan.index')->can('pencairanprogramikt.index');
        Route::get('/pencairanprogramikatan/create', 'create')->name('pencairanprogramikatan.create')->can('pencairanprogramikt.create');
        Route::post('/pencairanprogramikatan/store', 'store')->name('pencairanprogramikatan.store')->can('pencairanprogramikt.store');
        Route::get('/pencairanprogramikatan/{kode_pencairan}/setpencairan', 'setpencairan')->name('pencairanprogramikatan.setpencairan')->can('pencairanprogramikt.create');
        Route::delete('/pencairanprogramikatan/{kode_pencairan}/destroy', 'destroy')->name('pencairanprogramikatan.delete')->can('pencairanprogramikt.delete');
        Route::get('/pencairanprogramikatan/{kode_pencairan}/tambahpelanggan', 'tambahpelanggan')->name('pencairanprogramikatan.tambahpelanggan')->can('pencairanprogramikt.create');
        Route::post('/pencairanprogramikatan/getpelanggan', 'getpelanggan')->name('pencairanprogramikatan.getpelanggan')->can('pencairanprogramikt.create');
        Route::post('/pencairanprogramikatan/{kode_pencairan}/storepelanggan', 'storepelanggan')->name('pencairanprogramikatan.storepelanggan')->can('pencairanprogramikt.create');

        Route::get('/pencairanprogramikatan/{kode_pencairan}/approve', 'approve')->name('pencairanprogramikatan.approve')->can('ajuanprogramikatan.approve');
        Route::post('/pencairanprogramikatan/{kode_pencairan}/storeapprove', 'storeapprove')->name('pencairanprogramikatan.storeapprove')->can('ajuanprogramikatan.approve');
        // Route::get('/pencairanprogramikatan/{kode_pencairan}/{kode_pelanggan}/upload', 'upload')->name('pencairanprogramikatan.upload')->can('pencairanprogramikt.upload');
        Route::post('/pencairanprogramikatan/{kode_pencairan}/storeupload', 'storeupload')->name('pencairanprogramikatan.storeupload')->can('pencairanprogramikt.upload');
        Route::get('/pencairanprogramikatan/{kode_pencairan}/cetak', 'cetak')->name('pencairanprogramikatan.cetak')->can('pencairanprogramikt.show');
        Route::get('/pencairanprogramikatan/{kode_pelanggan}/{kode_pencairan}/detailfaktur', 'detailfaktur')->name('pencairanprogramikatan.detailfaktur')->can('pencairanprogram.create');
        Route::delete('/pencairanprogramikatan/{kode_pencairan}/{kode_pelanggan}/deletepelanggan', 'deletepelanggan')->name('pencairanprogramikatan.deletepelanggan')->can('pencairanprogram.create');
        Route::get('/pencairanprogramikatan/{kode_pencairan}/upload', 'upload')->name('pencairanprogramikatan.upload')->can('pencairanprogramikt.upload');
    });


    Route::controller(PencairanprogramenambulanController::class)->group(function () {
        Route::get('/pencairanprogramenambulan', 'index')->name('pencairanprogramenambulan.index')->can('pencairanprogramenambulan.index');
        Route::get('/pencairanprogramenambulan/create', 'create')->name('pencairanprogramenambulan.create')->can('pencairanprogramenambulan.create');
        Route::post('/pencairanprogramenambulan/store', 'store')->name('pencairanprogramenambulan.store')->can('pencairanprogramenambulan.store');
        Route::get('/pencairanprogramenambulan/{kode_pencairan}/setpencairan', 'setpencairan')->name('pencairanprogramenambulan.setpencairan')->can('pencairanprogramenambulan.create');

        Route::get('/pencairanprogramenambulan/{kode_pencairan}/tambahpelanggan', 'tambahpelanggan')->name('pencairanprogramenambulan.tambahpelanggan')->can('pencairanprogramenambulan.create');
        Route::post('/pencairanprogramenambulan/getpelanggan', 'getpelanggan')->name('pencairanprogramenambulan.getpelanggan')->can('pencairanprogramenambulan.create');
        Route::post('/pencairanprogramenambulan/{kode_pencairan}/storepelanggan', 'storepelanggan')->name('pencairanprogramenambulan.storepelanggan')->can('pencairanprogramenambulan.create');

        Route::get('/pencairanprogramenambulan/{kode_pencairan}/approve', 'approve')->name('pencairanprogramenambulan.approve')->can('ajuanprogramikatan.approve');
        Route::post('/pencairanprogramenambulan/{kode_pencairan}/storeapprove', 'storeapprove')->name('pencairanprogramenambulan.storeapprove')->can('ajuanprogramikatan.approve');
        // Route::get('/pencairanprogramikatan/{kode_pencairan}/{kode_pelanggan}/upload', 'upload')->name('pencairanprogramikatan.upload')->can('pencairanprogramikt.upload');
        Route::post('/pencairanprogramenambulan/{kode_pencairan}/storeupload', 'storeupload')->name('pencairanprogramenambulan.storeupload')->can('pencairanprogramenambulan.upload');
        Route::get('/pencairanprogramenambulan/{kode_pencairan}/cetak', 'cetak')->name('pencairanprogramenambulan.cetak')->can('pencairanprogramenambulan.show');
        Route::get('/pencairanprogramenambulan/{kode_pelanggan}/{kode_pencairan}/detailfaktur', 'detailfaktur')->name('pencairanprogramenambulan.detailfaktur')->can('pencairanprogramenambulan.create');
        Route::delete('/pencairanprogramenambulan/{kode_pencairan}/destroy', 'destroy')->name('pencairanprogramenambulan.delete')->can('pencairanprogramenambulan.delete');
        Route::delete('/pencairanprogramenambulan/{kode_pencairan}/{kode_pelanggan}/deletepelanggan', 'deletepelanggan')->name('pencairanprogramenambulan.deletepelanggan')->can('pencairanprogramenambulan.create');
        Route::get('/pencairanprogramenambulan/{kode_pencairan}/upload', 'upload')->name('pencairanprogramenambulan.upload')->can('pencairanprogramenambulan.upload');
    });


    Route::controller(AjuanprogramikatanController::class)->group(function () {
        Route::get('/ajuanprogramikatan', 'index')->name('ajuanprogramikatan.index')->can('ajuanprogramikatan.index');
        Route::get('/ajuanprogramikatan/create', 'create')->name('ajuanprogramikatan.create')->can('ajuanprogramikatan.create');
        Route::post('/ajuanprogramikatan/store', 'store')->name('ajuanprogramikatan.store')->can('ajuanprogramikatan.store');
        Route::get('/ajuanprogramikatan/{id}/edit', 'edit')->name('ajuanprogramikatan.edit')->can('ajuanprogramikatan.edit');
        Route::post('/ajuanprogramikatan/{id}/update', 'update')->name('ajuanprogramikatan.update')->can('ajuanprogramikatan.update');
        Route::delete('/ajuanprogramikatan/{id}/destroy', 'destroy')->name('ajuanprogramikatan.delete')->can('ajuanprogramikatan.delete');
        Route::get('/ajuanprogramikatan/{no_pengajuan}/setajuanprogramikatan', 'setajuanprogramikatan')->name('ajuanprogramikatan.setajuanprogramikatan')->can('ajuanprogramikatan.create');
        Route::get('/ajuanprogramikatan/{no_pengajuan}/tambahpelanggan', 'tambahpelanggan')->name('ajuanprogramikatan.tambahpelanggan')->can('ajuanprogramikatan.create');
        Route::post('/ajuanprogramikatan/{no_pengajuan}/storepelanggan', 'storepelanggan')->name('ajuanprogramikatan.storepelanggan')->can('ajuanprogramikatan.create');
        Route::get('/ajuanprogramikatan/{no_pengajuan}/{kode_pelanggan}/edit', 'editpelanggan')->name('ajuanprogramikatan.editpelanggan')->can('ajuanprogramikatan.create');
        Route::put('/ajuanprogramikatan/{no_pengajuan}/{kode_pelanggan}/updatepelanggan', 'updatepelanggan')->name('ajuanprogramikatan.updatepelanggan')->can('ajuanprogramikatan.create');
        Route::delete('/ajuanprogramikatan/{no_pengajuan}/{kode_pelanggan}/deletepelanggan', 'deletepelanggan')->name('ajuanprogramikatan.deletepelanggan')->can('ajuanprogramikatan.create');

        Route::get('/ajuanprogramikatan/getajuanprogramikatan', 'getajuanprogramikatan')->name('ajuanprogramikatan.getajuanprogramikatan')->can('ajuanprogramikatan.create');
        Route::get('/ajuanprogramikatan/{no_pengajuan}/approve', 'approve')->name('ajuanprogramikatan.approve')->can('ajuanprogramikatan.approve');
        Route::post('/ajuanprogramikatan/{no_pengajuan}/storeapprove', 'storeapprove')->name('ajuanprogramikatan.storeapprove')->can('ajuanprogramikatan.approve');
        Route::get('/ajuanprogramikatan/{no_pengajuan}/cetak', 'cetak')->name('ajuanprogramikatan.cetak')->can('ajuanprogramikatan.show');
        Route::get('/ajuanprogramikatan/{no_pengajuan}/{kode_pelanggan}/cetakkesepakatan', 'cetakkesepakatan')->name('ajuanprogramikatan.cetakkesepakatan')->can('ajuanprogramikatan.show');
        Route::get('/ajuanprogramikatan/{no_pengajuan}/{kode_pelanggan}/detailtarget', 'detailtarget')->name('ajuanprogramikatan.detailtarget')->can('ajuanprogramikatan.show');
    });

    Route::controller(AjuanprogramikatanenambulanController::class)->group(function () {
        Route::get('/ajuanprogramenambulan', 'index')->name('ajuanprogramenambulan.index')->can('ajuanprogramenambulan.index');
        Route::get('/ajuanprogramenambulan/create', 'create')->name('ajuanprogramenambulan.create')->can('ajuanprogramenambulan.create');
        Route::post('/ajuanprogramenambulan/store', 'store')->name('ajuanprogramenambulan.store')->can('ajuanprogramenambulan.store');

        Route::delete('/ajuanprogramenambulan/{no_pengajuan}/destroy', 'destroy')->name('ajuanprogramenambulan.delete')->can('ajuanprogramenambulan.delete');
        Route::get('/ajuanprogramenambulan/{no_pengajuan}/setajuanprogramenambulan', 'setajuanprogramenambulan')->name('ajuanprogramenambulan.setajuanprogramenambulan')->can('ajuanprogramenambulan.create');
        Route::get('/ajuanprogramenambulan/{no_pengajuan}/tambahpelanggan', 'tambahpelanggan')->name('ajuanprogramenambulan.tambahpelanggan')->can('ajuanprogramenambulan.create');
        Route::post('/ajuanprogramenambulan/{no_pengajuan}/storepelanggan', 'storepelanggan')->name('ajuanprogramenambulan.storepelanggan')->can('ajuanprogramenambulan.create');
        Route::delete('/ajuanprogramenambulan/{no_pengajuan}/{kode_pelanggan}/deletepelanggan', 'deletepelanggan')->name('ajuanprogramenambulan.deletepelanggan')->can('ajuanprogramenambulan.create');
        Route::get('/ajuanprogramenambulan/{no_pengajuan}/cetak', 'cetak')->name('ajuanprogramenambulan.cetak')->can('ajuanprogramenambulan.show');

        Route::get('/ajuanprogramenambulan/{no_pengajuan}/approve', 'approve')->name('ajuanprogramenambulan.approve')->can('ajuanprogramikatan.approve');
        Route::post('/ajuanprogramenambulan/{no_pengajuan}/storeapprove', 'storeapprove')->name('ajuanprogramenambulan.storeapprove')->can('ajuanprogramikatan.approve');
        Route::get('/ajuanprogramenambulan/{no_pengajuan}/{kode_pelanggan}/detailtarget', 'detailtarget')->name('ajuanprogramenambulan.detailtarget')->can('ajuanprogramikatan.show');
    });

    Route::controller(AjuanprogramkumulatifController::class)->group(function () {
        Route::get('/ajuankumulatif', 'index')->name('ajuankumulatif.index')->can('ajuankumulatif.index');
        Route::get('/ajuankumulatif/create', 'create')->name('ajuankumulatif.create')->can('ajuankumulatif.create');
        Route::post('/ajuankumulatif/store', 'store')->name('ajuankumulatif.store')->can('ajuankumulatif.store');
        Route::get('/ajuankumulatif/{id}/edit', 'edit')->name('ajuankumulatif.edit')->can('ajuankumulatif.edit');
        Route::post('/ajuankumulatif/{id}/update', 'update')->name('ajuankumulatif.update')->can('ajuankumulatif.update');
        Route::delete('/ajuankumulatif/{id}/destroy', 'destroy')->name('ajuankumulatif.delete')->can('ajuankumulatif.delete');
        Route::get('/ajuankumulatif/{no_pengajuan}/setajuankumulatif', 'setajuankumulatif')->name('ajuankumulatif.setajuankumulatif')->can('ajuankumulatif.create');
        Route::get('/ajuankumulatif/{no_pengajuan}/tambahpelanggan', 'tambahpelanggan')->name('ajuankumulatif.tambahpelanggan')->can('ajuankumulatif.create');
        Route::post('/ajuankumulatif/{no_pengajuan}/storepelanggan', 'storepelanggan')->name('ajuankumulatif.storepelanggan')->can('ajuankumulatif.create');
        Route::get('/ajuankumulatif/{no_pengajuan}/{kode_pelanggan}/editpelanggan', 'editpelanggan')->name('ajuankumulatif.editpelanggan')->can('ajuankumulatif.create');
        Route::put('/ajuankumulatif/{no_pengajuan}/{kode_pelanggan}/updatepelanggan', 'updatepelanggan')->name('ajuankumulatif.updatepelanggan')->can('ajuankumulatif.create');
        Route::delete('/ajuankumulatif/{no_pengajuan}/{kode_pelanggan}/deletepelanggan', 'deletepelanggan')->name('ajuankumulatif.deletepelanggan')->can('ajuankumulatif.create');

        Route::get('/ajuankumulatif/getajuankumulatif', 'getajuankumulatif')->name('ajuankumulatif.getajuankumulatif')->can('ajuankumulatif.create');
        Route::get('/ajuankumulatif/{no_pengajuan}/approve', 'approve')->name('ajuankumulatif.approve')->can('ajuankumulatif.approve');
        Route::post('/ajuankumulatif/{no_pengajuan}/storeapprove', 'storeapprove')->name('ajuankumulatif.storeapprove')->can('ajuankumulatif.approve');
        Route::get('/ajuankumulatif/{no_pengajuan}/cetak', 'cetak')->name('ajuankumulatif.cetak')->can('ajuankumulatif.show');
        Route::get('/ajuankumulatif/{no_pengajuan}/{kode_pelanggan}/cetakkesepakatan', 'cetakkesepakatan')->name('ajuankumulatif.cetakkesepakatan')->can('ajuankumulatif.show');
    });

    Route::controller(ActivitylogController::class)->group(function () {
        Route::get('/activitylog', 'index')->name('activitylog.index')->can('activitylog.index');
    });

    Route::controller(TicketController::class)->group(function () {
        Route::get('/ticket', 'index')->name('ticket.index');
        Route::get('/ticket/create', 'create')->name('ticket.create');
        Route::post('/ticket/store', 'store')->name('ticket.store');
        Route::get('/ticket/{kode_pengajuan}/edit', 'edit')->name('ticket.edit');
        Route::put('/ticket/{kode_pengajuan}/update', 'update')->name('ticket.update');
        Route::delete('/ticket/{no_pengajuan}/destroy', 'destroy')->name('ticket.delete');
        Route::get('/ticket/{no_pengajuan}/approve', 'approve')->name('ticket.approve')->can('ticket.approve');
        Route::post('/ticket/{no_pengajuan}/storeapprove', 'storeapprove')->name('ticket.storeapprove')->can('ticket.approve');
        Route::get('/ticket/{no_pengajuan}/message', 'message')->name('ticket.message');
        Route::post('/ticket/{no_pengajuan}/storemessage', 'storemessage')->name('ticket.storemessage');
    });

    Route::controller(TicketupdateController::class)->group(function () {
        Route::get('/ticketupdate', 'index')->name('ticketupdate.index');
        Route::get('/ticketupdate/create', 'create')->name('ticketupdate.create');
        Route::post('/ticketupdate/store', 'store')->name('ticketupdate.store');
        Route::get('/ticketupdate/{kode_pengajuan}/edit', 'edit')->name('ticketupdate.edit');
        Route::put('/ticketupdate/{kode_pengajuan}/update', 'update')->name('ticketupdate.update');
        Route::delete('/ticketupdate/{no_pengajuan}/destroy', 'destroy')->name('ticketupdate.delete');
        Route::get('/ticketupdate/{no_pengajuan}/approve', 'approve')->name('ticketupdate.approve')->can('ticket.approve');
        Route::delete('/ticketupdate/{no_pengajuan}/cancel', 'cancel')->name('ticketupdate.cancel')->can('ticket.approve');
        Route::post('/ticketupdate/{no_pengajuan}/storeapprove', 'storeapprove')->name('ticketupdate.storeapprove')->can('ticket.approve');
    });

    Route::controller(MutasikeuanganController::class)->group(function () {
        Route::get('/mutasikeuangan', 'index')->name('mutasikeuangan.index')->can('mutasikeuangan.index');
        Route::get('/mutasikeuangan/create', 'create')->name('mutasikeuangan.create')->can('mutasikeuangan.create');
        Route::post('/mutasikeuangan/store', 'store')->name('mutasikeuangan.store')->can('mutasikeuangan.store');
        Route::get('/mutasikeuangan/{id}/edit', 'edit')->name('mutasikeuangan.edit')->can('mutasikeuangan.edit');
        Route::put('/mutasikeuangan/{id}/update', 'update')->name('mutasikeuangan.update')->can('mutasikeuangan.update');
        Route::delete('/mutasikeuangan/{id}/destroy', 'destroy')->name('mutasikeuangan.delete')->can('mutasikeuangan.delete');
        Route::get('/mutasikeuangan/{kode_bank}/{dari}/{sampai}/show', 'show')->name('mutasikeuangan.show');
        Route::get('/mutasikeuangan/showmutasikategori', 'showmutasikategori')->name('mutasikeuangan.showmutasikategori');
    });


    Route::controller(SaldoawalkaskecilController::class)->group(function () {
        Route::get('/sakaskecil', 'index')->name('sakaskecil.index')->can('sakaskecil.index');
        Route::get('/sakaskecil/create', 'create')->name('sakaskecil.create')->can('sakaskecil.create');
        Route::post('/sakaskecil/store', 'store')->name('sakaskecil.store')->can('sakaskecil.store');
        Route::get('/sakaskecil/{no_bukti}/edit', 'edit')->name('sakaskecil.edit')->can('sakaskecil.edit');
        Route::put('/sakaskecil/{no_bukti}/update', 'update')->name('sakaskecil.update')->can('sakaskecil.update');
        Route::delete('/sakaskecil/{no_bukti}/delete', 'destroy')->name('sakaskecil.delete')->can('sakaskecil.delete');

        Route::post('/sakaskecil/getsaldo', 'getsaldo')->name('sakaskecil.getsaldo');
    });

    Route::get('/worksheetom/oman', [OmancabangController::class, 'index'])->name('worksheetom.oman')->can('worksheetom.oman');
    Route::get('/worksheetom/komisisalesman', [LaporanmarketingController::class, 'index'])->name('worksheetom.komisisalesman')->can('worksheetom.komisisalesman');
    Route::get('/worksheetom/insentifom', [LaporanmarketingController::class, 'insentifom'])->name('worksheetom.insentifom')->can('worksheetom.insentifom');
    Route::get('/worksheetom/komisidriverhelper', [LaporanmarketingController::class, 'index'])->name('worksheetom.komisidriverhelper')->can('worksheetom.komisidriverhelper');
    Route::get('/worksheetom/costratio', [CostratioController::class, 'index'])->name('worksheetom.costratio')->can('worksheetom.costratio');
    Route::get('/worksheetom/visitpelanggan', [VisitpelangganController::class, 'index'])->name('worksheetom.visitpelanggan')->can('worksheetom.visitpelanggan');
    Route::get('/worksheetom/monitoringretur', [MonitoringreturController::class, 'index'])->name('worksheetom.monitoringretur')->can('worksheetom.monitoringretur');
    Route::get('/worksheetom/monitoringprogram', [WorksheetomController::class, 'monitoringprogram'])->name('worksheetom.monitoringprogram')->can('worksheetom.monitoringprogram');
    Route::get('/worksheetom/kebutuhancabang', [WorksheetomController::class, 'kebutuhancabang'])->name('worksheetom.kebutuhancabang')->can('worksheetom.kebutuhancabang');
    Route::get('/worksheetom/produkexpired', [WorksheetomController::class, 'produkexpired'])->name('worksheetom.produkexpired')->can('worksheetom.produkexpired');
    Route::get('/worksheetom/evaluasisharing', [WorksheetomController::class, 'evaluasisharing'])->name('worksheetom.evaluasisharing')->can('worksheetom.evaluasisharing');
    Route::get('/worksheetom/bbm', [WorksheetomController::class, 'bbm'])->name('worksheetom.bbm')->can('worksheetom.bbm');
    Route::get('/worksheetom/ratiobs', [LaporanmarketingController::class, 'ratiobs'])->name('worksheetom.ratiobs')->can('worksheetom.ratiobs');


    Route::controller(SaldoawalbukubesarController::class)->group(function () {
        Route::get('/saldoawalbukubesar', 'index')->name('saldoawalbukubesar.index')->can('saldoawalbukubesar.index');
        Route::get('/saldoawalbukubesar/create', 'create')->name('saldoawalbukubesar.create')->can('saldoawalbukubesar.create');
        Route::post('/saldoawalbukubesar/store', 'store')->name('saldoawalbukubesar.store')->can('saldoawalbukubesar.store');
        Route::get('/saldoawalbukubesar/{kode_saldo_awal}/show', 'show')->name('saldoawalbukubesar.show')->can('saldoawalbukubesar.show');
        Route::get('/saldoawalbukubesar/{kode_saldo_awal}/edit', 'edit')->name('saldoawalbukubesar.edit')->can('saldoawalbukubesar.edit');
        Route::put('/saldoawalbukubesar/{kode_saldo_awal}/update', 'update')->name('saldoawalbukubesar.update')->can('saldoawalbukubesar.update');
        Route::delete('/saldoawalbukubesar/{kode_saldo_awal}/delete', 'destroy')->name('saldoawalbukubesar.delete')->can('saldoawalbukubesar.delete');

        Route::post('/saldoawalbukubesar/getsaldo', 'getsaldo')->name('saldoawalbukubesar.getsaldo');
    });

    // Backup Database Routes
    Route::controller(BackupDatabaseController::class)->group(function () {
        Route::get('/backup-database', 'index')->name('backup.database.index')->can('backup.database');
        Route::post('/backup-database/create', 'create')->name('backup.database.create')->can('backup.database');
        Route::post('/backup-database/create-large', 'createLargeBackup')->name('backup.database.create.large')->can('backup.database');
        Route::get('/backup-database/{filename}/download', 'download')->name('backup.database.download')->can('backup.database');
        Route::get('/backup-database/{filename}/resume-download', 'resumeDownload')->name('backup.database.resume.download')->can('backup.database');
        Route::delete('/backup-database/{filename}/destroy', 'destroy')->name('backup.database.destroy')->can('backup.database');

        // Streaming download langsung dari database
        Route::get('/backup-database/stream-download', 'streamDownloadFromDatabase')->name('backup.database.stream.download')->can('backup.database');
        Route::get('/backup-database/stream-download-progress', 'streamDownloadWithProgress')->name('backup.database.stream.download.progress')->can('backup.database');
    });
});


Route::get('/createrolepermission', function () {

    try {
        Role::create(['name' => 'super admin']);
        // Permission::create(['name' => 'view-karyawan']);
        // Permission::create(['name' => 'view-departemen']);
        echo "Sukses";
    } catch (\Exception $e) {
        echo "Error";
    }
});


Route::get('/assignrole', function () {
    // $permissiongroup = Permission_group::create([
    //     'name' => 'Surat Jalan'
    // ]);

    // dd($permissiongroup->id);
    // Daftar ID pengguna yang akan diberikan role
    $userIds = [3, 230];

    // Ambil role yang ingin diberikan
    $role = Role::findByName('admin penjualan');

    // Cari pengguna berdasarkan ID dan berikan role
    $users = User::whereIn('id', $userIds)->get();

    foreach ($users as $user) {
        $user->assignRole($role);
    }
});
require __DIR__ . '/auth.php';

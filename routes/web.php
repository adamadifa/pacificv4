<?php

use App\Http\Controllers\AngkutanController;
use App\Http\Controllers\BarangkeluargudangbahanController;
use App\Http\Controllers\BarangkeluargudanglogistikController;
use App\Http\Controllers\BarangkeluarproduksiController;
use App\Http\Controllers\BarangmasukgudangbahanController;
use App\Http\Controllers\BarangmasukgudanglogistikController;
use App\Http\Controllers\BarangmasukproduksiController;
use App\Http\Controllers\BarangpembelianController;
use App\Http\Controllers\BarangproduksiController;
use App\Http\Controllers\BpbjController;
use App\Http\Controllers\BpjskesehatanController;
use App\Http\Controllers\BpjstenagakerjaController;
use App\Http\Controllers\BufferstokController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DpbController;
use App\Http\Controllers\DriverhelperController;
use App\Http\Controllers\FsthpController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\HargaController;
use App\Http\Controllers\InsentifController;
use App\Http\Controllers\JenisprodukController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriprodukController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\KirimpusatController;
use App\Http\Controllers\LainnyagudangjadiController;
use App\Http\Controllers\LaporangudangbahanController;
use App\Http\Controllers\LaporangudangcabangController;
use App\Http\Controllers\LaporangudangjadiController;
use App\Http\Controllers\LaporangudanglogistikController;
use App\Http\Controllers\LaporanproduksiController;
use App\Http\Controllers\MutasidpbController;
use App\Http\Controllers\OmancabangController;
use App\Http\Controllers\OmanController;
use App\Http\Controllers\OpnamegudangbahanController;
use App\Http\Controllers\OpnamegudanglogistikController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenyesuaiangudangcabangController;
use App\Http\Controllers\PermintaankirimanController;
use App\Http\Controllers\PermintaanproduksiController;
use App\Http\Controllers\Permission_groupController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatiodriverhelperController;
use App\Http\Controllers\RegionalController;
use App\Http\Controllers\RejectController;
use App\Http\Controllers\RejectgudangjadiController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\RepackgudangcabangController;
use App\Http\Controllers\RepackgudangjadiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaldoawalbarangproduksiController;
use App\Http\Controllers\SaldoawalgudangbahanController;
use App\Http\Controllers\SaldoawalgudangcabangController;
use App\Http\Controllers\SaldoawalgudangjadiController;
use App\Http\Controllers\SaldoawalgudanglogistikController;
use App\Http\Controllers\SaldoawalhargagudangbahanController;
use App\Http\Controllers\SaldoawalmutasiproduksiController;
use App\Http\Controllers\SalesmanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SuratjalanangkutanController;
use App\Http\Controllers\SuratjalanController;
use App\Http\Controllers\TargetkomisiController;
use App\Http\Controllers\TransitinController;
use App\Http\Controllers\TujuanangkutanController;
use App\Http\Controllers\TutuplaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WilayahController;
use App\Models\Barangkeluargudangbahan;
use App\Models\Barangproduksi;
use App\Models\Permission_group;
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



Route::middleware('auth')->group(function () {




    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //Dashboard
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/dashboard/produksi', 'produksi')->name('dashboard.produksi')->can('dashboard.produksi');
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
        Route::put('/users/{id}/update', 'update')->name('users.update');
        Route::delete('/users/{id}/delete', 'destroy')->name('users.delete');
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
    });

    Route::controller(PelangganController::class)->group(function () {
        Route::get('/pelanggan', 'index')->name('pelanggan.index')->can('pelanggan.index');
        Route::get('/pelanggan/create', 'create')->name('pelanggan.create')->can('pelanggan.create');
        Route::post('/pelanggan', 'store')->name('pelanggan.store')->can('pelanggan.store');
        Route::get('/pelanggan/{kode_pelanggan}/edit', 'edit')->name('pelanggan.edit')->can('pelanggan.edit');
        Route::put('/pelanggan/{kode_pelanggan}', 'update')->name('pelanggan.update')->can('pelanggan.update');
        Route::delete('/pelanggan/{kode_pelanggan}', 'destroy')->name('pelanggan.delete')->can('pelanggan.delete');
        Route::get('/pelanggan/{kode_pelanggan}/show', 'show')->name('pelanggan.show')->can('pelanggan.show');
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
        Route::get('/suratjalanangkutan/create', 'create')->name('suratjalanangkutan.create')->can('suratjalanangkutan.create');
        Route::post('/suratjalanangkutan', 'store')->name('suratjalanangkutan.store')->can('suratjalanangkutan.store');
        Route::delete('/suratjalanangkutan/{kode_saldo_awal}', 'destroy')->name('suratjalanangkutan.delete')->can('suratjalanangkutan.delete');
        Route::get('/suratjalanangkutan/{kode_saldo_awal}/show', 'show')->name('suratjalanangkutan.show')->can('suratjalanangkutan.show');
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

    Route::controller(TutuplaporanController::class)->group(function () {


        //Ajax Request
        Route::post('/tutuplaporan/cektutuplaporan', 'cektutuplaporan');
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


Route::get('/creategrouppermission', function () {
    $permissiongroup = Permission_group::create([
        'name' => 'Surat Jalan'
    ]);

    dd($permissiongroup->id);
});
require __DIR__ . '/auth.php';

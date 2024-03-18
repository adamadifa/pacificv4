<?php

use App\Http\Controllers\BarangkeluarproduksiController;
use App\Http\Controllers\BarangmasukproduksiController;
use App\Http\Controllers\BarangproduksiController;
use App\Http\Controllers\BpbjController;
use App\Http\Controllers\BpjskesehatanController;
use App\Http\Controllers\BpjstenagakerjaController;
use App\Http\Controllers\BufferstokController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\FsthpController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\HargaController;
use App\Http\Controllers\InsentifController;
use App\Http\Controllers\JenisprodukController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriprodukController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\Permission_groupController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionalController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaldoawalbarangproduksiController;
use App\Http\Controllers\SaldoawalmutasiproduksiController;
use App\Http\Controllers\SalesmanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TutuplaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WilayahController;
use App\Models\Barangproduksi;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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


    Route::controller(KendaraanController::class)->group(function () {
        Route::get('/kendaraan', 'index')->name('kendaraan.index')->can('kendaraan.index');
        Route::get('/kendaraan/create', 'create')->name('kendaraan.create')->can('kendaraan.create');
        Route::post('/kendaraan', 'store')->name('kendaraan.store')->can('kendaraan.store');
        Route::get('/kendaraan/{kode_kendaraan}/edit', 'edit')->name('kendaraan.edit')->can('kendaraan.edit');
        Route::put('/kendaraan/{kode_kendaraan}', 'update')->name('kendaraan.update')->can('kendaraan.update');
        Route::delete('/kendaraan/{kode_kendaraan}', 'destroy')->name('kendaraan.delete')->can('kendaraan.delete');
        Route::get('/kendaraan/{kode_kendaraan}/show', 'show')->name('kendaraan.show')->can('kendaraan.show');
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
    });

    Route::controller(FsthpController::class)->group(function () {
        Route::get('/fsthp', 'index')->name('fsthp.index')->can('fsthp.index');
        Route::get('/fsthp/create', 'create')->name('fsthp.create')->can('fsthp.create');
        Route::post('/fsthp', 'store')->name('fsthp.store')->can('fsthp.store');
        Route::delete('/fsthp/{no_mutasi}', 'destroy')->name('fsthp.delete')->can('fsthp.delete');
        Route::get('/fsthp/{no_mutasi}/show', 'show')->name('fsthp.show')->can('fsthp.show');
        Route::get('/fsthp/{no_mutasi}/approve', 'approve')->name('fsthp.approve')->can('fsthp.approve');
        Route::get('/fsthp/{no_mutasi}/cancel', 'cancel')->name('fsthp.cancel')->can('fsthp.approve');

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

require __DIR__ . '/auth.php';

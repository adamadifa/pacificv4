<?php

use App\Models\Produk;
use App\Models\Tutuplaporan;
use Illuminate\Support\Facades\Redirect;

function buatkode($nomor_terakhir, $kunci, $jumlah_karakter = 0)
{
    /* mencari nomor baru dengan memecah nomor terakhir dan menambahkan 1
    string nomor baru dibawah ini harus dengan format XXX000000
    untuk penggunaan dalam format lain anda harus menyesuaikan sendiri */
    $nomor_baru = intval(substr($nomor_terakhir, strlen($kunci))) + 1;
    //    menambahkan nol didepan nomor baru sesuai panjang jumlah karakter
    $nomor_baru_plus_nol = str_pad($nomor_baru, $jumlah_karakter, "0", STR_PAD_LEFT);
    //    menyusun kunci dan nomor baru
    $kode = $kunci . $nomor_baru_plus_nol;
    return $kode;
}

function messageSuccess($message)
{
    return ['success' => $message];
}


function messageError($message)
{
    return ['error' => $message];
}


// Mengubah ke Huruf Besar
function textUpperCase($value)
{
    return strtoupper(strtolower($value));
}
// Mengubah ke CamelCase
function textCamelCase($value)
{
    return ucwords(strtolower($value));
}


function getdocMarker($file)
{
    $url = url('/storage/marker/' . $file);
    return $url;
}


function getfotoPelanggan($file)
{
    $url = url('/storage/pelanggan/' . $file);
    return $url;
}


function getfotoKaryawan($file)
{
    $url = url('/storage/karyawan/' . $file);
    return $url;
}


function toNumber($value)
{
    if (!empty($value)) {
        return str_replace([".", ","], ["", "."], $value);
    } else {
        return 0;
    }
}


function formatRupiah($nilai)
{
    return number_format($nilai, '0', ',', '.');
}

function formatAngka($nilai)
{
    if (!empty($nilai)) {
        return number_format($nilai, '0', ',', '.');
    }
}


function formatAngkaDesimal($nilai)
{
    if (!empty($nilai)) {
        return number_format($nilai, '2', ',', '.');
    }
}

function formatAngkaDesimal3($nilai)
{
    if (!empty($nilai)) {
        return number_format($nilai, '3', ',', '.');
    }
}






function DateToIndo($date2)
{ // fungsi atau method untuk mengubah tanggal ke format indonesia
    // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
    $BulanIndo2 = array(
        "Januari", "Februari", "Maret",
        "April", "Mei", "Juni",
        "Juli", "Agustus", "September",
        "Oktober", "November", "Desember"
    );

    if (!empty($date2)) {
        $tahun2 = substr($date2, 0, 4); // memisahkan format tahun menggunakan substring
        $bulan2 = substr($date2, 5, 2); // memisahkan format bulan menggunakan substring
        $tgl2   = substr($date2, 8, 2); // memisahkan format tanggal menggunakan substring

        $result = $tgl2 . " " . $BulanIndo2[(int)$bulan2 - 1] . " " . $tahun2;
        return ($result);
    } else {
        return "";
    }
}


function cektutupLaporan($tgl, $jenislaporan)
{
    $tanggal = explode("-", $tgl);
    $bulan = $tanggal[1];
    $tahun = $tanggal[0];
    $cek = Tutuplaporan::where('jenis_laporan', $jenislaporan)
        ->where('bulan', $bulan)
        ->where('tahun', $tahun)
        ->where('status', 1)
        ->count();
    return $cek;
}


function getbulandantahunlalu($bulan, $tahun, $show)
{
    if ($bulan == 1) {
        $bulanlalu = 12;
        $tahunlalu = $tahun - 1;
    } else {
        $bulanlalu = $bulan - 1;
        $tahunlalu = $tahun;
    }

    if ($show == "tahun") {
        return $tahunlalu;
    } elseif ($show == "bulan") {
        return $bulanlalu;
    }
}


function getbulandantahunberikutnya($bulan, $tahun, $show)
{
    if ($bulan == 12) {
        $bulanberikutnya =  1;
        $tahunberikutnya = $tahun + 1;
    } else {
        $bulanberikutnya = $bulan + 1;
        $tahunberikutnya = $tahun;
    }

    if ($show == "tahun") {
        return $tahunberikutnya;
    } elseif ($show == "bulan") {
        return $bulanberikutnya;
    }
}


function lockreport($tanggal)
{
    $start_year = config('global.start_year');
    $lock_date = $start_year . "-01-01";

    if ($tanggal < $lock_date && !empty($tanggal)) {
        return "error";
    } else {
        return "success";
    }
}



function getBeratliter($tanggal)
{
    if ($tanggal <= "2022-03-01") {
        $berat = 0.9064;
    } else {
        $berat = 1;
    }
    return $berat;
}


function convertToduspackpcs($kode_produk, $jumlah)
{
    $produk = Produk::where('kode_produk', $kode_produk)->first();
    $jml_dus = floor($jumlah / $produk->isi_pcs_dus);
    $sisa_dus = $jumlah % $produk->isi_pcs_dus;
    if (!empty($produk->isi_pack_dus)) {
        $jml_pack = floor($sisa_dus / $produk->isi_pcs_pack);
        $sisa_pack = $sisa_dus % $produk->isi_pcs_pack;
    } else {
        $jml_pack = 0;
        $sisa_pack = $sisa_dus;
    }
    $jml_pcs = $sisa_pack;

    return $jml_dus . "|" . $jml_pack . "|" . $jml_pcs;
}



function convertToduspackpcsv2($isi_pcs_dus, $isi_pcs_pack, $jumlah)
{

    $jml_dus = floor($jumlah / $isi_pcs_dus);
    $sisa_dus = $jumlah % $isi_pcs_dus;
    if (!empty($isi_pcs_pack)) {
        $jml_pack = floor($sisa_dus / $isi_pcs_pack);
        $sisa_pack = $sisa_dus % $isi_pcs_pack;
    } else {
        $jml_pack = 0;
        $sisa_pack = $sisa_dus;
    }
    $jml_pcs = $sisa_pack;

    return $jml_dus . "|" . $jml_pack . "|" . $jml_pcs;
}


function convertToduspackpcsv3($isi_pcs_dus, $isi_pcs_pack, $jumlah)
{

    $jml_dus = floor($jumlah / $isi_pcs_dus);
    $sisa_dus = $jumlah % $isi_pcs_dus;
    if (!empty($isi_pcs_pack)) {
        $jml_pack = floor($sisa_dus / $isi_pcs_pack);
        $sisa_pack = $sisa_dus % $isi_pcs_pack;
    } else {
        $jml_pack = 0;
        $sisa_pack = $sisa_dus;
    }
    $jml_pcs = $sisa_pack;

    return array($jml_dus, $jml_pack, $jml_pcs);
}

function getSignature($file)
{
    $url = url('/storage/signature/' . $file);
    return $url;
}


function penyebut($nilai)
{
    $nilai = abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
        $temp = penyebut($nilai - 10) . " belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
    }
    return $temp;
}

function terbilang($nilai)
{
    if ($nilai < 0) {
        $hasil = "minus " . trim(penyebut($nilai));
    } else {
        $hasil = trim(penyebut($nilai));
    }
    return $hasil;
}


function getAkunpiutangcabang($kode_cabang)
{
    if ($kode_cabang == 'TSM') {
        $akun = "1-1468";
    } else if ($kode_cabang == 'BDG') {
        $akun = "1-1402";
    } else if ($kode_cabang == 'BGR') {
        $akun = "1-1403";
    } else if ($kode_cabang == 'PWT') {
        $akun = "1-1404";
    } else if ($kode_cabang == 'TGL') {
        $akun = "1-1405";
    } else if ($kode_cabang == "SKB") {
        $akun = "1-1407";
    } else if ($kode_cabang == "GRT") {
        $akun = "1-1487";
    } else if ($kode_cabang == "SMR") {
        $akun = "1-1488";
    } else if ($kode_cabang == "SBY") {
        $akun = "1-1486";
    } else if ($kode_cabang == "PST") {
        $akun = "1-1489";
    } else if ($kode_cabang == "KLT") {
        $akun = "1-1490";
    } else if ($kode_cabang == "PWK") {
        $akun = "1-1492";
    } else if ($kode_cabang == "BTN") {
        $akun = "1-1493";
    } else if ($kode_cabang == "BKI") {
        $akun = "1-1494";
    } else if ($kode_cabang == "TGR") {
        $akun = "1-1495";
    } else {
        $akun = "99";
    }

    return $akun;
}


function formatIndo($date)
{
    $tanggal = !empty($date) ? date('d-m-Y', strtotime($date)) : '';
    return $tanggal;
}

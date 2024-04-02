<?php

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
    return str_replace([".", ","], ["", "."], $value);
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



function DateToIndo($date2)
{ // fungsi atau method untuk mengubah tanggal ke format indonesia
    // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
    $BulanIndo2 = array(
        "Januari", "Februari", "Maret",
        "April", "Mei", "Juni",
        "Juli", "Agustus", "September",
        "Oktober", "November", "Desember"
    );

    $tahun2 = substr($date2, 0, 4); // memisahkan format tahun menggunakan substring
    $bulan2 = substr($date2, 5, 2); // memisahkan format bulan menggunakan substring
    $tgl2   = substr($date2, 8, 2); // memisahkan format tanggal menggunakan substring

    $result = $tgl2 . " " . $BulanIndo2[(int)$bulan2 - 1] . " " . $tahun2;
    return ($result);
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

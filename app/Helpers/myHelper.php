<?php

use App\Models\Produk;
use App\Models\Tutuplaporan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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

function getfotoPelangganowner($file)
{
    $url = url('/storage/pelanggan/owner/' . $file);
    return $url;
}

function getfotoAktifitias($file)
{
    $url = url('/storage/uploads/aktifitas_smm/' . $file);
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

function formatAngkaDesimal5($nilai)
{
    if (!empty($nilai)) {
        return number_format($nilai, '5', ',', '.');
    }
}







function DateToIndo($date2)
{ // fungsi atau method untuk mengubah tanggal ke format indonesia
    // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
    $BulanIndo2 = array(
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember"
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
    } else if ($kode_cabang == "CRB") {
        $akun = "1-1496";
    } else {
        $akun = "99";
    }

    return $akun;
}


function getAkunkaskecil($kode_cabang)
{
    $akun = [
        'BDG' => '1-1102',
        'BGR' => '1-1103',
        'PST' => '1-1111',
        'TSM' => '1-1112',
        'SKB' => '1-1113',
        'PWT' => '1-1114',
        'TGL' => '1-1115',
        'SBY' => '1-1116',
        'SMR' => '1-1117',
        'KLT' => '1-1118',
        'GRT' => '1-1119',
        'PWK' => '1-1120',
        'BTN' => '1-1121',
        'BKI' => '1-1122',
        'TGR' => '1-1123',
        'CRB' => '1-1124'
    ];

    return $akun[$kode_cabang];
}


function formatIndo($date)
{
    $tanggal = !empty($date) ? date('d-m-Y', strtotime($date)) : '';
    return $tanggal;
}

function formatIndo2($date)
{
    $tanggal = !empty($date) ? date('d-m-y', strtotime($date)) : '';
    return $tanggal;
}


function calculateMonths($date1, $date2)
{

    // Parsing tanggal
    $date1 = Carbon::parse($date1);
    $date2 = Carbon::parse($date2);

    // Menghitung jumlah bulan
    $months = $date1->diffInMonths($date2);

    // Mengembalikan hasil sebagai JSON
    return $months + 1;
}


function calculateMonthsKontrak($date1, $date2)
{

    // Parsing tanggal
    $date1 = Carbon::parse($date1);
    $date2 = Carbon::parse($date2);

    // Menghitung jumlah bulan
    $months = $date1->diffInMonths($date2);

    // Mengembalikan hasil sebagai JSON
    return $months;
}

function hitungJumlahHari($tanggal_awal, $tanggal_akhir)
{
    $start_date = Carbon::parse($tanggal_awal);
    $end_date = Carbon::parse($tanggal_akhir);

    $jumlah_hari = $start_date->diffInDays($end_date);

    return $jumlah_hari;
}



function hitungJmk($masa_kerja)
{
    $jmlkali = 1;
    if ($masa_kerja >= 3 && $masa_kerja < 6) {
        $jmlkali = 2;
    } elseif ($masa_kerja >= 6 && $masa_kerja < 9) {
        $jmlkali = 3;
    } elseif ($masa_kerja >= 9 && $masa_kerja < 12) {
        $jmlkali = 4;
    } elseif ($masa_kerja >= 12 && $masa_kerja < 15) {
        $jmlkali = 5;
    } elseif ($masa_kerja >= 15 && $masa_kerja < 18) {
        $jmlkali = 6;
    } elseif ($masa_kerja >= 18 && $masa_kerja < 21) {
        $jmlkali = 7;
    } elseif ($masa_kerja >= 21 && $masa_kerja < 24) {
        $jmlkali = 8;
    } elseif ($masa_kerja >= 24) {
        $jmlkali = 10;
    }

    return $jmlkali;
}


function hitungMasakerja($tanggal_masuk, $tanggal_sampai)
{
    $joinDate = Carbon::parse($tanggal_masuk);
    $currentDate = Carbon::parse($tanggal_sampai);

    $diffYears = $joinDate->diffInYears($currentDate);
    $diffMonths = $joinDate->copy()->addYears($diffYears)->diffInMonths($currentDate);
    $diffDays = $joinDate->copy()->addYears($diffYears)->addMonths($diffMonths)->diffInDays($currentDate);

    return [
        'tahun' => $diffYears,
        'bulan' => $diffMonths,
        'hari' => $diffDays
    ];
}

function removeSpecialCharacters($string)
{
    return preg_replace('/[^a-zA-Z0-9]/', '', $string);
}

function formatName($fullName)
{
    // Pisahkan string menjadi array kata-kata
    $words = explode(' ', $fullName);

    // Jika ada lebih dari 3 kata
    if (count($words) >= 3) {
        // Ambil dua kata pertama
        $firstTwoWords = array_slice($words, 0, 2);

        // Ambil huruf pertama dari setiap kata setelah dua kata pertama
        $initials = array_map(function ($word) {
            return strtoupper($word[0]);
        }, array_slice($words, 2));

        // Gabungkan dua kata pertama dengan inisial-inisial
        $formattedName = implode(' ', $firstTwoWords) . ' ' . implode('', $initials);
    } else {
        // Jika tidak lebih dari 3 kata, gunakan nama asli
        $formattedName = $fullName;
    }

    return $formattedName;
}


function pihakpertamacabang($cabang, $perusahaan)
{
    $kepalaadmin = [
        'PWT' => 'Galuh Setiaji W',
        'BTN' => 'Anif Ardiana',
        'BDG' => 'Mohamad Ridwan Fauzi',
        'SKB' => 'Aceng Cahya Sugianto',
        'TGL' => 'Rosihul Iman',
        'SBY' => 'Angga Wahyu P',
        'SMR' => 'Muh. Fahmi Fadil',
        'KLT' => 'Fikkry Yusuf',
        'BGR' => 'Rizki Adam Husaeni',
        'GRT' => 'Nurman Susila',
        'BKI' => 'Victor Simatupang',
        'PWK' => 'M. Hirzam Purnama',
        'TSM' => 'Dade Gunawan',
        'TGR' => 'Ardi Kurniawan'
    ];


    $kepalapenjualan = [
        'PWT' => 'Yeni Listiana S',
        'TGR' => 'Robertus David',
        'BDG' => 'Dasep Reski Soejani',
        'SKB' => 'Asep Yusuf',
        'TGL' => 'Imam Syafangat',
        'SBY' => 'Angga Wahyu',
        'SMR' => 'Muhammad Luthfi Amri',
        'KLT' => 'Alip Aswanto',
        'BGR' => 'Muhammad Nuarry Iqbal',
        'GRT' => 'Radea Feryzal, ST',
        'BKI' => 'Sumarido Tanjung',
        'PWK' => 'M. Ridwan Nugraha',
        'TSM' => 'Aceng Saepul Anwar',
        'BTN' => 'Mauldy',
        'CRB' => 'Ari Ricardo'
    ];

    if ($perusahaan == "MP") {
        return $kepalaadmin[$cabang];
    } else {
        return $kepalapenjualan[$cabang];
    }
}


function singkatString($string)
{
    $words = explode(' ', $string);

    // Jika string terdiri dari tepat 3 kata, buat singkatan huruf besar
    if (count($words) === 3) {
        $abbreviation = '';

        foreach ($words as $word) {
            if (strlen($word) >= 3) {
                $abbreviation .= strtoupper($word[0]);
            }
        }

        return $abbreviation;
    }

    // Jika tidak, buat camelCase
    return ucwords(strtolower($string));
}

function formatName2($name)
{
    $words = explode(' ', $name);
    return implode(' ', array_slice($words, 0, 2));
}


function hitungDurasi($waktuMulai, $waktuSelesai)
{
    // Mengubah waktu ke format DateTime
    $mulai = new DateTime($waktuMulai);
    $selesai = new DateTime($waktuSelesai);

    // Menghitung selisih antara dua waktu
    $interval = $mulai->diff($selesai);

    // Mengambil durasi dalam jam dan menit
    $jam = $interval->h;
    $menit = $interval->i;

    // Jika durasi negatif, tambahkan 24 jam (untuk kasus waktu selesai melewati tengah malam)
    if ($interval->invert) {
        $jam += 24;
    }

    return ['jam' => $jam, 'menit' => $menit];
}

function hitungJarak($lat1, $lon1, $lat2, $lon2)
{
    // Radius bumi dalam meter
    $radiusBumi = 6371000;

    // Mengubah derajat menjadi radian
    $lat1Rad = deg2rad($lat1);
    $lon1Rad = deg2rad($lon1);
    $lat2Rad = deg2rad($lat2);
    $lon2Rad = deg2rad($lon2);

    // Menghitung perbedaan latitude dan longitude
    $deltaLat = $lat2Rad - $lat1Rad;
    $deltaLon = $lon2Rad - $lon1Rad;

    // Rumus Haversine
    $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
        cos($lat1Rad) * cos($lat2Rad) *
        sin($deltaLon / 2) * sin($deltaLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    // Menghitung jarak
    $jarak = $radiusBumi * $c;

    return $jarak; // Hasil dalam meter
}


function truncateText($text, $wordLimit = 4)
{
    $words = explode(' ', $text); // Memisahkan teks menjadi array kata
    if (count($words) > $wordLimit) {
        return implode(' ', array_slice($words, 0, $wordLimit)) . '...';
    }
    return $text; // Jika jumlah kata tidak lebih dari batas, kembalikan teks asli
}


function getreward($realisasi)
{
    if ($realisasi >= 60 && $realisasi <= 65) {
        $reward = 25000;
    } elseif ($realisasi > 65 && $realisasi <= 70) {
        $reward = 50000;
    } elseif ($realisasi > 70 && $realisasi <= 75) {
        $reward = 75000;
    } elseif ($realisasi > 75 && $realisasi <= 80) {
        $reward = 100000;
    } elseif ($realisasi > 80 && $realisasi <= 85) {
        $reward = 125000;
    } elseif ($realisasi > 85 && $realisasi <= 90) {
        $reward = 150000;
    } elseif ($realisasi > 90 && $realisasi <= 95) {
        $reward = 175000;
    } elseif ($realisasi > 95) {
        $reward = 200000;
    } else {
        $reward = 0;
    }

    return $reward;
}

function getMonthName($month)
{
    $monthNames = array(
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember"
    );
    return $monthNames[$month - 1];
}


function getMonthName2($month)
{
    $monthNames = array(
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "Mei",
        "Jun",
        "Jul",
        "Agu",
        "Sep",
        "Okt",
        "Nov",
        "Des"
    );
    return $monthNames[$month - 1];
}


function konversiHariKeBulan($jumlahHari)
{
    if ($jumlahHari === null) {
        return '-';
    }
    $bulan = floor($jumlahHari / 30);
    return $bulan . ' bulan';
}
// function getroleuser()
// {

//     return Auth::user()->roles->pluck('name');
// }

<?php

// Cek Role Approve Penilaian

use Illuminate\Support\Facades\Auth;

function cekRoleapprove($kode_dept, $kode_cabang, $kategori_jabatan, $kode_jabatan = "")
{
    // Cek Role Name
    $role = Auth::user()->roles->pluck('name')[0];

    if ($kode_dept == 'AKT' && $kode_cabang != 'PST' && $kategori_jabatan == 'NM') {
        //Akunting Cabang Non Manajemen
        $roles_approve =  ['operation manager', 'manager keuangan', 'gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'AKT' && $kode_cabang == 'PST' && $kategori_jabatan == 'NM') {
        //Akunting Pusat Non Manajemen
        $roles_approve =  ['manager keuangan', 'gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'KEU' && $kode_cabang == 'PST' && $kategori_jabatan == 'NM') {
        //Akunting Pusat Non Manajemen
        $roles_approve =  ['manager keuangan', 'gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($kode_jabatan == 'J08') {
        //Operation Manager
        $roles_approve =  ['manager keuangan', 'gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'MKT' && $kode_cabang != 'PST' && $kategori_jabatan == 'NM') {
        //Marketing Cabang Non Manajemen
        $roles_approve =  ['sales marketing manager', 'regional sales manager', 'gm marketing', 'asst. manager hrd', 'direktur'];
    } else if ($kode_jabatan == 'J07') {
        //Sales Marketing Manager
        $roles_approve =  ['regional sales manager', 'gm marketing', 'asst. manager hrd', 'direktur'];
    } else if (in_array($kode_dept, ['GAF', 'PMB', 'GDG', 'MTC', 'PRD', 'PDQ']) && in_array($kode_jabatan, ['J05', 'J06'])) {
        $roles_approve =  ['gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'GAF'  && $kategori_jabatan == 'NM') {
        $roles_approve =  ['manager general affair', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'PDQ') {
        $roles_approve =  ['gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'GDG' && $kategori_jabatan == "NM") {
        $roles_approve =  ['manager gudang', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'HRD' && $kategori_jabatan == "NM") {
        $roles_approve =  ['asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'MTC' && $kategori_jabatan == "NM") {
        $roles_approve =  ['manager maintenance', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'PMB' && $kategori_jabatan == "NM") {
        $roles_approve =  ['manager pembelian', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == 'PRD' && $kategori_jabatan == "NM") {
        $roles_approve =  ['manager produksi', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else {
        $roles_approve =  ['manager keuangan', 'gm administrasi', 'asst. manager hrd', 'direktur'];
    }

    return $roles_approve;
}



function listApprovepenilaian($kode_dept, $level = "")
{
    $list_approve = [];
    if ($kode_dept == "AKT") {
        $list_approve =  ['operation manager', 'manager keuangan', 'gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "MKT") {
        $list_approve =  ['sales marketing manager', 'regional sales manager', 'gm marketing', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "GAF") {
        $list_approve =  ['manager general affair', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "MTC") {
        $list_approve =  ['manager maintenance', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "PMB") {
        $list_approve =  ['manager pembelian', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "PRD") {
        $list_approve =  ['manager produksi', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "GDG") {
        $list_approve =  ['manager gudang', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "PDQ") {
        $list_approve =  ['gm operasional', 'asst. manager hrd', 'direktur'];
    }

    if ($level == "manager keuangan") {
        $list_approve =  ['manager keuangan', 'gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($level == "manager gudang") {
        $list_approve =  ['manager gudang', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($level == "manager maintenance") {
        $list_approve =  ['manager maintenance', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($level == "manager pembelian") {
        $list_approve =  ['manager pembelian', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($level == "manager produksi") {
        $list_approve =  ['manager produksi', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($level == "manager general affair") {
        $list_approve =  ['manager general affair', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($level == "regional sales manager") {
        $list_approve =  ['regional sales manager', 'asst. manager hrd', 'direktur'];
    } else if ($level == "gm administrasi") {
        $list_approve =  ['gm administrasi', 'asst. manager hrd', 'direktur'];
    } else if ($level == "gm marketing") {
        $list_approve =  ['gm marketing', 'asst. manager hrd', 'direktur'];
    } else if ($level == "gm operasional") {
        $list_approve =  ['gm operasional', 'asst. manager hrd', 'direktur'];
    } else if (in_array($level, ['super admin', 'asst. manager hrd'])) {
        $list_approve =  [
            'operation manager', 'sales marketing manager', 'regional sales manager',
            'manager keuangan',
            'manager gudang', 'manager maintenance', 'manager pembelian', 'manager produksi',
            'manager general affair',
            'gm administrasi', 'gm marketing', 'gm operasional',
            'asst. manager hrd', 'direktur'
        ];
    }
    return $list_approve;
}



function cekRoleapprovelembur($kode_dept)
{
    if ($kode_dept == "GAF") {
        $roles_approve =  ['manager general affair', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "MTC") {
        $roles_approve =  ['manager maintenance', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "PRD") {
        $roles_approve =  ['manager produksi', 'gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "PDQ") {
        $roles_approve =  ['gm operasional', 'asst. manager hrd', 'direktur'];
    } else if ($kode_dept == "GDG") {
        $roles_approve =  ['manager gudang', 'gm operasional', 'asst. manager hrd', 'direktur'];
    }

    return $roles_approve;
}


function cekRoleapprovepresensi($kode_dept, $kode_cabang, $kategori_jabatan, $kode_jabatan = "")
{
    // Cek Role Name
    $role = Auth::user()->roles->pluck('name')[0];

    if ($kode_dept == 'AKT' && $kode_cabang != 'PST' && $kategori_jabatan == 'NM') {  //Akunting Cabang Non Manajemen
        $roles_approve =  ['operation manager', 'asst. manager hrd'];
    } else if (in_array($kode_dept, ['AKT', 'KEU']) && $kode_cabang == 'PST' && $kategori_jabatan == 'NM') { //Akunting dan  Keuangan Pusat Non Manajemen
        $roles_approve =  ['manager keuangan', 'asst. manager hrd'];
    } else if ($kode_jabatan == 'J08') { //Operation Manager
        $roles_approve =  ['manager keuangan', 'asst. manager hrd'];
    } else if ($kode_dept == 'MKT' && $kode_cabang != 'PST' && $kategori_jabatan == 'NM') { //Marketing Cabang Non Manajemen
        $roles_approve =  ['sales marketing manager',  'asst. manager hrd'];
    } else if ($kode_jabatan == 'J07') { //Sales Marketing Manager
        $roles_approve =  ['gm marketing', 'asst. manager hrd', 'direktur'];
    } else if (in_array($kode_dept, ['GAF', 'PMB', 'GDG', 'MTC', 'PRD', 'PDQ']) && in_array($kode_jabatan, ['J05', 'J06'])) { //GAF, PMB, GDG, MTC, PRD, PDQ MANAGER / ASST. MANAGER HRD
        $roles_approve =  ['gm operasional', 'asst. manager hrd'];
    } else if ($kode_dept == 'GAF'  && $kategori_jabatan == 'NM') { //GAF Non Manajemen
        $roles_approve =  ['manager general affair', 'asst. manager hrd'];
    } else if ($kode_dept == 'PDQ') {
        $roles_approve =  ['gm operasional', 'asst. manager hrd'];
    } else if ($kode_dept == 'GDG' && $kategori_jabatan == "NM") { // Gudang Non Manajemen
        $roles_approve =  ['manager gudang', 'asst. manager hrd'];
    } else if ($kode_dept == 'HRD' && $kategori_jabatan == "NM") { // HRD Non Manajemen
        $roles_approve =  ['asst. manager hrd'];
    } else if ($kode_dept == 'MTC' && $kategori_jabatan == "NM") { // Maintenance Non Manajemen
        $roles_approve =  ['manager maintenance', 'asst. manager hrd'];
    } else if ($kode_dept == 'PMB' && $kategori_jabatan == "NM") { //Pembelian Non Manajemen
        $roles_approve =  ['manager pembelian',  'asst. manager hrd'];
    } else if ($kode_dept == 'PRD' && $kategori_jabatan == "NM") { //Produksi Non Manajemen
        $roles_approve =  ['manager produksi', 'asst. manager hrd'];
    } else {
        $roles_approve =  ['asst. manager hrd'];
    }

    return $roles_approve;
}
function hitungjamdesimal($jam1, $jam2)
{
    $j1 = strtotime($jam1);
    $j2 = strtotime($jam2);

    $diffterlambat = $j2 - $j1;

    $jamterlambat = floor($diffterlambat / (60 * 60));
    $menitterlambat = floor(($diffterlambat - ($jamterlambat * (60 * 60))) / 60);

    $desimalterlambat = $jamterlambat + ROUND(($menitterlambat / 60), 2);

    return $desimalterlambat;
}


function hitungHari($startDate, $endDate)
{
    if ($startDate && $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);

        // Tambahkan 1 hari agar penghitungan inklusif
        $interval = $start->diff($end);
        $dayDifference = $interval->days + 1;

        return  $dayDifference;
    } else {
        return 0;
    }
}

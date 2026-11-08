<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Arus Kas {{ $nama_bulan }} {{ $tahun }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #111;
        }

        .table-aruskas {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .table-aruskas th {
            border: 1px solid #333;
            padding: 6px 8px;
            font-weight: bold;
            background-color: #f7f9fa;
        }

        .table-aruskas td {
            border: 1px solid #d5d5d5;
            padding: 4px 8px;
            vertical-align: middle;
        }

        .bg-header-group {
            background-color: #ffffff;
            font-weight: bold;
            border-top: 2px solid #333 !important;
            border-bottom: 2px solid #333 !important;
        }

        .bg-subtotal-yellow {
            background-color: #ffff00 !important;
            font-weight: bold;
        }

        .bg-subtotal-blue {
            background-color: #c9daf8 !important;
            font-weight: bold;
        }

        .bg-subtotal-light {
            background-color: #f3f3f3 !important;
            font-weight: bold;
        }

        .bg-rekon-highlight {
            background-color: #cfe2f3 !important;
            font-weight: bold;
        }

        .text-detail-blue {
            color: #0000ff;
        }

        .row-total {
            font-weight: bold;
            background-color: #ffffff;
            border-top: 2px solid #000 !important;
            border-bottom: 2px solid #000 !important;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        .ps-3 {
            padding-left: 20px !important;
        }

        .ps-4 {
            padding-left: 30px !important;
        }

        .text-note {
            font-size: 10px;
            color: #333;
        }
    </style>
</head>

<body>
    @php
        function formatVal($val, $isNegative = false)
        {
            if (empty($val) || $val == 0) {
                return '-';
            }
            if ($isNegative) {
                return '(' . number_format(abs($val), 0, ',', '.') . ')';
            }
            if ($val < 0) {
                return '(' . number_format(abs($val), 0, ',', '.') . ')';
            }
            return number_format($val, 0, ',', '.');
        }

        // Helper filter mutasi
        $getItem = function ($kode_akun) use ($mutasi) {
            return $mutasi->get($kode_akun);
        };

        // Filter daftar akun berdasarkan prefix / kondisi
        $filterMutasi = function ($predicate) use ($mutasi) {
            return $mutasi->filter($predicate);
        };

        // ================= SUMBER DANA =================
        // 1. Penyusutan
        $penyusutanAkun = [
            '1-2220' => 'Penyusutan Bangunan',
            '1-2320' => 'Penyusutan Mesin',
            '1-2420' => 'Penyusutan Peralatan Pabrik',
            '1-2620' => 'Penyusutan Peralatan Kantor',
            '1-2720' => 'Penyusutan Kendaraan',
            '1-2520' => 'Penyusutan Instalasi Listrik & Air',
        ];
        $listPenyusutan = [];
        $subtotalPenyusutan = 0;
        foreach ($penyusutanAkun as $k => $label) {
            $m = $getItem($k);
            $kredit = $m ? $m->kredit : 0;
            if ($kredit > 0) {
                $listPenyusutan[] = [
                    'kode_akun' => $k,
                    'label' => $label,
                    'nama_akun' => $m->nama_akun ?? $label,
                    'jumlah' => $kredit,
                ];
                $subtotalPenyusutan += $kredit;
            }
        }

        // 2. Piutang Usaha
        $mPiutangUsaha = $getItem('1-1401');
        $sumberPiutangUsaha = $mPiutangUsaha ? $mPiutangUsaha->kredit : 0;

        // 3. Piutang Lain-lain (1-14% selain 1-1401)
        $listPiutangLainSumber = $filterMutasi(function ($item, $key) {
            return str_starts_with($key, '1-14') && $key !== '1-1401' && $item->kredit > 0;
        });
        $subtotalPiutangLainSumber = $listPiutangLainSumber->sum('kredit');

        // 4. Persediaan (1-15%)
        $listPersediaanSumber = $filterMutasi(function ($item, $key) {
            return str_starts_with($key, '1-15') && $item->kredit > 0;
        });
        $subtotalPersediaanSumber = $listPersediaanSumber->sum('kredit');

        // 5. Uang Muka Pembelian (1-16%)
        $listUmSumber = $filterMutasi(function ($item, $key) {
            return str_starts_with($key, '1-16') && $item->kredit > 0;
        });
        $subtotalUmSumber = $listUmSumber->sum('kredit');

        // 6. Hutang Usaha (2-1200)
        $mHutangUsaha = $getItem('2-1200');
        $sumberHutangUsaha = $mHutangUsaha ? $mHutangUsaha->kredit : 0;

        // 7. Hutang Lain-lain (2-1% & 2-2% selain 2-1100 & 2-1200)
        $listHutangLainSumber = $filterMutasi(function ($item, $key) {
            return (str_starts_with($key, '2-1') || str_starts_with($key, '2-2')) &&
                !in_array($key, ['2-1100', '2-1200']) &&
                $item->kredit > 0;
        });
        $subtotalHutangLainSumber = $listHutangLainSumber->sum('kredit');

        // 8. Beban YMHD (2-3%)
        $listYmhdSumber = $filterMutasi(function ($item, $key) {
            return str_starts_with($key, '2-3') && $item->kredit > 0;
        });
        $subtotalYmhdSumber = $listYmhdSumber->sum('kredit');

        // 9. Hutang KPN-I (2-4000)
        $mKpni = $getItem('2-4000');
        $sumberKpni = $mKpni ? $mKpni->kredit : 0;

        // 10. Hutang Bank (2-1100)
        $mHutangBank = $getItem('2-1100');
        $sumberHutangBank = $mHutangBank ? $mHutangBank->kredit : 0;

        // 11. Pengurangan Aset Tetap (1-2% non akumulasi)
        $listAsetTetapSumber = $filterMutasi(function ($item, $key) {
            return str_starts_with($key, '1-2') && !in_array(substr($key, 3, 1), ['2']) && $item->kredit > 0;
        });
        $subtotalAsetTetapSumber = $listAsetTetapSumber->sum('kredit');

        // 12. Pengurangan Aset Lain-lain (1-3%)
        $listAsetLainSumber = $filterMutasi(function ($item, $key) {
            return str_starts_with($key, '1-3') && $item->kredit > 0;
        });
        $subtotalAsetLainSumber = $listAsetLainSumber->sum('kredit');

        // 13. Pengurangan Biaya Dibayar di Muka / Sewa (1-17%)
        $listBiayaDiMukaSumber = $filterMutasi(function ($item, $key) {
            return str_starts_with($key, '1-17') && $item->kredit > 0;
        });
        $subtotalBiayaDiMukaSumber = $listBiayaDiMukaSumber->sum('kredit');

        // TOTAL SUMBER DANA
        $totalSumberDana =
            $net_profit_loss +
            $subtotalPenyusutan +
            $sumberPiutangUsaha +
            $subtotalPiutangLainSumber +
            $subtotalPersediaanSumber +
            $subtotalUmSumber +
            $sumberHutangUsaha +
            $subtotalHutangLainSumber +
            $subtotalYmhdSumber +
            $sumberKpni +
            $sumberHutangBank +
            $subtotalAsetTetapSumber +
            $subtotalAsetLainSumber +
            $subtotalBiayaDiMukaSumber;

        // ================= PEMAKAIAN DANA =================
        // 1. Piutang Usaha (1-1401)
        $pemakaianPiutangUsaha = $mPiutangUsaha ? $mPiutangUsaha->debet : 0;

        // 2. Piutang Lain-lain (1-14% selain 1-1401)
        $listPiutangLainPakai = $filterMutasi(function ($item, $key) {
            return str_starts_with($key, '1-14') && $key !== '1-1401' && $item->debet > 0;
        });
        $subtotalPiutangLainPakai = $listPiutangLainPakai->sum('debet');

        // 3. Persediaan (1-15%)
        $listPersediaanPakai = $filterMutasi(function ($item, $key) {
            return str_starts_with($key, '1-15') && $item->debet > 0;
        });
        $subtotalPersediaanPakai = $listPersediaanPakai->sum('debet');

        // 4. Uang Muka Pembelian (1-16%)
        $listUmPakai = $filterMutasi(function ($item, $key) {
            return str_starts_with($key, '1-16') && $item->debet > 0;
        });
        $subtotalUmPakai = $listUmPakai->sum('debet');

        // 5. Hutang Usaha (2-1200)
        $pemakaianHutangUsaha = $mHutangUsaha ? $mHutangUsaha->debet : 0;

        // 6. Hutang Lain-lain (2-1% & 2-2% selain 2-1100 & 2-1200)
        $listHutangLainPakai = $filterMutasi(function ($item, $key) {
            return (str_starts_with($key, '2-1') || str_starts_with($key, '2-2')) &&
                !in_array($key, ['2-1100', '2-1200']) &&
                $item->debet > 0;
        });
        $subtotalHutangLainPakai = $listHutangLainPakai->sum('debet');

        // 7. Beban YMHD (2-3%)
        $listYmhdPakai = $filterMutasi(function ($item, $key) {
            return str_starts_with($key, '2-3') && $item->debet > 0;
        });
        $subtotalYmhdPakai = $listYmhdPakai->sum('debet');

        // 8. Hutang KPN-I (2-4000)
        $pemakaianKpni = $mKpni ? $mKpni->debet : 0;

        // 9. Hutang Bank (2-1100)
        $pemakaianHutangBank = $mHutangBank ? $mHutangBank->debet : 0;

        // 10. Prive (3-3000)
        $mPrive = $getItem('3-3000');
        $pemakaianPrive = $mPrive ? $mPrive->debet : 0;

        // 11. Pendapatan Belum Direalisasi (2-5000)
        $mPendBelumRealisasi = $getItem('2-5000');
        $pemakaianPendBelumRealisasi = $mPendBelumRealisasi ? $mPendBelumRealisasi->debet : 0;

        // 12. Penambahan Aset Tetap (1-2% non akumulasi)
        $listAsetTetapPakai = $filterMutasi(function ($item, $key) {
            return str_starts_with($key, '1-2') && !in_array(substr($key, 3, 1), ['2']) && $item->debet > 0;
        });
        $subtotalAsetTetapPakai = $listAsetTetapPakai->sum('debet');

        // 13. Penambahan Aset Lain-lain (1-3%)
        $listAsetLainPakai = $filterMutasi(function ($item, $key) {
            return str_starts_with($key, '1-3') && $item->debet > 0;
        });
        $subtotalAsetLainPakai = $listAsetLainPakai->sum('debet');

        // 14. Penambahan Biaya Dibayar di Muka / Sewa (1-17%)
        $listBiayaDiMukaPakai = $filterMutasi(function ($item, $key) {
            return str_starts_with($key, '1-17') && $item->debet > 0;
        });
        $subtotalBiayaDiMukaPakai = $listBiayaDiMukaPakai->sum('debet');

        // TOTAL PEMAKAIAN DANA
        $totalPemakaianDana =
            $pemakaianPiutangUsaha +
            $subtotalPiutangLainPakai +
            $subtotalPersediaanPakai +
            $subtotalUmPakai +
            $pemakaianHutangUsaha +
            $subtotalHutangLainPakai +
            $subtotalYmhdPakai +
            $pemakaianKpni +
            $pemakaianHutangBank +
            $pemakaianPrive +
            $pemakaianPendBelumRealisasi +
            $subtotalAsetTetapPakai +
            $subtotalAsetLainPakai +
            $subtotalBiayaDiMukaPakai;

        // ================= REKONSILIASI KAS & BANK =================
        $peningkatanDana = $totalSumberDana - $totalPemakaianDana;
        $danaAkhir = $kas_bank_sebelum + $peningkatanDana;
    @endphp

    <table class="table-aruskas">
        <thead>
            <tr>
                <th style="width: 45%; text-align: left; font-size: 13px;">Sumber Dana dari :</th>
                <th colspan="2" style="width: 25%; text-align: center; font-size: 13px;">{{ strtoupper($nama_bulan) }} {{ $tahun }}</th>
                <th style="width: 30%; text-align: left; font-size: 12px;">Catatan / Sumber Data</th>
            </tr>
        </thead>
        <tbody>
            <!-- Laba Bersih -->
            <tr>
                <td class="fw-bold">Laba bersih</td>
                <td style="width: 12%;"></td>
                <td class="text-right fw-bold" style="width: 13%;">{{ formatVal($net_profit_loss) }}</td>
                <td class="text-note">Total Net Profit berjalan</td>
            </tr>

            <!-- Penyusutan -->
            <tr>
                <td class="fw-bold">Penyusutan :</td>
                <td></td>
                <td class="text-right bg-subtotal-yellow">{{ formatVal($subtotalPenyusutan) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listPenyusutan as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p['label'] }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p['jumlah']) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Kredit {{ $p['nama_akun'] }}</td>
                </tr>
            @endforeach

            <!-- Pembayaran Piutang Usaha -->
            <tr>
                <td class="fw-bold">Pembayaran piutang usaha</td>
                <td></td>
                <td class="text-right bg-subtotal-yellow">{{ formatVal($sumberPiutangUsaha) }}</td>
                <td class="text-note">General Ledger Total Kredit Piutang Dagang</td>
            </tr>

            <!-- Pembayaran Piutang Lain-lain -->
            <tr>
                <td class="fw-bold">Pembayaran piutang lain-lain :</td>
                <td></td>
                <td class="text-right bg-subtotal-yellow">{{ formatVal($subtotalPiutangLainSumber) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listPiutangLainSumber as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p->nama_akun }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p->kredit) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Kredit {{ $p->nama_akun }}</td>
                </tr>
            @endforeach

            <!-- Pengurangan Persediaan -->
            <tr>
                <td class="fw-bold">Pengurangan persediaan :</td>
                <td></td>
                <td class="text-right bg-subtotal-yellow">{{ formatVal($subtotalPersediaanSumber) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listPersediaanSumber as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p->nama_akun }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p->kredit) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Kredit {{ $p->nama_akun }}</td>
                </tr>
            @endforeach

            <!-- Pengurangan Uang Muka Pembelian -->
            <tr>
                <td class="fw-bold">Pengurangan Uang Muka Pembelian :</td>
                <td></td>
                <td class="text-right bg-subtotal-yellow">{{ formatVal($subtotalUmSumber) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listUmSumber as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p->nama_akun }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p->kredit) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Kredit {{ $p->nama_akun }}</td>
                </tr>
            @endforeach

            <!-- Penambahan Hutang Usaha -->
            <tr>
                <td class="fw-bold">Penambahan hutang usaha</td>
                <td></td>
                <td class="text-right bg-subtotal-yellow">{{ formatVal($sumberHutangUsaha) }}</td>
                <td class="text-note">General Ledger Total Kredit Hutang Dagang</td>
            </tr>

            <!-- Penambahan Hutang Lain-lain -->
            <tr>
                <td class="fw-bold">Penambahan hutang lain - lain :</td>
                <td></td>
                <td class="text-right bg-subtotal-yellow">{{ formatVal($subtotalHutangLainSumber) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listHutangLainSumber as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p->nama_akun }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p->kredit) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Kredit {{ $p->nama_akun }}</td>
                </tr>
            @endforeach

            <!-- Penambahan Beban YMHD -->
            <tr>
                <td class="fw-bold">Penambahan Beban YMHD :</td>
                <td></td>
                <td class="text-right bg-subtotal-yellow">{{ formatVal($subtotalYmhdSumber) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listYmhdSumber as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p->nama_akun }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p->kredit) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Kredit {{ $p->nama_akun }}</td>
                </tr>
            @endforeach

            <!-- Penambahan Hutang KPN-I -->
            <tr>
                <td class="fw-bold">Penambahan Hutang KPN-I :</td>
                <td></td>
                <td class="text-right bg-subtotal-yellow">{{ formatVal($sumberKpni) }}</td>
                <td class="text-note">General Ledger Total Kredit Hutang KPYMHI</td>
            </tr>

            <!-- Penambahan Hutang Bank -->
            <tr>
                <td class="fw-bold">Penambahan Hutang Bank :</td>
                <td></td>
                <td class="text-right bg-subtotal-yellow">{{ formatVal($sumberHutangBank) }}</td>
                <td class="text-note">General Ledger Total Kredit Hutang Bank</td>
            </tr>

            <!-- Pengurangan Aset Tetap -->
            <tr>
                <td class="fw-bold">Pengurangan Aset Tetap :</td>
                <td></td>
                <td class="text-right bg-subtotal-yellow">{{ formatVal($subtotalAsetTetapSumber) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listAsetTetapSumber as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p->nama_akun }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p->kredit) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Kredit {{ $p->nama_akun }}</td>
                </tr>
            @endforeach

            <!-- Pengurangan Aset Lain-lain -->
            <tr>
                <td class="fw-bold">Pengurangan Aset lain - lain :</td>
                <td></td>
                <td class="text-right bg-subtotal-yellow">{{ formatVal($subtotalAsetLainSumber) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listAsetLainSumber as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p->nama_akun }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p->kredit) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Kredit {{ $p->nama_akun }}</td>
                </tr>
            @endforeach

            <!-- Pengurangan Biaya Dibayar di Muka -->
            <tr>
                <td class="fw-bold">Pengurangan Biaya Dibayar di Muka :</td>
                <td></td>
                <td class="text-right bg-subtotal-yellow">{{ formatVal($subtotalBiayaDiMukaSumber) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listBiayaDiMukaSumber as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p->nama_akun }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p->kredit) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Kredit {{ $p->nama_akun }}</td>
                </tr>
            @endforeach

            <!-- TOTAL SUMBER DANA -->
            <tr class="row-total">
                <td class="text-center fw-bold" style="font-size: 12px;">Total</td>
                <td></td>
                <td class="text-right fw-bold" style="font-size: 12px;">{{ formatVal($totalSumberDana) }}</td>
                <td></td>
            </tr>

            <!-- ================= PEMAKAIAN DANA UNTUK ================= -->
            <tr class="bg-header-group">
                <td colspan="4" style="font-size: 13px;">Pemakaian Dana Untuk :</td>
            </tr>

            <!-- Penambahan Piutang Usaha -->
            <tr>
                <td class="fw-bold">Penambahan piutang usaha</td>
                <td></td>
                <td class="text-right bg-subtotal-blue">{{ formatVal($pemakaianPiutangUsaha, true) }}</td>
                <td class="text-note">General Ledger Total Debet Piutang Dagang</td>
            </tr>

            <!-- Penambahan Piutang Lain-lain -->
            <tr>
                <td class="fw-bold">Penambahan piutang lain-lain :</td>
                <td></td>
                <td class="text-right bg-subtotal-blue">{{ formatVal($subtotalPiutangLainPakai, true) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listPiutangLainPakai as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p->nama_akun }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p->debet, true) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Debet {{ $p->nama_akun }}</td>
                </tr>
            @endforeach

            <!-- Penambahan Persediaan -->
            <tr>
                <td class="fw-bold">Penambahan persediaan :</td>
                <td></td>
                <td class="text-right bg-subtotal-blue">{{ formatVal($subtotalPersediaanPakai, true) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listPersediaanPakai as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p->nama_akun }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p->debet, true) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Debet {{ $p->nama_akun }}</td>
                </tr>
            @endforeach

            <!-- Penambahan Uang Muka Pembelian -->
            <tr>
                <td class="fw-bold">Penambahan Uang Muka Pembelian :</td>
                <td></td>
                <td class="text-right bg-subtotal-blue">{{ formatVal($subtotalUmPakai, true) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listUmPakai as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p->nama_akun }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p->debet, true) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Debet {{ $p->nama_akun }}</td>
                </tr>
            @endforeach

            <!-- Pengurangan Hutang Usaha -->
            <tr>
                <td class="fw-bold">Pengurangan hutang usaha</td>
                <td></td>
                <td class="text-right bg-subtotal-blue">{{ formatVal($pemakaianHutangUsaha, true) }}</td>
                <td class="text-note">General Ledger Total Debet Hutang Dagang</td>
            </tr>

            <!-- Pengurangan Hutang Lain-lain -->
            <tr>
                <td class="fw-bold">Pengurangan hutang lain - lain :</td>
                <td></td>
                <td class="text-right bg-subtotal-blue">{{ formatVal($subtotalHutangLainPakai, true) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listHutangLainPakai as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p->nama_akun }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p->debet, true) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Debet {{ $p->nama_akun }}</td>
                </tr>
            @endforeach

            <!-- Pengurangan Beban YMHD -->
            <tr>
                <td class="fw-bold">Pengurangan Beban YMHD :</td>
                <td></td>
                <td class="text-right bg-subtotal-blue">{{ formatVal($subtotalYmhdPakai, true) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listYmhdPakai as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p->nama_akun }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p->debet, true) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Debet {{ $p->nama_akun }}</td>
                </tr>
            @endforeach

            <!-- Pengurangan Hutang KPN-I -->
            <tr>
                <td class="fw-bold">Pengurangan Hutang KPN-I :</td>
                <td></td>
                <td class="text-right bg-subtotal-blue">{{ formatVal($pemakaianKpni, true) }}</td>
                <td class="text-note">General Ledger Total Debet Hutang KPYMHI</td>
            </tr>

            <!-- Pengurangan Hutang Bank -->
            <tr>
                <td class="fw-bold">Pengurangan Hutang Bank :</td>
                <td></td>
                <td class="text-right bg-subtotal-blue">{{ formatVal($pemakaianHutangBank, true) }}</td>
                <td class="text-note">General Ledger Total Debet Hutang Bank</td>
            </tr>

            <!-- Prive -->
            <tr>
                <td class="fw-bold">Prive :</td>
                <td></td>
                <td class="text-right bg-subtotal-blue">{{ formatVal($pemakaianPrive, true) }}</td>
                <td class="text-note">General Ledger Total Debet Prive</td>
            </tr>

            <!-- Pendapatan Belum Direalisasi -->
            <tr>
                <td class="fw-bold">Pendapatan Yang Belum Direalisasi :</td>
                <td></td>
                <td class="text-right bg-subtotal-blue">{{ formatVal($pemakaianPendBelumRealisasi, true) }}</td>
                <td class="text-note">General Ledger Total Debet Pendapatan Belum Direalisasi</td>
            </tr>

            <!-- Penambahan Aset Tetap -->
            <tr>
                <td class="fw-bold">Penambahan Aset Tetap :</td>
                <td></td>
                <td class="text-right bg-subtotal-blue">{{ formatVal($subtotalAsetTetapPakai, true) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listAsetTetapPakai as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p->nama_akun }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p->debet, true) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Debet {{ $p->nama_akun }}</td>
                </tr>
            @endforeach

            <!-- Penambahan Aset Lain-lain -->
            <tr>
                <td class="fw-bold">Penambahan Aset lain-lain :</td>
                <td></td>
                <td class="text-right bg-subtotal-blue">{{ formatVal($subtotalAsetLainPakai, true) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listAsetLainPakai as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p->nama_akun }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p->debet, true) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Debet {{ $p->nama_akun }}</td>
                </tr>
            @endforeach

            <!-- Penambahan Biaya Dibayar di Muka -->
            <tr>
                <td class="fw-bold">Penambahan Biaya Dibayar di Muka :</td>
                <td></td>
                <td class="text-right bg-subtotal-blue">{{ formatVal($subtotalBiayaDiMukaPakai, true) }}</td>
                <td class="text-note"></td>
            </tr>
            @foreach ($listBiayaDiMukaPakai as $p)
                <tr>
                    <td class="ps-3 text-detail-blue">{{ $p->nama_akun }}</td>
                    <td class="text-right text-detail-blue">{{ formatVal($p->debet, true) }}</td>
                    <td></td>
                    <td class="text-note">General Ledger Total Debet {{ $p->nama_akun }}</td>
                </tr>
            @endforeach

            <!-- TOTAL PEMAKAIAN DANA -->
            <tr class="row-total">
                <td class="text-center fw-bold" style="font-size: 12px;">Total</td>
                <td></td>
                <td class="text-right fw-bold" style="font-size: 12px;">{{ formatVal($totalPemakaianDana, true) }}</td>
                <td></td>
            </tr>

            <!-- ================= REKONSILIASI KAS & BANK ================= -->
            <tr>
                <td colspan="4" style="height: 14px; border: none;"></td>
            </tr>

            <tr class="fw-bold">
                <td style="border: 2px solid #333;">Peningkatan (Penurunan) Sumber Dana</td>
                <td style="border: 2px solid #333;"></td>
                <td class="text-right" style="border: 2px solid #333;">{{ formatVal($peningkatanDana) }}</td>
                <td style="border: 2px solid #333;"></td>
            </tr>

            <tr class="fw-bold">
                <td style="border: 2px solid #333;">Dana Awal (Kas & Bank {{ $nama_bulan_sebelum }} {{ $tahun_sebelum }})</td>
                <td style="border: 2px solid #333;"></td>
                <td class="text-right" style="border: 2px solid #333;">{{ formatVal($kas_bank_sebelum) }}</td>
                <td class="text-note" style="border: 2px solid #333;">Total kas & bank di Neraca bulan sebelum</td>
            </tr>

            <tr class="fw-bold">
                <td style="border: 2px solid #333;">Dana Akhir (Kas & Bank {{ $nama_bulan }} {{ $tahun }})</td>
                <td style="border: 2px solid #333;"></td>
                <td class="text-right" style="border: 2px solid #333;">{{ formatVal($danaAkhir) }}</td>
                <td style="border: 2px solid #333;"></td>
            </tr>

            <tr class="fw-bold bg-rekon-highlight">
                <td style="border: 2px solid #333; color: #0b5394;">Saldo Akhir Pada Neraca (Kas & Bank {{ $nama_bulan }} {{ $tahun }})</td>
                <td style="border: 2px solid #333;"></td>
                <td class="text-right" style="border: 2px solid #333; color: #0b5394;">{{ formatVal($kas_bank_berjalan) }}</td>
                <td class="text-note" style="border: 2px solid #333; color: #0b5394;">Total kas & bank di Neraca bulan berjalan</td>
            </tr>
        </tbody>
    </table>
</body>

</html>


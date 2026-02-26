<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buku Besar Rekap {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    <style>
        .text-red {
            background-color: red;
            color: white;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            REKAP BUKU BESAR<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th style="font-size:12; width: 15%;">KODE AKUN</th>
                        <th style="font-size:12; width: 35%;">NAMA AKUN</th>
                        <th style="font-size:12; width: 12.5%;">SALDO AWAL</th>
                        <th style="font-size:12; width: 12.5%;">DEBET</th>
                        <th style="font-size:12; width: 12.5%;">KREDIT</th>
                        <th style="font-size:12; width: 12.5%;">SALDO AKHIR</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $kode_akun = '';
                        $total_debet = 0;
                        $total_kredit = 0;
                        $saldo = 0;
                        $saldo_awal_kredit = 0;
                        $saldo_awal_debet = 0;
                        $nama_akun = '';
                        $jenis_akun = '';
                        $first_row = true;
                    @endphp
                    @foreach ($bukubesar as $key => $d)
                        @if ($kode_akun != $d->kode_akun && !$first_row)
                            <!-- Print Previous Row -->
                            <tr>
                                <td>{{ $kode_akun }}</td>
                                <td>{{ $nama_akun }}</td>
                                @php
                                    $mutasi_d = $total_debet - $saldo_awal_debet;
                                    $mutasi_k = $total_kredit - $saldo_awal_kredit;
                                    
                                    // Saldo Awal is determined by the first record of the account (which is ordered first)
                                    $saldo_awal_actual = 0;
                                    if ($jenis_akun == '1') {
                                        $saldo_awal_actual = $saldo_awal_kredit - $saldo_awal_debet;
                                    } else {
                                        $saldo_awal_actual = $saldo_awal_debet - $saldo_awal_kredit;
                                    }
                                    
                                    // Saldo Akhir is the accumulated $saldo
                                @endphp
                                
                                <td style="text-align: right;">{{ formatAngkaDesimal(abs($saldo_awal_actual)) }}</td>
                                <td style="text-align: right;">{{ formatAngkaDesimal($mutasi_d) }}</td>
                                <td style="text-align: right;">{{ formatAngkaDesimal($mutasi_k) }}</td>
                                <td style="text-align: right;">{{ formatAngkaDesimal(abs($saldo)) }}</td>
                            </tr>
                        @endif

                        @if ($kode_akun != $d->kode_akun)
                            @php
                                // Reset variables for the new account
                                $saldo = 0;
                                $total_debet = 0;
                                $total_kredit = 0;
                                $saldo_awal_debet = 0;
                                $saldo_awal_kredit = 0;
                                $kode_akun = $d->kode_akun;
                                $nama_akun = $d->nama_akun;
                                $jenis_akun = $d->jenis_akun;
                                $first_row = false;
                            @endphp
                        @endif

                        @php
                            // Accumulate running totals
                            if ($d->jenis_akun == '1') {
                                $saldo += $d->jml_kredit - $d->jml_debet;
                            } else {
                                $saldo += $d->jml_debet - $d->jml_kredit;
                            }
                            $total_debet += $d->jml_debet;
                            $total_kredit += $d->jml_kredit;

                            // Capture Saldo Awal
                            if ($d->sumber == 'SALDO AWAL') {
                                if ($d->jenis_akun == '1') {
                                    $saldo_awal_kredit = $saldo;
                                    $saldo_awal_debet = 0;
                                } else {
                                    $saldo_awal_kredit = 0;
                                    $saldo_awal_debet = $saldo;
                                }
                            }
                        @endphp
                        
                        @if ($loop->last)
                            <!-- Print Last Row -->
                            <tr>
                                <td>{{ $kode_akun }}</td>
                                <td>{{ $nama_akun }}</td>
                                @php
                                    $mutasi_d = $total_debet - $saldo_awal_debet;
                                    $mutasi_k = $total_kredit - $saldo_awal_kredit;
                                    
                                    $saldo_awal_actual = 0;
                                    if ($jenis_akun == '1') {
                                        $saldo_awal_actual = $saldo_awal_kredit - $saldo_awal_debet;
                                    } else {
                                        $saldo_awal_actual = $saldo_awal_debet - $saldo_awal_kredit;
                                    }
                                @endphp
                                
                                <td style="text-align: right;">{{ formatAngkaDesimal(abs($saldo_awal_actual)) }}</td>
                                <td style="text-align: right;">{{ formatAngkaDesimal($mutasi_d) }}</td>
                                <td style="text-align: right;">{{ formatAngkaDesimal($mutasi_k) }}</td>
                                <td style="text-align: right;">{{ formatAngkaDesimal(abs($saldo)) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>

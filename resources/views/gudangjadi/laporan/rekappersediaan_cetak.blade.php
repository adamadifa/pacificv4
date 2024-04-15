<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Persediaan Gudang Jadi {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4>REKAPITULASI PERSEDIAAN BARANG</h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>

    </div>
    <div class="body">
        <table class="datatable3">
            <thead>
                <tr>

                    <th rowspan="2">No</th>
                    <th rowspan="2">KODE</th>
                    <th rowspan="2">PRODUK</th>
                    <th rowspan="2">Saldo Awal</th>
                    <th colspan="3" class="green">PENERIMAAN</th>
                    <th colspan="3" class="red">PENGELUARAN</th>
                    <th rowspan="2">SALDO AKHIR
                    </th>
                </tr>
                <tr>
                    <th class="green">PRODUKSI</th>
                    <th class="green">REPACK</th>
                    <th class="green">LAIN LAIN</th>
                    <th class="reject">KIRIM KE CABANG</th>
                    <th class="reject">REJECT</th>
                    <th class="reject">LAIN LAIN</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_fsthp = 0;
                    $total_repack = 0;
                    $total_lainlain_in = 0;
                    $total_surat_jalan = 0;
                    $total_reject = 0;
                    $total_lainlain_out = 0;
                    $total_saldo_awal = 0;
                    $total_saldo_akhir = 0;
                @endphp
                @foreach ($rekap as $d)
                    @php
                        $total_fsthp += $d->jml_fsthp;
                        $total_repack += $d->jml_repack;
                        $total_lainlain_in += $d->jml_lainlain_in;
                        $total_surat_jalan += $d->jml_surat_jalan;
                        $total_reject += $d->jml_reject;
                        $total_lainlain_out += $d->jml_lainlain_out;
                        $total_saldo_awal += $d->jml_saldo_awal;
                        $total_saldo_akhir += $d->jml_saldo_akhir;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->kode_produk }}</td>
                        <td>{{ $d->nama_produk }}</td>
                        <td class="right">{{ formatAngka($d->jml_saldo_awal) }}</td>
                        <td class="right">{{ formatAngka($d->jml_fsthp) }}</td>
                        <td class="right">{{ formatAngka($d->jml_repack) }}</td>
                        <td class="right">{{ formatAngka($d->jml_lainlain_in) }}</td>
                        <td class="right">{{ formatAngka($d->jml_surat_jalan) }}</td>
                        <td class="right">{{ formatAngka($d->jml_reject) }}</td>
                        <td class="right">{{ formatAngka($d->jml_lainlain_out) }}</td>
                        <td class="right">{{ formatAngka($d->jml_saldo_akhir) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">TOTAL</th>
                    <th style="text-align: right">{{ formatAngka($total_saldo_awal) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_fsthp) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_repack) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_lainlain_in) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_surat_jalan) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_reject) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_lainlain_out) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_saldo_akhir) }}</th>

                </tr>
            </tfoot>
        </table>
    </div>
</body>

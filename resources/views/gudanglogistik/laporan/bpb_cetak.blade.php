<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan BPB Gudang Logistik {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN BUKTI PERMINTAAN BARANG (BPB) GUDANG LOGISTIK<br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($dept != null)
            <h4>DEPARTEMEN : {{ textUpperCase($dept->nama_dept) }}</h4>
        @endif
        @if ($status != null)
            <h4>STATUS : {{ textUpperCase($status) }}</h4>
        @endif
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NO. BPB</th>
                    <th>TANGGAL</th>
                    <th>DEPARTEMEN</th>
                    <th>KODE BARANG</th>
                    <th style="width: 20%">NAMA BARANG</th>
                    <th>SATUAN</th>
                    <th>QTY PERMINTAAN</th>
                    <th>QTY PENYERAHAN</th>
                    <th>SISA</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_diminta = 0;
                    $total_diserahkan = 0;
                    $total_sisa = 0;
                @endphp
                @foreach ($bpb as $d)
                    @php
                        $total_diminta += $d->qty_diminta;
                        $total_diserahkan += $d->qty_diserahkan;
                        $total_sisa += $d->sisa;
                        $rowStatus = $d->sisa <= 0 ? 'Selesai' : 'Proses';
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->no_bpb }}</td>
                        <td>{{ DateToIndo($d->tanggal) }}</td>
                        <td>{{ textUpperCase($d->nama_dept) }}</td>
                        <td>{{ $d->kode_barang }}</td>
                        <td>{{ textUpperCase($d->nama_barang) }}</td>
                        <td>{{ textUpperCase($d->satuan) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->qty_diminta) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->qty_diserahkan) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->sisa) }}</td>
                        <td class="center">
                            <span style="font-weight: bold; color: {{ $rowStatus == 'Selesai' ? 'green' : 'red' }}">
                                {{ $rowStatus }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <th colspan="7" bgcolor="#024a75">TOTAL</th>
                <th class="right">{{ formatAngkaDesimal($total_diminta) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_diserahkan) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_sisa) }}</th>
                <th></th>
            </tfoot>
        </table>
    </div>
</body>
</html>

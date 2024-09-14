<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kartu Hutang {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    {{-- <style>
        .freeze-table {
            height: auto;
            max-height: 830px;
            overflow: auto;
        }
    </style> --}}
</head>

<body>
    <div class="header">
        <h4 class="title">
            KARTU HUTANG<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($supplier != null)
            <h4>
                {{ $supplier->kode_supplier }} - {{ $supplier->nama_supplier }}
            </h4>
        @endif
        @if (!empty($jenis_hutang))
            <h4>
                @if ($jenis_hutang == '2-1200')
                    HUTANG DAGANG
                @else
                    HUTANG LAINNYA
                @endif
            </h4>

        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>TGL</th>
                        <th>NO BUKTI</th>
                        <th>KATEGORI</th>
                        <th>SUPPLIER</th>
                        <th>AKUN</th>
                        <th>SALDO AWAL</th>
                        <th>PEMBELIAN</th>
                        <th>PENYESUAIAN</th>
                        <th>PEMBAYARAN</th>
                        <th>SALDO AKHIR</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalsaldoawal = 0;
                        $totalsaldoakhir = 0;
                        $totalpembelian = 0;
                        $totalpenyesuaian = 0;
                        $totalpembayaran = 0;
                    @endphp
                    @foreach ($kartuhutang as $d)
                        @php
                            if ($d->tanggal < $dari) {
                                $saldoawal = $d->sisapiutang;
                            } else {
                                $saldoawal = 0;
                            }
                            $saldoakhir = $d->totalhutang - $d->jmlbayarbulanlalu - $d->jmlbayarbulanini;
                            $totalsaldoawal += $saldoawal;
                            $totalsaldoakhir += $saldoakhir;
                            $totalpembelian += $d->pmbbulanini;
                            $totalpenyesuaian += $d->penyesuaianbulanini;
                            $totalpembayaran += $d->jmlbayarbulanini;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_bukti }}</td>
                            <td class="center">{{ $d->kategori_transaksi }}</td>
                            <td>{{ $d->kode_supplier }} - {{ $d->nama_supplier }}</td>
                            <td><b>{{ $d->kode_akun }}</b> {{ $d->nama_akun }}</td>
                            <td class="right">{{ formatAngkaDesimal($saldoawal) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->pmbbulanini) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->penyesuaianbulanini) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->jmlbayarbulanini) }}</td>
                            <td class="right">{{ formatAngkaDesimal($saldoakhir) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6">TOTAL</th>
                        <th class="right"><b>{{ formatAngka($totalsaldoawal) }}</b></th>
                        <th class="right"><b>{{ formatAngka($totalpembelian) }}</b></th>
                        <th class="right"><b>{{ formatAngka($totalpenyesuaian) }}</b></th>
                        <th class="right"><b>{{ formatAngka($totalpembayaran) }}</b></th>
                        <th class="right"><b>{{ formatAngka($totalsaldoakhir) }}</b></th>
                </tfoot>
            </table>
        </div>
    </div>
</body>
{{-- <script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 10,
        'shadow': true,
    });
</script> --}}

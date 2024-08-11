<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Retur {{ date('Y-m-d H:i:s') }}</title>
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

    <style>
        .text-red {
            background-color: red;
            color: white;
        }

        .bg-terimauang {
            background-color: #199291 !important;
            color: white !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN RETUR <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
        @if ($salesman != null)
            <h4>
                {{ textUpperCase($salesman->nama_salesman) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3" style="width: 110%">
                <thead>
                    <tr>
                        {{-- <th rowspan="2">No</th> --}}
                        <th rowspan="2">Tanggal</th>
                        <th rowspan="2">No Retur</th>
                        <th rowspan="2">No Faktur</th>
                        <th rowspan="2">Kode Pel.</th>
                        <th rowspan="2" style="width:10%">Nama Pelanggan</th>
                        <th rowspan="2" style="width:6%">Pasar/Daerah</th>
                        <th rowspan="2">Hari</th>
                        <th rowspan="2">Jenis</th>
                        <th rowspan="2">Nama Barang</th>
                        <th colspan="7" align="center">QTY</th>
                        <th rowspan="2">Total</th>
                        <th rowspan="2">T/K</th>
                        <th rowspan="2">Dibuat</th>
                        <th rowspan="2">Diupdate</th>
                        {{-- <th colspan="{{ count($validasi_item) }}" align="center">Validasi</th> --}}
                    </tr>
                    <tr>
                        <th>DUS</th>
                        <th>Harga</th>
                        <th>PACK</th>
                        <th>Harga</th>
                        <th>PCS</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                        {{-- @foreach ($validasi_item as $item)
                            <th>{{ $item->item }}</th>
                        @endforeach --}}
                    </tr>
                </thead>
                <tbody>
                    @php
                        $arr = [];
                        foreach ($retur as $row) {
                            $arr[$row->no_retur][] = $row;
                        }

                        $grandtotal = 0;
                    @endphp
                    @foreach ($arr as $key => $val)
                        @foreach ($val as $k => $d)
                            @php
                                if (!empty($d->isi_pcs_dus)) {
                                    $qty = convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah);
                                    $jml = explode('|', $qty);
                                    $dus = $jml[0];
                                    $pack = $jml[1];
                                    $pcs = $jml[2];
                                    //$total += $d->subtotal;
                                    if ($d->status_promosi == '1') {
                                        $bgcolor = 'yellow';
                                    } else {
                                        $bgcolor = '';
                                    }
                                } else {
                                    $dus = 0;
                                    $pack = 0;
                                    $pcs = 0;
                                    $bgcolor = 'red';
                                }

                            @endphp
                            <tr>
                                @if ($k == 0)
                                    <td rowspan="{{ count($val) }}">{{ formatIndo($d->tanggal) }}</td>
                                    <td rowspan="{{ count($val) }}">{{ !empty($d->no_ref) ? $d->no_ref : $d->no_retur }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->no_faktur }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->kode_pelanggan }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->nama_pelanggan }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->nama_wilayah }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->hari }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->jenis_retur }}</td>
                                @endif
                                <td>{{ $d->nama_produk }}</td>
                                <td class="center">{{ formatAngka($dus) }}</td>
                                <td class="right">
                                    {{ !empty($dus) ? formatAngka($d->harga_dus) : '' }}</td>
                                <td class="center">{{ formatAngka($pack) }}</td>
                                <td class="right">
                                    {{ !empty($pack) ? formatAngka($d->harga_pack) : '' }}</td>
                                <td class="center">{{ formatAngka($pcs) }}</td>
                                <td class="right">
                                    {{ !empty($pcs) ? formatAngka($d->harga_pcs) : '' }}</td>
                                <td class="right">{{ formatAngka($d->subtotal) }}</td>
                                @if ($k == 0)
                                    @php
                                        $grandtotal += $d->total;
                                    @endphp
                                    <td class="right" rowspan="{{ count($val) }}">{{ formatAngka($d->total) }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT' }}</td>
                                    <td rowspan="{{ count($val) }}">{{ date('d-m-Y H:i:s', strtotime($d->created_at)) }}</td>
                                    <td rowspan="{{ count($val) }}">
                                        {{ !empty($d->updated_at) ? date('d-m-Y H:i:s', strtotime($d->updated_at)) : '' }}</td>
                                @endif
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="16">Total</th>
                        <th class="right">{{ formatAngka($grandtotal) }}</th>
                        <th colspan="4"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

</html>
{{-- <script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 5,
        'shadow': true,
    });
</script> --}}

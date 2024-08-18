<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Realisasi Oman {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4>REALISASI OMAN</h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>

    </div>
    <div class="body">
        <div class="body">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th rowspan="3">NO</th>
                        <th rowspan="3">CABANG</th>
                        <th colspan="{{ count($produk) * 3 }}">PRODUK</th>
                    </tr>
                    <tr>
                        @foreach ($produk as $d)
                            <th colspan="3">{{ $d->nama_produk }}</th>
                        @endforeach
                    </tr>
                    @foreach ($produk as $d)
                        <th class="blue">OMAN</th>
                        <th class="green">REALISASI</th>
                        <th class="green">%</th>
                    @endforeach
                </thead>
                <tbody>
                    @foreach ($produk as $d)
                        @php
                            ${"total_oman_$d->kode_produk"} = 0;
                            ${"total_realisasi_$d->kode_produk"} = 0;
                        @endphp
                    @endforeach
                    @foreach ($rekap as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ textUpperCase($d->nama_cabang) }}</td>
                            @foreach ($produk as $p)
                                @php
                                    ${"selisih_$p->kode_produk"} = $d->{"realisasi_$p->kode_produk"} - $d->{"oman_$p->kode_produk"};
                                    ${"total_oman_$p->kode_produk"} += $d->{"oman_$p->kode_produk"};
                                    ${"total_realisasi_$p->kode_produk"} += $d->{"realisasi_$p->kode_produk"};
                                    ${"total_persentase_$p->kode_produk"} =
                                        ${"total_oman_$p->kode_produk"} != 0
                                            ? (${"total_realisasi_$p->kode_produk"} / ${"total_oman_$p->kode_produk"}) * 100
                                            : 0;
                                    $persentase =
                                        $d->{"oman_$p->kode_produk"} != 0
                                            ? ($d->{"realisasi_$p->kode_produk"} / $d->{"oman_$p->kode_produk"}) * 100
                                            : 0;

                                    if (${"selisih_$p->kode_produk"} > 0) {
                                        $color = 'green';
                                    } elseif (${"selisih_$p->kode_produk"} < 0) {
                                        $color = 'red';
                                    }
                                @endphp
                                <td class="right">{{ formatAngka($d->{"oman_$p->kode_produk"}) }}</td>
                                <td class="right">
                                    {{ formatAngka($d->{"realisasi_$p->kode_produk"}) }}
                                    <span style="color:{{ $color }}">
                                        ({{ ${"selisih_$p->kode_produk"} > 0 ? '+' : '' }}{{ ${"selisih_$p->kode_produk"} }})
                                    </span>
                                </td>
                                <td class="center" style="color:{{ $persentase >= 100 ? 'green' : '' }}">
                                    {{ formatAngka($persentase) }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">TOTAL</th>

                        @foreach ($produk as $d)
                            <th class="right">{{ formatAngka(${"total_oman_$d->kode_produk"}) }}</th>
                            <th class="right">{{ formatAngka(${"total_realisasi_$d->kode_produk"}) }}</th>
                            <th class="center">
                                {{ formatAngka(${"total_persentase_$d->kode_produk"}) }}
                            </th>
                        @endforeach
                    </tr>

                </tfoot>
            </table>
        </div>

    </div>
</body>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Angkutan {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4>LAPORAN ANGKUTAN</h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($angkutan != null)
            <h4>{{ textUpperCase($angkutan->nama_angkutan) }}</h4>
        @endif
    </div>
    <div class="body">
        <table class="datatable3">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>NO. DOK</th>
                    <th>TANGGAL</th>
                    <th>NO. POLISI</th>
                    <th>ANGKUTAN</th>
                    <th>TUJUAN</th>
                    <th>KETERANGAN</th>
                    <th>TARIF</th>
                    <th>TEPUNG</th>
                    <th>BS</th>
                    <th>TOTAL</th>
                    <th>TGL KONTRABON</th>
                    <th>TGL BAYAR</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_tarif = 0;
                    $total_tepung = 0;
                    $total_bs = 0;
                    $total_all_tarif = 0;
                @endphp
                @foreach ($suratjalanangkutan as $d)
                    @php
                        $total_tarif += $d->tarif;
                        $total_tepung += $d->tepung;
                        $total_bs += $d->bs;
                        $total_all_tarif += $d->total_tarif;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->no_dok }}</td>
                        <td>{{ DateToIndo($d->tanggal) }}</td>
                        <td>{{ $d->no_polisi }}</td>
                        <td>{{ $d->nama_angkutan }}</td>
                        <td>{{ $d->tujuan }}</td>
                        <td>{{ $d->keterangan }}</td>
                        <td class="right">{{ formatAngka($d->tarif) }}</td>
                        <td class="right">{{ formatAngka($d->tepung) }}</td>
                        <td class="right">{{ formatAngka($d->bs) }}</td>
                        <td class="right">{{ formatAngka($d->total_tarif) }}</td>
                        <td class="right">{{ DateToIndo($d->tanggal_kontrabon) }}</td>
                        <td>
                            @if (!empty($d->tanggal_bayar) || !empty($d->tanggal_bayar_hutang))
                                <span class="badge bg-success">
                                    {{ formatIndo($d->tanggal_bayar ?? $d->tanggal_bayar_hutang) }}
                                </span>
                            @else
                                Belum Bayar
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <th colspan="7">TOTAL</th>
                <th class="right">{{ formatAngka($total_tarif) }}</th>
                <th class="right">{{ formatAngka($total_tepung) }}</th>
                <th class="right">{{ formatAngka($total_bs) }}</th>
                <th class="right">{{ formatAngka($total_all_tarif) }}</th>
                <th></th>
            </tfoot>
        </table>
    </div>
</body>

<!-- Normalize or reset CSS with your favorite library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

<!-- Load paper.css for happy printing -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

<!-- Set page size here: A5, A4 or A3 -->
<!-- Set also "landscape" if you need -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&display=swap');

    body.A4 .sheet {
        height: auto !important;
    }

    .sheet {
        overflow: auto !important;
    }

    .datatable3 {
        border: 1px solid #2f2f2f;
        border-collapse: collapse;

    }

    .datatable3 td {
        border: 1px solid #000000;
        padding: 6px;
        font-size: 9px;
    }

    .datatable3 th {
        border: 2px solid #828282;
        font-weight: bold;
        text-align: left;
        padding: 5px;
        text-align: center;
        font-size: 10px;
    }


    .datatable2 {
        border: 1px solid #2f2f2f;
        border-collapse: collapse;

    }

    .datatable2 td {
        /* border: 1px solid #000000; */
        padding: 6px;
        font-size: 9px;
    }


    body {
        background: rgb(204, 204, 204);
        font-family: 'Poppins';
    }

    @page {
        size: A4
    }

    .center {
        text-align: center;
    }
</style>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Sales Perfomance</title>
</head>

<body class="A4">
    <section class="sheet padding-10mm">
        <!-- Write HTML just like a web page -->
        <article>
            <b style="font-size:14px;">
                {{ textUpperCase($cabang->nama_cabang) }}
                <br>
                SALES PERFOMANCE<br>
                PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}
                <br>
                {{ $salesman->nama_salesman }}
            </b>
            <div style="margin-top: 10px">
                <table class="datatable3">
                    <tr>
                        <td style="text-align: center; font-weight:bold; font-size:18px">
                            {{ $jmlkunjungan }}
                        </td>
                        <td style="text-align: center; font-weight:bold; font-size:18px">
                            {{ $ec }}
                        </td>
                        <td style="text-align: center; font-weight:bold; font-size:18px" id="totalminutes">

                        </td>
                        <td style="text-align: center; font-weight:bold; font-size:18px" id="ratarataminutes">

                        </td>
                    </tr>
                    <tr>
                        <td>Call</td>
                        <td>Effective Call</td>
                        <td>Total Waktu (Menit)</td>
                        <td>Rata Rata / Transaksi (Menit)</td>
                    </tr>
                </table>
            </div>
            <div style="margin-top: 20px">
                <table class="datatable3" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode</th>
                            <th>Nama Pelanggan</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Durasi (Menit)</th>
                            <th>Penjualan</th>
                            <th>Jarak (Km)</th>
                            {{-- <th>Retur</th>
                            <th>Tunai/Tagihan</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalminutes = 0;
                            $jmldatacheckin = 0;
                            $lokasi_cabang = explode(',', $cabang->lokasi_cabang);
                            $lat_start = $lokasi_cabang[0];
                            $long_start = $lokasi_cabang[1];
                        @endphp
                        @foreach ($salesperfomance as $d)
                            @php
                                if (!empty($d->checkin_time) && !empty($d->checkout_time)) {
                                    $checkin = new DateTime($d->checkin_time);
                                    $checkout = new DateTime($d->checkout_time);
                                    $diff = $checkin->diff($checkout);
                                    $minutes = $diff->days * 24 * 60 + $diff->h * 60 + $diff->i;
                                    if (!empty($d->checkout_time)) {
                                        $totalminutes += $minutes;
                                        $jmldatacheckin += 1;
                                    }
                                } else {
                                    $minutes = 0;
                                }

                                $jarak = hitungJarak($lat_start, $long_start, $d->latitude, $d->longitude);

                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->kode_pelanggan }}</td>
                                <td>{{ textUpperCase($d->nama_pelanggan) }}</td>
                                <td class="center">{{ date('H:i:s', strtotime($d->checkin_time)) }}</td>
                                <td class="center">{{ date('H:i:s', strtotime($d->checkout_time)) }}</td>
                                <td class="center">{{ $minutes }}</td>
                                <td class="center">
                                    @if (!empty($d->cekpenjualan))
                                        &#10004;
                                    @endif
                                </td>
                                <td class="center">{{ formatAngkaDesimal($jarak / 1000) }}</td>

                            </tr>
                            @php
                                $lat_start = $d->latitude;
                                $long_start = $d->longitude;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</body>
<script>
    var totalmenit = "{{ $totalminutes }}";
    var jmldatacheckin = "{{ $jmldatacheckin }}";
    var ratarata = parseInt(totalmenit) / parseInt(jmldatacheckin);
    document.getElementById("totalminutes").innerHTML = totalmenit;
    document.getElementById("ratarataminutes").innerHTML = Math.floor(ratarata);
</script>

</html>

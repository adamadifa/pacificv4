<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Routing Salesaman {{ date('Y-m-d H:i:s') }}</title>
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
            LAPORAN ROUTING SALESMAN <br>
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
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kode Pelanggan</th>
                        <th>Nama Pelanggan</th>
                        <th>Wilayah</th>
                        <th>Jadwal Kunjungan</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($routing as $d)
                        @php
                            if (str_contains($d->routing, getNamahari($d->tanggal))) {
                                $bgcolor = '';
                                $color = '';
                                $ket = '';
                            } else {
                                $bgcolor = 'red';
                                $color = 'white';
                                $ket = 'Tidak Sesuai Jadwal';
                            }
                        @endphp
                        <tr style="background-color: {{ $bgcolor }}; color: {{ $color }};">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ getNamahari($d->tanggal) . ',' . DateToIndo($d->tanggal) }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ textUpperCase($d->nama_pelanggan) }}</td>
                            <td>{{ $d->nama_wilayah }}</td>
                            <td>{{ $d->routing }}</td>
                            <td>{{ $ket }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

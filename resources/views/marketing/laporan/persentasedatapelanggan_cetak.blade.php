<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Persentase Data Pelanggan {{ date('Y-m-d H:i:s') }}</title>
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
            REKAP PERSENTASE Data Pelanggan <br>
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
                        <th rowspan="2">NO</th>
                        <th rowspan="2">Kode</th>
                        <th rowspan="2">Nama Salesman</th>
                        <th rowspan="2">Cabang</th>
                        <th rowspan="2">Jumlah <br> Pelanggan Aktif</th>
                        <th colspan="4">Lokasi</th>
                        <th colspan="2">No. HP</th>
                        <th colspan="4">Tanda Tangan</th>
                    </tr>
                    <tr>
                        <th>Terisi</th>
                        <th>Persentase</th>
                        <th>Update By SFA</th>
                        <th>Persentase</th>

                        <th>Terisi</th>
                        <th>Persentase</th>

                        <th>Pemilik</th>
                        <th>Persentase</th>
                        <th>Karyawan</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($persentasedatapelanggan as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_salesman }}</td>
                            <td>{{ $d->nama_salesman }}</td>
                            <td>{{ $d->kode_cabang }}</td>
                            <td class="center">{{ $d->jmlpelangganaktif }}</td>
                            <td class="center">{{ $d->lokasi }}</td>
                            <td class="center">
                                @php
                                    $persentaselokasiterisi = ($d->lokasi / $d->jmlpelangganaktif) * 100;
                                @endphp
                                {{ formatAngkaDesimal($persentaselokasiterisi, 2) }} %
                            </td>
                            <td class="center">{{ $d->updatebysfa }}</td>
                            <td class="center">
                                @php
                                    $persentaseupdatebysfa = ($d->updatebysfa / $d->jmlpelangganaktif) * 100;
                                @endphp
                                {{ formatAngkaDesimal($persentaseupdatebysfa, 2) }} %
                            </td>

                            <td class="center">{{ $d->nohpcomplete }}</td>
                            <td class="center">
                                @php
                                    $persentasenohpcomplete = ($d->nohpcomplete / $d->jmlpelangganaktif) * 100;
                                @endphp
                                {{ formatAngkaDesimal($persentasenohpcomplete, 2) }} %
                            </td>

                            <td class="center">{{ $d->signature_pemilik }}</td>
                            <td class="center">
                                @php
                                    $persentasesignaturepemilik = ($d->signature_pemilik / $d->jmlpelangganaktif) * 100;
                                @endphp
                                {{ formatAngkaDesimal($persentasesignaturepemilik, 2) }} %
                            </td>
                            <td class="center">{{ $d->signature_karyawan }}</td>
                            <td class="center">
                                @php
                                    $persentasesignaturekaryawan = ($d->signature_karyawan / $d->jmlpelangganaktif) * 100;
                                @endphp
                                {{ formatAngkaDesimal($persentasesignaturekaryawan, 2) }} %
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

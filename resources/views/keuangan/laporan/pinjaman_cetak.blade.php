<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PJP {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">

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
            PINJAMAN JANGKA PANJANG (PJP) <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
        @if ($departemen != null)
            <h4>
                {{ textUpperCase($departemen->nama_dept) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Pinjaman</th>
                        <th>Tanggal</th>
                        <th>Nik</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Departemen</th>
                        <th>Jumlah</th>
                        {{-- <th>Bayar</th> --}}
                        {{-- <th>Sisa Tagihan</th>
                        <th>Status</th> --}}
                        {{-- <th>Ket</th> --}}
                        <th>Mulai Cicilan</th>
                        <th>Angsuran</th>
                        <th>Angsuran / Bulan</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandtotal = 0;
                    @endphp
                    @foreach ($pjp as $d)
                        @php
                            $grandtotal += $d->jumlah_pinjaman;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->no_pinjaman }}</td>
                            <td>{{ DateToIndo($d->tanggal) }}</td>
                            <td>{{ $d->nik }}</td>
                            <td>{{ textCamelCase($d->nama_karyawan) }}</td>
                            <td>{{ $d->nama_jabatan }}</td>
                            <td>{{ $d->nama_dept }}</td>
                            <td class="right" style="font-weight: bold">{{ formatAngka($d->jumlah_pinjaman) }}</td>
                            {{-- <td class="right" style="font-weight: bold">{{ formatAngka($d->totalpembayaran) }}</td> --}}
                            <td>{{ DateToIndo($d->mulai_cicilan) }}</td>
                            <td class="center">{{ $d->angsuran }} Bulan</td>
                            <td class="right">{{ formatAngka($d->jumlah_angsuran) }} </td>
                            <td>{{ $d->kategori_jabatan == 'NM' ? 'NON MANAJEMEN' : 'MANAJEMEN' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="7" class="left" style="font-weight: bold">TOTAL</th>
                        <th class="right" style="font-weight: bold">{{ formatAngka($grandtotal) }}</th>
                        <th colspan="5"></th>
                    </tr>
            </table>
        </div>
    </div>
</body>

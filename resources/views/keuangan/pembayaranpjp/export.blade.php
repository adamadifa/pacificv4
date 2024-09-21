<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Ajuan Limit Kredit </title>
    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>

    <div class="body">
        <table class="datatable4">
            <tr>
                <th>Bulan</th>
                <td>{{ $namabulan[$potonganpjp->bulan] }}</td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td>{{ $potonganpjp->tahun }}</td>
            </tr>
        </table>
        <table class="datatable3">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No. Pinjaman</th>
                    <th>Nik</th>
                    <th>Nama Karyawan</th>
                    {{-- <th>Jabatan</th> --}}
                    <th>Dept</th>
                    <th>Kantor</th>
                    <th>Jumlah</th>
                    <th class="center">Cicilan Ke</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($historibayar as $d)
                    @php
                        $total += $d->jumlah;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->no_pinjaman }}</td>
                        <td>'{{ $d->nik }}</td>
                        <td>{{ textUpperCase($d->nama_karyawan) }}</td>
                        {{-- <td>{{ textUpperCase($d->nama_jabatan) }}</td> --}}
                        <td>{{ textUpperCase($d->kode_dept) }}</td>
                        <td>{{ textUpperCase($d->kode_cabang) }}</td>
                        <td class="right">{{ formatAngka($d->jumlah) }}</td>
                        <td class="center">{{ $d->cicilan_ke }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6">TOTAL</th>
                    <th class="right">{{ formatAngka($total) }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>

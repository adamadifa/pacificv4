<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Penilaian Karyawan {{ $penilaiankaryawan->nama_karyawan }} </title>
    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <style>
        @page {
            size: A4
        }

        body {
            font-family: 'Times New Roman';
            font-size: 14px
        }


        hr.style2 {
            border-top: 3px double #8c8b8b;
        }

        h4 {
            line-height: 1.1rem !important;
            margin: 0 0 5px 0 !important;
        }

        p {
            margin: 3px !important;
            line-height: 1.1rem;
        }

        ol {
            line-height: 1.2rem;
            margin: 0;
        }

        h3 {
            margin: 5px;
        }
    </style>
</head>

<body>

    <body class="A4">
        <!-- Each sheet element should have the class "sheet" -->
        <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
        <section class="sheet padding-10mm">
            <div>
                <table border=0>
                    <tr>
                        <td style="width: 10%">
                            @if ($penilaiankaryawan->kode_perusahaan == 'MP')
                                <img src="{{ asset('assets/img/logo/mp.png') }}" width="80" height="80" alt="">
                            @endif
                        </td>
                        <td style="font-weight: bold; text-align:center; width:55%">
                            <h3>FORMULIR EVALUASI KARYAWAN MASA PERCOBAAN DAN KONTRAK</h3>
                        </td>
                        <td style="width: 35%" valign="top">
                            <table style="border: 1px solid; border-collapse:collapse;">
                                <tr>
                                    <td style="font-size:14px; padding:3px">No. Dok : FRM.HRD.01.04. Rev.05</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="display:flex; justify-content: space-between">
                <div>
                    <table class="datatable3">
                        <tr>
                            <td style="font-weight: bold">Periode Kontrak / Masa Percobaan</td>
                            <td>
                                {{ DateToIndo($penilaiankaryawan->kontrak_dari) }} s/d {{ DateToIndo($penilaiankaryawan->kontrak_sampai) }}
                            </td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>{{ $penilaiankaryawan->nik }}</td>
                        </tr>
                        <tr>
                            <td>Nama Karyawan</td>
                            <td>{{ $penilaiankaryawan->nama_karyawan }}</td>
                        </tr>
                        <tr>
                            <td>Departemen / Posisi</td>
                            <td>{{ $penilaiankaryawan->nama_dept }} / {{ $penilaiankaryawan->nama_jabatan }}</td>
                        </tr>
                    </table>
                </div>
                <div>
                    @if (Storage::disk('public')->exists('/karyawan/' . $penilaiankaryawan->foto))
                        <img src="{{ getfotoKaryawan($penilaiankaryawan->foto) }}" class="card-img"
                            style="width: 120px; height:150px; object-fit:cover; border-radius:10px; position:absolute; right:70px; top:80px;">
                    @else
                        @if ($penilaiankaryawan->jenis_kelamin == 'L')
                            <img src="{{ asset('assets/img/avatars/male.jpg') }}" class="card-img"
                                style="width: 120px; height:150px; object-fit:cover; border-radius:10px; position:absolute; right:70px; top:80px;">
                        @else
                            <img src="{{ asset('assets/img/avatars/female.jpg') }}" class="card-img"
                                style="width: 120px; height:150px; object-fit:cover; border-radius:10px; position:absolute; right:70px; top:80px;">
                        @endif
                    @endif
                </div>
            </div>

            <div style="margin-top:20px">
                <b style="font-size:14px">A. Penilaian</b>
            </div>
            <div style="margin-top:10px">
                <table class="datatable8">
                    <thead>
                        <tr>
                            <th style="width:5%" rowspan="2">No</th>
                            <th style="width:40%" rowspan="2">Faktor Penilaian</th>
                            <th style="width:30%" colspan="2" align="center">Hasil Penilaian</th>
                        </tr>
                        <tr style="font-weight: bold;  text-align:center">
                            <td>Tidak Memuaskan</td>
                            <td>Sangat Memuaskan</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penilaian_item as $d)
                            <tr>
                                <td rowspan="2">{{ $loop->iteration }}</td>
                                <td style="background-color: rgb(41, 155, 212)">{{ $d->nama_kategori }}</td>
                                <td style="text-align: center" rowspan="2">{!! $d->nilai == 0 ? '&#10004' : '' !!}</td>
                                <td style="text-align: center" rowspan="2">{!! $d->nilai == 1 ? '&#10004' : '' !!}</td>
                            </tr>
                            <tr>
                                <td>{{ $d->item_penilaian }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        <section class="sheet padding-10mm">
            <div>
                <b style="font-size:14px">B. Kehadiran Absensi</b>
            </div>
            <div style="margin-top:10px">
                <table style="font-size:14px" class="datatable4">
                    <tr>
                        <td>SID</td>
                        <td>:</td>
                        <td>{{ $penilaiankaryawan->sid }}</td>
                        <td>Izin</td>
                        <td>:</td>
                        <td>{{ $penilaiankaryawan->izin }}</td>
                    </tr>
                    <tr>
                        <td>Sakit</td>
                        <td>:</td>
                        <td>{{ $penilaiankaryawan->sakit }}</td>
                        <td>Alfa</td>
                        <td>:</td>
                        <td>{{ $penilaiankaryawan->alfa }}</td>
                    </tr>
                </table>
            </div>
            <div style="margin-top:20px">
                <b style="font-size:14px">C. Masa Kontrak Kerja</b>
            </div>
            <div style="margin-top:10px">
                <table class="datatable8" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Tidak Di Perpanjang</th>
                            <th>3 Bulan</th>
                            <th>6 Bulan</th>
                            <th>Karyawan Tetap</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td align="center">{!! $penilaiankaryawan->masa_kontrak == 'TP' ? '&#10004' : '' !!}</td>
                            <td align="center">{!! $penilaiankaryawan->masa_kontrak == 'K3' ? '&#10004' : '' !!}</td>
                            <td align="center">{!! $penilaiankaryawan->masa_kontrak == 'K6' ? '&#10004' : '' !!}</td>
                            <td align="center">{!! $penilaiankaryawan->masa_kontrak == 'KT' ? '&#10004' : '' !!}</td>

                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="margin-top:10px">
                <b style="font-size:14px">D. Riwayat Absensi dan Rekomendasi User</b>
            </div>
            <div style="border:1px solid; border-collapse:collapse; height:100px; font-size:14px; padding:8px; margin-top:10px">
                {{ $penilaiankaryawan->rekomendasi }}
            </div>
            <div style="margin-top:10px">
                <b style="font-size:14px">E. Evaluasi Skill Teknis / Kinerja (Wajib Diisi Oleh User)</b>
            </div>
            <div style="border:1px solid; border-collapse:collapse; height:100px; font-size:14px; padding:8px; margin-top:10px">
                {{ $penilaiankaryawan->evaluasi }}
            </div>
            <div style="margin-top:10px">
                <b style="font-size:14px">F. Riwayat Kontrak Karyawan</b>
            </div>
            <div style="margin-top:10px">
                <table class="datatable3" style="width:100%">
                    <tr>
                        <td style="height:200px; width:50%; vertical-align:top">
                            @foreach ($historikontrak as $d)
                                <b>Kontrak Ke {{ $loop->iteration }} </b> : {{ DateToIndo($d->dari) }} s/d {{ DateToIndo($d->sampai) }} <br>
                            @endforeach
                        </td>
                        <td style="height:200px; width:50%; vertical-align:top">
                            <b>Pemutihan :</b><br>
                            @foreach ($historipemutihan as $d)
                                <b>{{ $loop->iteration }} </b> : {{ DateToIndo($d->tanggal) }} <br>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </div>
        </section>
    </body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Ajuan PJP </title>
    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <style>
        @page {
            size: A4
        }


        .judul {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 20px;
            text-align: center;
            color: #005e2f
        }

        .judul2 {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 16px;


        }

        .huruf {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .ukuranhuruf {
            font-size: 12px;
        }


        hr.style2 {
            border-top: 3px double #8c8b8b;
        }
    </style>
</head>

<body>

    <body class="A4">

        <!-- Each sheet element should have the class "sheet" -->
        <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
        <section class="sheet padding-10mm">
            <table style="width: 100%">
                <tr>
                    <td style="text-align: center">
                        <h3 style="font-family:'Cambria'; line-height:0px">PINJAMAN KARYAWAN</h3>
                        <h3 style="font-family:'Cambria'; line-height:0px">CV. PACIFIC & CV. MAKMUR PERMATA</h3>
                        <span style="font-family:'Times New Roman'">Jl. Perintis Kemerdekaan No. 160 Tasikmalaya</span><br>
                        <span style="font-size: 12px">Telp (0265) 336794 Fax (0265) 332329</span><br>
                        <span style="font-size: 11px"><i>e-mail : pacific.tasikmalaya@gmail.com</i></span>
                    </td>
                </tr>
            </table>
            <hr>
            <u>
                <h3 style="font-family:'Cambria'; line-height:0px; text-align:center">FORMULIR PENGAJUAN PINJAMAN KARYAWAN
                </h3>
            </u>
            <table class="datatable4">
                <tr>
                    <td style="width: 200px">NIK</td>
                    <td>:</td>
                    <td>{{ $pjp->nik }}</td>
                </tr>
                <tr>
                    <td>Nama Karyawan</td>
                    <td>:</td>
                    <td>{{ $pjp->nama_karyawan }}</td>
                </tr>
                <tr>
                    <td>Status Karyawan</td>
                    <td>:</td>
                    <td>{{ $pjp->status_karyawan == 'T' ? 'Tetap' : 'Kontrak' }}</td>
                </tr>
                <tr>
                    <td>Jabatan/Posisi Kerja</td>
                    <td>:</td>
                    <td>{{ ucwords(strtolower($pjp->nama_jabatan)) }}</td>
                </tr>
                <tr>
                    <td>Fasilitas Pinjaman</td>
                    <td>:</td>
                    <td>Pinjaman Jangka Panjang (PJP)</td>
                </tr>
                <tr>
                    <td>Acc. Pencairan</td>
                    <td>:</td>
                    <td>{{ formatRupiah($pjp->jumlah_pinjaman) }}</td>
                </tr>
                <tr>
                    <td>Terbilang</td>
                    <td>:</td>
                    <td>{{ ucwords(terbilang($pjp->jumlah_pinjaman)) }} Rupiah</td>
                </tr>
                <tr>
                    <td>Jangka Waktu</td>
                    <td>:</td>
                    <td>{{ $pjp->angsuran }} bulan</td>
                </tr>
                <tr>
                    <td> Cicilan/Bulan </td>
                    <td>:</td>
                    <td>{{ formatRupiah($pjp->jumlah_angsuran) }}</td>
                </tr>
            </table>
            <p>
            <ol>
                <li style="line-height: 1.5rem">
                    Apabila di kemudian hari saya tidak lagi bekerja di perusahaan ini, maka sisa cicilan pinjaman
                    yang belum lunas akan di selesaikan dan diperhitungkan dari uang yang saya terima saat saya
                    berhenti kerja
                </li>
                <li style="line-height: 1.5rem">
                    Apabila uang yang diperoleh saat saya berhenti bekerja tersebut tidak mencukupi maka saya
                    akan menyelesaikan sisa cicilan pinjaman ini secara pribadi.
                </li>

            </ol>
            </p>
            <p>
                Demikian pengajuan ini saya buat dengan sebenarnya dan atas keinginan sendiri
            </p>
            <table style="width: 100% !important">
                <tr>
                    <td colspan="2"></td>
                    <td style="text-align: center">Tasikmalaya, {{ DateToIndo($pjp->tanggal) }}</td>
                </tr>
                <tr>
                    <td style="text-align: center; width:30%;"">Pemohon,</td>
                    <td style=" text-align: center; width:30%; vertical-align:top">Diverifikasi Oleh,</td>
                    <td style=" text-align: center; width:30%; vertical-align:top">Menyetujui,</td>
                </tr>
                <tr>
                    <td style="height: 90px"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr style="font-weight: bold">
                    <td class="center">{{ $pjp->nama_karyawan }}</td>
                    <td class="center">Head Departemen</td>
                    <td class="center">Panitia Kredit / Keuangan</td>
                </tr>
            </table>
        </section>
        <section class="sheet padding-10mm">
            <table style="width: 100%">
                <tr>
                    <td style="text-align: left">
                        <h3 style="font-family:'Cambria'; line-height:0px">MAKMUR PERMATA GROUP</h3>
                        <i style="font-family:'Cambria';">Factory / Head Office :</i>
                        <br>
                        <span style="font-family:'Times New Roman'">Jl. Perintis Kemerdekaan No. 160 Tasikmalaya</span><br>
                        <span style="font-size: 12px">Telp (0265) 336794 Fax (0265) 332329</span><br>
                        <span style="font-size: 11px">e-mail : pacific.tasikmalaya@gmail.com</span>
                    </td>
                </tr>
            </table>
            <h3 style="font-family:'Cambria'; line-height:0px; text-align:center; margin-top:40px">
                SURAT PERNYATAAN PERSETUJUAN PASANGAN PEKERJA
            </h3>
            <h4 style="font-family:'Cambria'; line-height:0px; text-align:center; margin-top:20px">
                Nomor : {{ $pjp->no_pinjaman }}
            </h4>
            <p style="margin-top:20px">
                Saya yang bertanda tangan di bawah ini :
            </p>
            <table class="datatable4">
                <tr>
                    <td style="width: 200px">NIK</td>
                    <td>:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Nama </td>
                    <td>:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>No. KTP</td>
                    <td>:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>No. Handphone</td>
                    <td>:</td>
                    <td></td>
                </tr>
            </table>
            <p>
                Adalah benar pasangan sah sebagai suami istri dari pekerja :
            </p>
            <table class="datatable4">
                <tr>
                    <td>Nama Karyawan</td>
                    <td>:</td>
                    <td>{{ $pjp->nama_karyawan }}</td>
                </tr>
                <tr>
                    <td style="width: 200px">NIK</td>
                    <td>:</td>
                    <td>{{ $pjp->nik }}</td>
                </tr>
                <tr>
                    <td>Departemen</td>
                    <td>:</td>
                    <td>{{ ucwords(strtolower($pjp->nama_dept)) }}</td>
                </tr>
                <tr>
                    <td>Jabatan/Posisi Kerja</td>
                    <td>:</td>
                    <td>{{ ucwords(strtolower($pjp->nama_jabatan)) }}</td>
                </tr>
            </table>
            <p style="line-height: 1.5rem">
                Dengan ini menyatakan memberikan persetujuan sepenuhnya kepada pasangan saya untuk menggunakan fasilitas
                pinjaman di perusahaan MAKMUR PERMATA GROUP sebesar Rp {{ formatRupiah($pjp->jumlah_pinjaman) }} dengan
                kewajiban angsuran Rp {{ formatRupiah($pjp->jumlah_angsuran) }} selama {{ $pjp->angsuran }} kali
                dipotong dari
                gaji setiap bulan.
            </p>
            <p style="line-height: 1.5rem">
                Demikian persetujuan ini diberikan untuk dipergunakan sebagaimana mestinya.
            </p>
            <table style="width: 100% !important">
                <tr>
                    <td colspan="2" style="width: 60%"></td>
                    <td style="text-align: center; width:30%">Tasikmalaya, {{ DateToIndo($pjp->tanggal) }}
                        <br>
                        Yang Membuat Pernyataan
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td style=" text-align: center; height:90px; vertical-align:top; border-bottom:1px solid #000">

                    </td>
                </tr>
            </table>
        </section>
    </body>

</html>

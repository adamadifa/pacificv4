<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Kesepakatan Bersama {{ $kesepakatanbersama->no_kontrak }} {{ $kesepakatanbersama->nama_karyawan }} </title>
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
            @if ($kesepakatanbersama->kode_cabang == 'PST')
                <table style="width: 100%">
                    <tr>
                        <td style="width: 20%; text-align:center">
                            <img src="{{ asset('assets/img/logo/mp.png') }}" alt="" style="width: 80px; height:80px">
                        </td>
                        <td style="text-align: left">
                            <h3 style="font-family:'Cambria'; line-height:0px ">CV MAKMUR PERMATA</h3>
                            <span style="font-size: 1.2rem"><i>Factory / Head Office</i></span><br>
                            <span style="font-family:'Times New Roman'">Jl. Perintis Kemerdekaan No. 160
                                Tasikmalaya</span><br>
                            <span style="font-size: 12px">Telp (0265) 336794 Fax (0265) 332329</span><br>
                            <span style="font-size: 12px">e-mail : pacific.tasikmalaya@gmail.com</span>
                        </td>
                        <td></td>
                    </tr>
                </table>
            @else
                <table style="width: 100%">
                    <tr>

                        <td style="text-align: left">
                            <h3 style="font-family:'Cambria'; line-height:0px">{{ $kesepakatanbersama->nama_pt }}</h3>
                            <span style="font-size: 1.2rem"><i>Factory / Head Office</i></span><br>
                            <span style="font-family:'Times New Roman'">{{ $kesepakatanbersama->alamat_cabang }}</span><br>
                            <span style="font-size: 12px">{{ $kesepakatanbersama->email }}</span><br>
                        </td>
                        <td></td>
                    </tr>
                </table>
            @endif
            <hr>
            <h3 style="text-align:center"><u>KESEPAKATAN BERSAMA</u></h3>
            <p>
                Yang bertandatangan dibawah ini :
            <table>
                <tr>
                    <td style="width: 30px">I.</td>
                    <td style="width:100px">Nama</td>
                    <td>:</td>
                    <td>{{ $pihak_satu['nama_pihak_satu'] }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ $pihak_satu['jabatan_pihak_satu'] }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $kesepakatanbersama->alamat_cabang }}</td>
                </tr>
            </table>
            </p>
            <p style="text-indent:1cm; text-align:justify">
                Untuk selanjutnya disebut PIHAK PERTAMA ( I ) dan bertindak atas nama CV. Makmur Permata yang
                beralamat di Jl. Perintis Kemerdekaan No. 160 Tasikmalaya.
            </p>
            <p>
            <table>
                <tr>
                    <td style="width: 30px">II.</td>
                    <td style="width:100px">Nama</td>
                    <td>:</td>
                    <td>{{ $kesepakatanbersama->nama_karyawan }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ $kesepakatanbersama->nama_jabatan }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{ $kesepakatanbersama->nik }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Masa Kerja</td>
                    <td>:</td>
                    <td>
                        @php
                            $masakerja = hitungMasakerja($kesepakatanbersama->tanggal_masuk, $kesepakatanbersama->tanggal);
                        @endphp
                        {{ $masakerja['tahun'] }} Tahun {{ $masakerja['bulan'] }} Bulan {{ $masakerja['hari'] }} Hari
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $kesepakatanbersama->alamat_karyawan }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>No. Identitas</td>
                    <td>:</td>
                    <td>{{ $kesepakatanbersama->no_ktp }}</td>
                </tr>
            </table>
            </p>
            <p style="text-indent:1cm; text-align:justify">
                Untuk selanjutnya disebut PIHAK KEDUA ( II) atau pekerja.<br>
                Pada tanggal {{ DateToIndo($kontrak->sampai) }} PIHAK PERTAMA ( I ) dan PIHAK KEDUA ( II ) bertempat di CV
                Makmur Permata Tasikmalaya telah mengadakan perundingan atau musyawarah mufakat yang mendalam secara
                kekeluargaan dengan menghasilkan kesepakatan sebagai berikut :
                <br>
            <ol>
                <li>
                    PIHAK PERTAMA (I) dan PIHAK KEDUA (II) telah sepakat terkait kontrak kerja yang diputihkan mulai tanggal
                    {{ DateToIndo($kontrak->sampai) }}
                </li>
                <li>
                    PIHAK PERTAMA ( I ) bersedia untuk memberikan kompensasi atau kebijakan kepada PIHAK KEDUA ( II ) yang
                    besarnya sebagai berikut :
                </li>
            </ol>
            </p>
            <p>
            <table class="datatable6">
                <tr>
                    <th>Rincian Upah</th>
                    <th>
                        Perhitungan Besaran Uang Masa Kerja <br>
                        Masa Kerja :
                        @php
                            $tanggal = $jmk != null ? $jmk->tanggal : $kontrak->dari;
                            $nextmonth = date('Y-m-d', strtotime('+1 month', strtotime($tanggal)));

                            $masakerjakb = hitungMasakerja($nextmonth, $kontrak->sampai);

                        @endphp
                        {{-- {{ $jmk->tanggal }} - {{ $kontrak->dari }} -  --}}
                        {{ $nextmonth }} s/d {{ $kontrak->sampai }}
                        {{ $masakerjakb['tahun'] }} Tahun {{ $masakerjakb['bulan'] }} Bulan {{ $masakerjakb['hari'] }} Hari
                    </th>
                </tr>
                <tr>
                    <td>
                        <table class="datatable7">
                            <tr>
                                <td>Gaji Pokok</td>
                                <td>:</td>
                                <td>Rp.</td>
                                <td style="text-align: right">{{ formatRupiah($kesepakatanbersama->gaji_pokok) }}</td>
                            </tr>
                            <tr>
                                <td>Tj. Jabatan</td>
                                <td>:</td>
                                <td>Rp.</td>
                                <td style="text-align: right">{{ formatRupiah($kesepakatanbersama->t_jabatan) }}</td>
                            </tr>
                            <tr>
                                <td>Tj.Tanggung Jawab</td>
                                <td>:</td>
                                <td>Rp.</td>
                                <td style="text-align: right">{{ formatRupiah($kesepakatanbersama->t_tanggungjawab) }}</td>
                            </tr>
                            <tr>
                                <td>Uang Makan</td>
                                <td>:</td>
                                <td>Rp.</td>
                                <td style="text-align: right">{{ formatRupiah($kesepakatanbersama->t_makan) }}</td>
                            </tr>
                            <tr>
                                <td>Skill Khusus</td>
                                <td>:</td>
                                <td>Rp.</td>
                                <td style="text-align: right">{{ formatRupiah($kesepakatanbersama->t_skill) }}</td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top" rowspan="2">
                        <table class="datatable7">
                            <tr>
                                @php
                                    $mk_kb = $masakerjakb['tahun'];
                                    if ($mk_kb >= 3 && $mk_kb < 6) {
                                        $jmlkali = 2;
                                    } elseif ($mk_kb >= 6 && $mk_kb < 9) {
                                        $jmlkali = 3;
                                    } elseif ($mk_kb >= 9 && $mk_kb < 12) {
                                        $jmlkali = 4;
                                    } elseif ($mk_kb >= 12 && $mk_kb < 15) {
                                        $jmlkali = 5;
                                    } elseif ($mk_kb >= 15 && $mk_kb < 18) {
                                        $jmlkali = 6;
                                    } elseif ($mk_kb >= 18 && $mk_kb < 21) {
                                        $jmlkali = 7;
                                    } elseif ($mk_kb >= 21 && $mk_kb < 24) {
                                        $jmlkali = 8;
                                    } elseif ($mk_kb >= 24) {
                                        $jmlkali = 10;
                                    } else {
                                        $jmlkali = 0.5;
                                    }

                                    if ($mk_kb <= 2) {
                                        $totalupah = $kesepakatanbersama->gaji_pokok;
                                    } else {
                                        $totalupah =
                                            $kesepakatanbersama->gaji_pokok +
                                            $kesepakatanbersama->t_tanggungjawab +
                                            $kesepakatanbersama->t_makan +
                                            $kesepakatanbersama->t_skill +
                                            $kesepakatanbersama->t_jabatan;
                                    }

                                    $grandtotal_upah =
                                        $kesepakatanbersama->gaji_pokok +
                                        $kesepakatanbersama->t_tanggungjawab +
                                        $kesepakatanbersama->t_makan +
                                        $kesepakatanbersama->t_skill +
                                        $kesepakatanbersama->t_jabatan;

                                    if ($jmk != null) {
                                        if ($masakerjakb['tahun'] == 1 and $masakerjakb['bulan'] >= 3) {
                                            $persentasejmk = 25;
                                        } else {
                                            $persentasejmk = 15;
                                        }
                                    } else {
                                        $persentasejmk = 25;
                                    }
                                    $totalpemutihan = ($persentasejmk / 100) * $totalupah;
                                @endphp
                                <td style="width: 2px">1.</td>
                                <td>Jasa Masa Kerja </td>
                                <td>{{ $persentasejmk }}%</td>
                                <td>x</td>
                                <td>Rp. {{ formatRupiah($totalupah) }}</td>
                                <td>Rp.</td>
                                <td style="text-align:right">{{ formatRupiah($totalpemutihan) }}</td>
                            </tr>
                            <tr>
                                <td style="width: 2px; border-bottom:1px solid black">2.</td>
                                <td style="border-bottom:1px solid black">Uang Pengganti Hak</td>
                                <td style="border-bottom:1px solid black">0%</td>
                                <td style="border-bottom:1px solid black">x</td>
                                <td style="border-bottom:1px solid black">Rp. {{ formatRupiah($totalpemutihan) }}</td>
                                <td style="border-bottom:1px solid black">Rp.</td>
                                <td style="border-bottom:1px solid black; text-align:right">
                                    @php
                                        $uph = (0 / 100) * ($jmlkali * $totalupah);
                                    @endphp
                                    {{ formatRupiah($uph) }}
                                </td>
                            </tr>
                            <tr style="font-weight:bold">
                                <td colspan="5">Jumlah Uang Jasa Masa Kerja</td>
                                <td>Rp.</td>
                                <td style="text-align:right; font-weight:bold">
                                    @php
                                        $jml_ujmk = ($persentasejmk / 100) * $totalupah + $uph;
                                    @endphp
                                    {{ formatRupiah($jml_ujmk) }}
                                </td>
                            </tr>
                            @php
                                $totalpotongan = 0;
                            @endphp
                            @foreach ($potongan as $d)
                                @php
                                    $totalpotongan += $d->jumlah;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td colspan="4">{{ $d->keterangan }}</td>
                                    <td>Rp.</td>
                                    <td style="text-align: right">{{ formatRupiah($d->jumlah) }}</td>
                                </tr>
                            @endforeach
                            <tr style="font-weight:bold">
                                <td colspan="5" style="border-bottom:1px solid black">Jumlah Potongan</td>
                                <td style="border-bottom:1px solid black">Rp.</td>
                                <td style="border-bottom:1px solid black; text-align:right">{{ formatRupiah($totalpotongan) }}
                                </td>
                            </tr>
                            <tr style="font-weight:bold">
                                <td colspan="5">Jumlah Uang Yang Diterima Karyawan</td>
                                <td>Rp.</td>
                                <td style="text-align:right">
                                    @php
                                        $totalditerima = $jml_ujmk - $totalpotongan;
                                    @endphp
                                    {{ formatRupiah($totalditerima) }}
                                </td>

                            </tr>

                        </table>
                    </td>
                </tr>
                <tr style="font-weight:bold">
                    <td>
                        <table class="datatable7">
                            <tr>
                                <td style="font-weight:bold">Total Upah</td>
                                <td style="font-weight:bold; text-align:right">

                                    {{ formatRupiah($grandtotal_upah) }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </p>
            <p>
            <ol start="3">
                <li>
                    PIHAK KEDUA ( II ) dapat menerima dengan baik kompensasi atau kebijakan dari PIHAK PERTAMA (I) seperti
                    tersebut di atas.
                </li>
                <li>
                    Dengan ditandatanganinya kesepakatan bersama ini oleh kedua belah pihak, PIHAK PERTAMA ( I ) dan PIHAK
                    KEDUA ( II ) menyatakan permasalahan telah selesai dan tidak ada saling menuntut apapun dikemudian hari.
                </li>
            </ol>
            Demikian Kesepakatan Bersama ini dibuat dan ditandatangani oleh kedua belah pihak.
            </p>
            <table class="datatable7">
                <tr>
                    <td colspan="4" style="text-align: center">Tasikmalaya, {{ DateToIndo($kontrak->sampai) }}</td>
                </tr>
                <tr>
                    <td style="text-align:center">PIHAK KEDUA</td>
                    <td style="text-align:center" colspan="2">PIHAK PERTAMA</td>
                    <td style="text-align:center">MENYETUJUI</td>
                </tr>
                <tr>
                    <td style="height: 70px"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:center">
                        <u>{{ $kesepakatanbersama->nama_karyawan }}</u><br>
                        Karyawan
                    </td>
                    <td style="text-align:center">
                        <u>{{ $pihak_satu['nama_pihak_satu'] }}</u><br>
                        {{ $pihak_satu['jabatan_pihak_satu'] }}
                    </td>
                    <td style="text-align:center">
                        <u>Eris Fardiana</u><br>
                        GM Operasional
                    </td>
                    <td style="text-align:center">
                        <u>Jemmy Feldiana</u><br>
                        Direktur
                    </td>

                </tr>
            </table>
        </section>
    </body>

</html>

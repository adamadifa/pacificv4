<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Kesepakatan</title>
    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <style>
        @page {
            size: A4 landscape
        }

        body {
            font-family: 'Times New Roman';
            font-size: 13px;
            line-height: 1.35;
        }

        hr.style2 {
            border-top: 3px double #8c8b8b;
        }

        h4 {
            line-height: 1.2rem !important;
            margin: 0 0 5px 0 !important;
        }

        p {
            margin: 5px 0 !important;
            line-height: 1.35;
        }

        ol {
            line-height: 1.35;
            margin: 0;
            padding-left: 20px;
        }

        h3 {
            margin: 5px 0;
            font-size: 16px;
        }

        .table td {
            padding: 3px 6px;
        }

        .datatable3 {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-top: 15px;
        }

        .datatable3 th {
            background-color: #004d80;
            color: #ffffff;
            border: 1px solid #002b4d;
            padding: 7px;
            font-weight: bold;
            text-align: center;
        }

        .datatable3 td {
            border: 1px solid #b3d1ff;
            padding: 6px;
            text-align: center;
        }

        .datatable3 tfoot th, .datatable3 tfoot td {
            background-color: #004d80;
            color: #ffffff;
            border: 1px solid #002b4d;
            padding: 7px;
            font-weight: bold;
        }

        .container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            gap: 30px;
        }

        .left-col {
            width: 50%;
        }

        .right-col {
            width: 48%;
        }
    </style>
</head>

<body class="A4 landscape">
    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">
        <div class="container">
            <!-- Left Column: Company details, titles, Pihak 1, Pihak 2, and bullet points 1-7 -->
            <div class="left-col">
                <table style="width: 100%">
                    <tr>
                        <td style="text-align: left">
                            <h3 style="font-family:'Cambria'; margin-bottom: 2px">{{ $kesepakatan->nama_pt }}</h3>
                            <span style="font-family:'Times New Roman'; font-size: 11px">{{ $kesepakatan->alamat_cabang }}</span><br>
                            <span style="font-size: 11px; color: #004d80;">{{ $kesepakatan->email }}</span><br>
                        </td>
                    </tr>
                </table>

                <h3 style="text-align: center; margin-top: 10px; margin-bottom: 10px; font-weight: bold;">
                    SURAT KESEPAKATAN TARGET
                    <br>
                    PROGRAM {{ $kesepakatan->nama_program }}
                </h3>

                <p>Saya Yang Bertanda Tangan dibawah ini :</p>
                <table class="table" style="width: 100%">
                    <tr>
                        <td style="width: 35%; font-weight: bold;">Nama Lengkap</td>
                        <td style="width: 2%">:</td>
                        <td style="border-bottom: 1px solid black">{{ $kesepakatan->nama_salesman }}</td>
                    </tr>
                    <tr>
                        <td style="width: 35%; font-weight: bold;">Alamat Lengkap Tempat Tinggal</td>
                        <td style="width: 2%">:</td>
                        <td style="border-bottom: 1px solid black">{{ $kesepakatan->alamat_cabang }}</td>
                    </tr>
                    <tr>
                        <td style="width: 35%; font-weight: bold;">NIK KTP</td>
                        <td style="width: 2%">:</td>
                        <td style="border-bottom: 1px solid black"></td>
                    </tr>
                </table>
                
                <p style="margin-top: 8px !important; font-weight: bold;">Yang Selanjutnya disebut sebagai Pihak ke -1 (Perwakilan Perusahaan)</p>
                
                <table class="table" style="width: 100%; margin-top: 5px;">
                    <tr>
                        <td style="width: 35%; font-weight: bold;">Nama Lengkap</td>
                        <td style="width: 2%">:</td>
                        <td style="border-bottom: 1px solid black">{{ textUpperCase($kesepakatan->nama_pelanggan) }}</td>
                    </tr>
                    <tr>
                        <td style="width: 35%; font-weight: bold;">Alamat Lengkap Tempat Tinggal</td>
                        <td style="width: 2%">:</td>
                        <td style="border-bottom: 1px solid black">{{ textCamelCase($kesepakatan->alamat_pelanggan) }}</td>
                    </tr>
                    <tr>
                        <td style="width: 35%; font-weight: bold;">NIK KTP</td>
                        <td style="width: 2%">:</td>
                        <td style="border-bottom: 1px solid black">{{ $kesepakatan->nik }}</td>
                    </tr>
                    <tr>
                        <td style="width: 35%; font-weight: bold;">No. HP / No. Telp Rumah</td>
                        <td style="width: 2%">:</td>
                        <td style="border-bottom: 1px solid black">{{ $kesepakatan->no_hp_pelanggan }}</td>
                    </tr>
                    <tr>
                        <td style="width: 35%; font-weight: bold;">Alamat Toko</td>
                        <td style="width: 2%">:</td>
                        <td style="border-bottom: 1px solid black">{{ textCamelCase($kesepakatan->alamat_toko) }}</td>
                    </tr>
                    <tr>
                        <td style="width: 35%; font-weight: bold;">Kode Pelanggan ( Diisi Pihak Ke - 1)</td>
                        <td style="width: 2%">:</td>
                        <td style="border-bottom: 1px solid black">{{ textupperCase($kesepakatan->kode_pelanggan) }}</td>
                    </tr>
                </table>

                <p style="margin-top: 8px !important; font-weight: bold;">Yang Selanjutnya disebut sebagai Pihak ke -2 (Pembeli)</p>
                
                <p style="margin-top: 8px !important;">
                    Adapun hak dan kewajiban antara Pihak Ke-1 dan Pihak Ke-2 yang harus disepakati bersama dalam surat kesepakatan ini antara lain :
                </p>
                <ol>
                    <li>Pihak Ke-2 Memilih target penjualan sebanyak {{ formatAngka($kesepakatan->qty_target + $kesepakatan->qty_avg) }} dus/ball selama satu semester</li>
                    <li>Periode Program dimulai dari bulan {{ $namabulan[date('m', strtotime($kesepakatan->periode_dari)) * 1] }} s/d
                        {{ $namabulan[date('m', strtotime($kesepakatan->periode_sampai)) * 1] }}
                        {{ date('Y', strtotime($kesepakatan->periode_sampai)) }}
                    </li>
                    <li>Dengan hadiah berupa Cashback sebesar Rp.{{ formatAngka($rate) }}
                        {{ $kesepakatan->tipe_reward == '2' ? 'Flat' : '/Dus/Ball' }}
                        @if ($kesepakatan->kode_program == 'PRIK003')
                            atau maksimal Cashback sebesar Rp 1.200.000,-
                        @endif
                    </li>
                    <li>Pihak Ke2 bersedia membeli barang sesuai dengan kesepakatan target satu semester</li>
                    <li>Khusus transaksi kredit Pihak Ke-2 bersedia melakukan pelunasan maksimal {{ $kesepakatan->top }} hari dari tanggal awal
                        transaksi, jika tidak memenuhi maka faktur tersebut tidak diperhitungkan dalam hitungan program.</li>
                    <li>Pihak Ke-2 bersedia melampirkan fotocopy KTP</li>
                    <li>Pihak Ke-1 dan Pihak Ke-2 bersedia melengkapi seluruh data yang ada pada surat kesepakatan ini</li>
                </ol>
            </div>

            <!-- Right Column: Bullet points 8-10, sign blocks, target table -->
            <div class="right-col">
                <ol start="8">
                    <li>Pengembalian produk oleh Pihak Ke-2 tidak dapat dilakukan dengan cara potong faktur maupun diuangkan. Pengembalian produk hanya
                        dapat dilakukan dengan cara tukar barang dengan produk sejenis.</li>
                    <li>Pihak Ke-1 tidak menerima pengembalian barang yang diakibatkan expired masa produk.</li>
                    <li>Pihak Ke-2 tidak diperkenankan turun target dari target yang telah disepakati</li>
                </ol>

                <p style="margin-top: 8px !important; margin-left: 20px;">
                    Pencairan reward akan dilakukan oleh Pihak ke-1 maksimal satu bulan dari periode program berakhir.
                </p>

                <p style="margin-top: 15px !important;">
                    Demikian surat kesepakatan ini dibuat atas dasar kesepakatan target program yang ditawarkan oleh {{ $kesepakatan->nama_pt }}. Apabila
                    terdapat kewajiban yang tidak terlaksana dari poin yang telah disebutkan diatas maka surat kesepakatan ini dapat dinyatakan gugur.
                </p>

                <p style="margin-top: 12px !important; font-weight: bold;">
                    {{ $kesepakatan->nama_cabang }}, {{ DateToIndo($kesepakatan->tanggal) }}
                </p>
                
                <table style="width: 100%; margin-top: 12px;">
                    <tr>
                        <td style="text-align: center; width: 33%; font-weight: bold;">Pihak Ke -1</td>
                        <td style="text-align: center; width: 33%; font-weight: bold;">Pihak Ke -2</td>
                        <td style="text-align: center; width: 33%; font-weight: bold;">Saksi</td>
                    </tr>
                    <tr>
                        <td style="height: 55px"></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">(___________)</td>
                        <td style="text-align: center;">(___________)</td>
                        <td style="text-align: center;">(___________)</td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-size: 11px;">Salesman</td>
                        <td style="text-align: center; font-size: 11px;">Pelanggan</td>
                        <td style="text-align: center; font-size: 11px;">SMM</td>
                    </tr>
                </table>

                <h3 style="margin-top: 20px; text-align: center; font-weight: bold;">Target Per Bulan</h3>
                <table class="datatable3">
                    <thead>
                        <tr>
                            <th>BULAN</th>
                            <th>TAHUN</th>
                            <th>TARGET</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_target = 0;
                        @endphp
                        @foreach ($detailtarget as $d)
                            @php
                                $target = $d->target_perbulan + $d->avg;
                                $total_target += $target;
                            @endphp
                            <tr>
                                <td>{{ getMonthName($d->bulan) }}</td>
                                <td>{{ $d->tahun }}</td>
                                <td style="text-align: right; padding-right: 12px;">{{ formatAngka($target) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" style="text-align: left; padding-left: 10px;">TOTAL</th>
                            <th style="text-align: right; padding-right: 12px;">{{ formatAngka($total_target) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
</body>

</html>

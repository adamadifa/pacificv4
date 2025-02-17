<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Kesepakatan </title>
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

        .table td {
            padding: 5px;
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

                    <td style="text-align: left">
                        <h3 style="font-family:'Cambria'; margin-bottom: 5px">{{ $kesepakatan->nama_pt }}</h3>
                        <span style="font-family:'Times New Roman'">{{ $kesepakatan->alamat_cabang }}</span><br>
                        <span style="font-size: 12px">{{ $kesepakatan->email }}</span><br>
                    </td>
                    <td></td>
                </tr>
            </table>

            <h3 style="text-align: center">
                SURAT KESEPAKATAN TARGET
                <br>
                PROGRAM {{ $kesepakatan->nama_program }}
            </h3>
            <p>
                Saya Yang Bertanda Tangan dibawah ini :
            <table class="table" style="width: 100%">
                <tr>
                    <td style="width: 40%">Nama Lengkap</td>
                    <td style="width: 1%">:</td>
                    <td style="border-bottom: 1px solid black">{{ $kesepakatan->nama_salesman }}</td>
                </tr>

                <tr>
                    <td style="width: 40%">Alamat Lengkap Tempat Tinggal</td>
                    <td style="width: 1%">:</td>
                    <td style="border-bottom: 1px solid black">{{ $kesepakatan->alamat_cabang }}</td>
                </tr>

                <tr>
                    <td style="width: 40%">NIK KTP</td>
                    <td style="width: 1%">:</td>
                    <td style="border-bottom: 1px solid black"></td>
                </tr>
            </table>
            <br>
            Yang Selanjutnya disebut sebagai Pikah ke -1 (<b>Perwakilan Perusahaan</b>)
            </p>
            <p>
            <table class="table" style="width: 100%">
                <tr>
                    <td style="width: 40%">Nama Lengkap</td>
                    <td style="width: 1%">:</td>
                    <td style="border-bottom: 1px solid black">{{ textUpperCase($kesepakatan->nama_pelanggan) }}</td>
                </tr>

                <tr>
                    <td style="width: 40%">Alamat Lengkap Tempat Tinggal</td>
                    <td style="width: 1%">:</td>
                    <td style="border-bottom: 1px solid black">{{ textCamelCase($kesepakatan->alamat_pelanggan) }}</td>
                </tr>

                <tr>
                    <td style="width: 40%">NIK KTP</td>
                    <td style="width: 1%">:</td>
                    <td style="border-bottom: 1px solid black">{{ $kesepakatan->nik }}</td>
                </tr>
                <tr>
                    <td style="width: 40%">No. HP / No. Telp Rumah</td>
                    <td style="width: 1%">:</td>
                    <td style="border-bottom: 1px solid black">{{ $kesepakatan->no_hp_pelanggan }}</td>
                </tr>
                <tr>
                    <td style="width: 40%">Alamat Toko</td>
                    <td style="width: 1%">:</td>
                    <td style="border-bottom: 1px solid black">{{ textCamelCase($kesepakatan->alamat_toko) }}</td>
                </tr>
                <tr>
                    <td style="width: 40%">Kode Pelanggan ( Diisi Pihak Ke - 1)</td>
                    <td style="width: 1%">:</td>
                    <td style="border-bottom: 1px solid black">{{ textupperCase($kesepakatan->kode_pelanggan) }}</td>
                </tr>
            </table>
            <br>
            Yang Selanjutnya disebut sebagai Pikah ke -2 (<b>Pembeli</b>)
            </p>
            <p>
                Adapun hak dan kewajiban antara Pihak Ke-1 dan Pihak Ke-2 yang harus disepakati bersama dalam surat kesepakatan ini antara lain :
            <ol>
                <li>Pihak Ke-2 Memilih target penjualan sebanyak {{ formatAngka($kesepakatan->qty_target) }} {{ $kesepakatan->nama_program }}
                    dengan rincian target per bulan terlampir</li>
                <li>Dengan hadiah berupa Cashback sebesar Rp.{{ $kesepakatan->reward }}
                    {{ $kesepakatan->tipe_reward == '2' ? 'Flat' : '/Dus/Ball' }}</li>
                <li>Periode Program dimulai dari {{ $namabulan[date('m', strtotime($kesepakatan->periode_dari)) * 1] }} s/d
                    {{ $namabulan[date('m', strtotime($kesepakatan->periode_sampai)) * 1] }}
                    {{ date('Y', strtotime($kesepakatan->periode_sampai)) }}
                </li>
                <li>
                    Pihak Ke2 bersedia mengambil barang dengan kuantitas yang stabil sesuai dengan ksesepakatan target bulanan (diperbolehkan lebih
                    tetapi tidak boleh <b>KURANG</b>)
                </li>
                <li>
                    Pihak Ke-2 bersedia melampirkan fotocopy KTP
                </li>
                <li>
                    Pihak Ke-1 dan Pihak Ke-2 bersedia melengkapi seluruh data yang ada pada surat kesepakatan ini
                </li>
                <li>
                    Sebagai upaya mempercepat proses realisasi hadiah untuk pelanggan perusahan, Pihak Ke-2 bersedia <b>Melunasi seluruh faktur yang
                        ada</b> (Paling lambat {{ $kesepakatan->top }} hari dari berakhirnya masa program)
                </li>
                <li>
                    Pengembalian produk oleh Pihak Ke-2 tidak dapat dilakukan dengan cara potong faktur maupun diluangkan. Pengembalian produk hanya
                    dapat dilakukan dengan cara tukar barang dengan produk sejenis.
                </li>
                <li>
                    Pihak Ke-1 tidak menerima pengembalian barang yang diakibatkan expired masa produk.
                </li>
                <li>
                    Apabila dalam realisasinya pihak Ke-2 mampu melebihi target yang telah disepakati maka Pihak Ke-2 berhak mendapatkan hadiah
                    yang lebih besar sesuai grade yang tercapai
                </li>
                <li>
                    Pihak Ke-2 tidak diperkenankan turun target dari target yang telah disepakati
                </li>
            </ol>
            </p>
            <p>
                Demikian surat kesepakatan ini dibuat atas dasar kesepakatan target program yang ditawarkan oleh {{ $kesepakatan->nama_pt }}. Apabila
                terdapat kewajiban yang tidak terlaksana dari poin yang telah disebutkan diatas maka surat kesepakatan ini dapat dinyatakan gugur.
            </p>
            <p>
                <br>
                {{ $kesepakatan->nama_cabang }}, {{ DateToIndo($kesepakatan->tanggal) }}
                <br>
                <br>
            <table style="width: 100%">
                <tr>
                    <td style="text-align: center;">Pihak Ke -1</td>
                    <td style="text-align: center;">Pihak Ke -2</td>
                    <td style="text-align: center;" colspan="2" style="text-align: center">Saksi</td>
                    {{-- <td style="text-align: center;">Saksi 2</td> --}}
                </tr>
                <tr>
                    <td style="height: 70px"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: center;">(___________)</td>
                    <td style="text-align: center;">(___________)</td>
                    <td style="text-align: center;">(___________)</td>
                    <td style="text-align: center;">(___________)</td>
                </tr>
                <tr>
                    <td style="text-align: center;">Salesman</td>
                    <td style="text-align: center;">Pelanggan</td>
                    <td style="text-align: center;">SMM</td>
                    <td style="text-align: center;">OM</td>
                </tr>
            </table>
            </p>
        </section>
        <section class="sheet padding-10mm">
            <h2>Lampiran Target Per Bulan</h2>
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th>Target</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_target = 0;
                    @endphp
                    @foreach ($detailtarget as $d)
                        @php
                            $total_target += $d->target_perbulan;
                        @endphp
                        <tr>
                            <td>{{ getMonthName($d->bulan) }}</td>
                            <td>{{ $d->tahun }}</td>
                            <td style="text-align: right">{{ formatAngka($d->target_perbulan) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">TOTAL</th>
                        <th style="text-align: right">{{ formatAngka($total_target) }}</th>
                    </tr>
                </tfoot>
            </table>
        </section>

    </body>

</html>

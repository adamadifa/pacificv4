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
                SURAT PEMBERITAHUAN PROGRAM DISCOUNT KUMULATIF PEMBELIAN
                <br>
                {{-- PROGRAM {{ $kesepakatan->nama_program }} --}}
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
                <li>Pihak ke-2 berhak mendapatkan tambahan cashback sesuai denagn selisih diskon pada saat transaksi reguler </li>
                <li>Nilai cashback dihitung berdasarkan jumlah kumulatif transaksi dalam satu bulan kalender dikalikan dengan selisih diskon saat
                    transaksi reguler</li>
                <li>Pencapaian cashback dengan nominal kurang dari Rp 100.000,- perolehan berupa voucher
                </li>
                <li>Pencapaian cashback dengan nominal lebih dari Rp 100.000,- perolehan berupa uang tunai ( Cash/Transfer)</li>
                <li>
                    Sebagai upaya mempercepat proses realisasi hadiah untuk pelanggan perusahan, Pihak Ke-2 bersedia Melunasi seluruh faktur yang
                    ada (14 hari dari tgl faktur)
                </li>
                <li>ika pihak ke-2 melakukan pelunasan melebihi dari 14 hari, maka pihak ke-2 tidak berhak mendapatkan cashback tambahan</li>
                <li>
                    Pihak Ke-1 dan Pihak Ke-2 bersedia melengkapi seluruh data yang ada pada surat kesepakatan ini
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
                {{-- <tr>
                    <td style="text-align: center;">Pihak Ke -1</td>
                    <td style="text-align: center;">Pihak Ke -2</td>
                    <td style="text-align: center;">Saksi</td>
                    <td style="text-align: center;"></td>
                </tr> --}}
                <tr>
                    <td colspan="4" style="text-align: center">Mengetahui,</td>
                </tr>
                <tr>
                    <td style="height: 70px"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    {{-- <td style="text-align: center;">(___________)</td>
                    <td style="text-align: center;">(___________)</td> --}}
                    <td style="text-align: center;">(___________)</td>
                    <td style="text-align: center;">(___________)</td>
                </tr>
                <tr>
                    <td style="text-align: center;">Pelanggan</td>
                    <td style="text-align: center;">Salesman</td>
                    {{-- <td style="text-align: center;">RSM/GM</td>
                    <td style="text-align: center;">Salesman</td> --}}
                </tr>
            </table>
            </p>
        </section>
        <style>
            .tabeldiskon {
                width: 100%;
                border-collapse: collapse;
                text-align: center;
            }

            .tabeldiskon th,
            .tabeldiskon td {
                border: 1px solid black;
                padding: 8px;
            }

            .tabeldiskon th {
                background-color: #f2f2f2;
            }
        </style>
        {{-- <section class="sheet padding-10mm">
            <table class="tabeldiskon">
                <thead>
                    <tr>
                        <th colspan="6">BB + DP ALL CABANG</th>
                    </tr>
                    <tr>
                        <th rowspan="2">No</th>
                        <th colspan="2">Qty</th>
                        <th rowspan="2">Reguler</th>
                        <th colspan="2">Diskon</th>
                    </tr>
                    <tr>
                        <th>Dari</th>
                        <th>Sampai</th>
                        <th>Cash</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>5</td>
                        <td>29</td>
                        <td>1.000</td>
                        <td>500</td>
                        <td>1.500</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>30</td>
                        <td>49</td>
                        <td>1.250</td>
                        <td>500</td>
                        <td>1.750</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>50</td>
                        <td>99</td>
                        <td>1.500</td>
                        <td>500</td>
                        <td>2.000</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>100</td>
                        <td>299</td>
                        <td>1.750</td>
                        <td>1.000</td>
                        <td>2.750</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>300</td>
                        <td>499</td>
                        <td>2.000</td>
                        <td>1.000</td>
                        <td>3.000</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>500</td>
                        <td>&gt;</td>
                        <td>2.250</td>
                        <td>1.000</td>
                        <td>3.250</td>
                    </tr>
                </tbody>
            </table>
            <table class="tabeldiskon">
                <thead>
                    <tr>
                        <th colspan="6">AR + AS + AB</th>
                    </tr>
                    <tr>
                        <th rowspan="2">No</th>
                        <th colspan="2">Qty</th>
                        <th rowspan="2">Reguler</th>
                        <th colspan="2">Diskon</th>
                    </tr>
                    <tr>
                        <th>Dari</th>
                        <th>Sampai</th>
                        <th>Cash</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>10</td>
                        <td>29</td>
                        <td>2.500</td>
                        <td>1.000</td>
                        <td>3.500</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>30</td>
                        <td>49</td>
                        <td>5.000</td>
                        <td>1.000</td>
                        <td>6.000</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>50</td>
                        <td>99</td>
                        <td>7.500</td>
                        <td>1.000</td>
                        <td>8.500</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>100</td>
                        <td>299</td>
                        <td>10.000</td>
                        <td>1.000</td>
                        <td>11.000</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>300</td>
                        <td>499</td>
                        <td>12.500</td>
                        <td>1.000</td>
                        <td>13.500</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>500</td>
                        <td>&gt;</td>
                        <td>15.000</td>
                        <td>1.000</td>
                        <td>16.000</td>
                    </tr>
                </tbody>
            </table>
        </section> --}}
    </body>

</html>

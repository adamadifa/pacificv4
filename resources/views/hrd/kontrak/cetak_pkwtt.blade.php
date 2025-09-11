<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Kontrak {{ $kontrak->no_kontrak }} {{ $kontrak->nama_karyawan }} </title>
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
            @if ($kontrak->kode_cabang == 'PST')
                <table style="width: 100%">
                    <tr>
                        <td style="width: 20%; text-align:center">
                            <img src="{{ asset('assets/img/logo/mp.png') }}" alt=""
                                style="width: 80px; height:80px">
                        </td>
                        <td style="text-align: left">
                            <h3 style="font-family:'Cambria'; line-height:0px ">CV MAKMUR PERMATA</h3>
                            <span style="font-size: 1.2rem"><i>Factory / Head Office</i></span><br>
                            <span style="font-family:'Times New Roman'">Jl. Perintis Kemerdekaan No. 160
                                Tasikmalaya</span><br>
                            <span style="font-size: 12px">Telp (0265) 336794 Fax (0265) 332329</span><br>
                            <span style="font-size: 12px">e-mail : pacific.tasikmalaya@gmail.com</span>
                        </td>
                        <td>

                        </td>
                    </tr>
                </table>
            @else
                <table style="width: 100%">
                    <tr>

                        <td style="text-align: left">
                            <h3 style="font-family:'Cambria'; line-height:0px">{{ $kontrak->nama_pt }}</h3>
                            <span style="font-size: 1.2rem"><i>Factory / Head Office</i></span><br>
                            <span style="font-family:'Times New Roman'">{{ $kontrak->alamat_cabang }}</span><br>
                            <span style="font-size: 12px">{{ $kontrak->email }}</span><br>
                        </td>
                        <td>

                        </td>
                    </tr>
                </table>
            @endif
            <hr>
            <h3 style="text-align: center">
                <u>PERJANJIAN KERJA</u>
                <br>
                WAKTU TIDAK TENTU
            </h3>
            <table>
                <tr>
                    <td style="width: 120px">Nama</td>
                    <td>:</td>
                    <td>{{ $pihak_satu['nama_pihak_satu'] }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ $pihak_satu['jabatan_pihak_satu'] }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>
                        {{ $kontrak->alamat_cabang }}
                    </td>
                </tr>
            </table>
            <p>
                Bertindak untuk dan atas nama <b>{{ $kontrak->nama_pt }}</b>
                berkedudukan di {{ $kontrak->kode_cabang != 'PST' ? $kontrak->nama_cabang : 'Tasikmalaya' }}
                selanjutnya disebut <b>PIHAK SATU.</b>
            </p>
            <p>
            <table>
                <tr>
                    <td style="width: 120px">Nama</td>
                    <td>:</td>
                    <td>{{ ucwords(strtolower($kontrak->nama_karyawan)) }}</td>
                </tr>
                <tr>
                    <td>Tempat, Tgl Lahir</td>
                    <td>:</td>
                    <td>{{ $kontrak->tempat_lahir }},
                        {{ !empty($kontrak->tanggal_lahir) ? DateToIndo($kontrak->tanggal_lahir) : '' }}
                    </td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $kontrak->alamat_karyawan }}</td>
                </tr>
                <tr>
                    <td>No. Identitas</td>
                    <td>:</td>
                    <td>{{ $kontrak->no_ktp }}</td>
                </tr>
            </table>
            </p>
            <p>
                Bertindak atas nama diri sendiri selanjutnya disebut <b>PIHAK KEDUA.</b><br>
            </p>
            <p>
                <b>PIHAK SATU</b> dan <b>PIHAK DUA</b> telah mengadakan kesepakatan Perjanjian Kerja Waktu Tertentu
                dengan ketentuan
                sebagai berikut:
            </p>
            <h4 style="text-align: center">
                PASAL 1<br>
                UMUM
            </h4>


            <ol>
                <li>
                    Pihak kesatu setuju menerima pihak kedua sebagai <b>KARYAWAN TETAP</b>
                    {{ $kontrak->kode_cabang == 'PST' ? 'CV. Makmur Permata' : $kontrak->nama_cabang }}
                </li>
                <li>
                    Pihak kedua ditempatkan sebagai <b>{{ $kontrak->nama_jabatan }}
                        {{ $kontrak->kode_cabang == 'PST' ? 'TASIKMALAYA' : strtoupper($kontrak->nama_cabang) }}</b>
                    dan bersedia ditempatkan diluar departemen tersebut bila Perusahaan memerlukan
                </li>
                <li>
                    @if ($kontrak->kategori_jabatan == 'MJ')
                        Pihak kedua setuju menerima upah dengan rincian terlampir:
                    @else
                        @php
                            $totalupah =
                                $gaji->gaji_pokok +
                                $gaji->t_jabatan +
                                $gaji->t_tanggungjawab +
                                $gaji->t_makan +
                                $gaji->t_skill;
                        @endphp
                        Pihak kedua setuju menerima upah sebesar Rp {{ formatRupiah($totalupah) }} ,- dengan rincian
                        sebagai
                        berikut :
                        <table>
                            <tr>
                                <td>a.</td>
                                <td style="width:140px">Gaji Pokok</td>
                                <td>:</td>
                                <td>Rp. {{ formatRupiah($gaji->gaji_pokok) }},-</td>
                            </tr>

                            <tr>
                                <td>b.</td>
                                <td style="width:140px">Tj. Jabatan</td>
                                <td>:</td>
                                <td>Rp. {{ formatRupiah($gaji->t_jabatan) }},-</td>
                            </tr>
                            <tr>
                                <td>c.</td>
                                <td style="width:140px">Tj. Tanggungjawab</td>
                                <td>:</td>
                                <td>Rp. {{ formatRupiah($gaji->t_tanggungjawab) }},-</td>
                            </tr>
                            <tr>
                                <td>d.</td>
                                <td style="width:140px">Tj. Makan</td>
                                <td>:</td>
                                <td>Rp. {{ formatRupiah($gaji->t_makan) }},-</td>
                            </tr>

                            <tr>
                                <td>e.</td>
                                <td style="width:140px">Skill Khusus</td>
                                <td>:</td>
                                <td>Rp. {{ formatRupiah($gaji->t_skill) }},-</td>
                            </tr>
                        </table>
                    @endif
                </li>
            </ol>
            </p>
            <h4 style="text-align: center">
                PASAL 2<br>
                WAKTU KERJA
            </h4>
            <ol>
                <li>
                    Jam kerja adalah 8 jam sehari (termasuk istirahat 1 jam) atau 40 Jam seminggu Senin s/d Jumat 07.00
                    –
                    15.00 WIB dan Sabtu Jam 07.00 – 12.00 WIB. Atau sesuai jadwal kerja yang disepakati bersama.
                </li>
                <li>
                    Untuk cabang, hari dan jam kerja akan dilaksanakan dengan ketentuan yang telah disepakati oleh
                    masing-masing cabang.
                </li>
            </ol>
            </p>
            <p>
            <h4 style="text-align: center">
                PASAL 3<br>
                TATA TERTIB DAN DISIPLIN KERJA
            </h4>
            <ol>

                <li>
                    Tata tertib dan disiplin kerja berlaku ketentuan Peraturan Perusahaan yang tercantum dalam PKB
                    (Perjanjian Kerja Bersama)
                </li>
                <li>
                    Pelanggaran tata tertib PKB (Perjanjian Kerja Bersama) oleh pihak kedua dapat diberikan peringatan
                    baik
                    lisan maupun tulisan dan bila terpaksa berlaku scorsing sampai pemutusan hubungan kerja dengan
                    landasan
                    hukum yang dipergunakan oleh pihak kesatu adalah PKB (Perjanjian Kerja Bersama) dan peraturan
                    ketenagakerjaan yang berlaku.
                </li>
                <li>
                    Dalam hal pekerja yang mendapatkan kesempatan promosi jabatan atas hasil seleksi yang ditempuh, maka
                    pekerja wajib menandatangani surat pernyataan bersedia mengabdi sekurang-kurangnya 2 tahun untuk
                    promosi
                    ke level supervisor dan 3 tahun untuk promosi ke level manajemen pusat/cabang.
                </li>
                <li>
                    Izin tidak masuk kerja terlebih dahulu meminta izin tertulis kepada pimpinan.
                </li>
                <li>
                    Pihak kesatu berhak memindahkan / menempatkan pihak kedua dari pekerjaan yang dianggap perlu oleh
                    pihak
                    kesatu dan pihak kedua wajib mematuhi dan melaksanakannya dengan penuh tanggung jawab.
                </li>
            </ol>
            </p>
        </section>
        <section class="sheet padding-10mm">
            <h4 style="text-align: center">
                PASAL 4<br>
                KETENTUAN SANKSI
            </h4>
            <ol>
                <li>Pihak kedua wajib bertanggungjawab terhadap tugas yang diberikan oleh pimpinan.</li>
                <li>Pihak kedua wajib mengganti kerugian apabila pihak kedua merusak barang atau peralatan lainnya baik
                    disengaja ataupun tidak disengaja milik perusahaan sehingga menyebabkan kerugian bagi perusahaan.
                </li>
                <li>Pihak kedua akan dituntut secara hukum apabila pihak kedua melakukan pencurian milik perusahaan baik
                    dilakukan secara individu atau bekerjasama dengan pihak lain atau pihak ketiga.</li>
                <li>Pihak kedaua akan di scorsing sesuai dengan peraturan perusahaan yang berlaku, yaitu PKB (Perjanjian
                    Kerja Bersama) apabila pihak kedua mangkir dari tugas dan tanggungjawabnya.</li>
            </ol>
            </p>
            <p>
            <h4 style="text-align: center">
                PASAL 5<br>
                JAMINAN SOSIAL
            </h4>
            <ol>
                <li>Seragam diatur di Peraturan Perusahaan.</li>
                <li>Cuti diberikan setelah masa kerja satu tahun dan pengambilan cutinya jatuh pada bulan ketiga belas.
                </li>
                <li>Cuti dalam kasus meninggalnya istri, ayah/ibu kandung, dan anak kandung diberikan cuti selama dua
                    hari
                    berturut turut.</li>

            </ol>
            </p>
            <p>
            <h4 style="text-align: center">
                PASAL 6<br>
                MASA KERJA
            </h4>
            <ol>
                <li>Perjanjian Kerja Waktu Tidak Tertentu ini mulai berlaku pada saat ditandatangani oleh kedua belah
                    pihak.
                </li>
                <li>Perjanjian Kerja Waktu Tidak Tertentu ini akan berakhir apabila :
                    <ol type="a">
                        <li>Diputuskan oleh pihak kedua.</li>
                        <li>Diputuskan oleh pihak kesatu berdasarkan peraturan ketenagakerjaan yang berlaku.</li>
                        <li>Meninggal dunia.</li>

                    </ol>
                </li>

                <li>Untuk hal-hal yang belum tercantum dalam syarat-syarat kerja ini berlaku ketentuan-ketentuan umum
                    pada
                    PKB (Perjanjian Kerja Bersama).</li>
                <li>Apabila dikemudian hari terdapat kekeliruan pada surat perjanjian kerja bersama ini maka akan
                    ditinjau
                    kembali dan diperbaiki sebagaimana mestinya.</li>
            </ol>
            </p>
            <p>
            <h4 style="text-align: center">
                PASAL 7<br>
                PENUTUP
            </h4>
            Demikian perjanjian kerja bersama waktu tertentu ini dibuat dan ditandatangani oleh kedua belah pihak dalam
            keadaan sehat walafiat, sadar, mengerti tanpa ada paksaan dari siapapun atau pihak manapun.
            </p>
            <p style="margin-top:4rem !important">
            <table style="width: 100%;">
                <tr>
                    @if ($kontrak->kode_cabang != 'PST' && $kontrak->kategori_jabatan != 'MJ')
                        <td colspan="3" style="text-align: center; padding:1rem">
                            {{ textCamelCase($kontrak->nama_cabang) }},
                            {{ DateToIndo($kontrak->tanggal) }}</td>
                    @else
                        <td colspan="2" style="text-align: center; padding:1rem">
                            {{ $kontrak->kode_cabang != 'PST' ? textCamelCase($kontrak->nama_cabang) : 'Tasikmalaya' }},
                            {{ DateToIndo($kontrak->tanggal) }}</td>
                    @endif
                </tr>
                <tr>
                    <td style="text-align: center">PIHAK DUA</td>
                    @if ($kontrak->kode_cabang != 'PST' && $kontrak->kategori_jabatan != 'MJ')
                        <td colspan="2" style="text-align: center">PIHAK PERTAMA</td>
                    @else
                        <td style="text-align: center">PIHAK PERTAMA</td>
                    @endif
                </tr>
                <tr>
                    <td style="text-align: center; height:5rem"></td>
                    @if ($kontrak->kode_cabang != 'PST' && $kontrak->kategori_jabatan != 'MJ')
                        <td colspan="2" style="text-align: center"></td>
                    @else
                        <td style="text-align: center"></td>
                    @endif
                </tr>
                <tr>
                    <td style="text-align: center"><u>{{ $kontrak->nama_karyawan }}</u><br>Pekerja</td>
                    @if ($kontrak->kode_cabang != 'PST' && $kontrak->kategori_jabatan != 'MJ')
                        @if ($kontrak->kode_perusahaan == 'MP')
                            <td style="text-align: center">
                                <u>{{ pihakpertamacabang($kontrak->kode_cabang, 'MP') }}</u><br>Operation
                                Manager
                            </td>
                        @else
                            <td style="text-align: center">
                                <u>{{ pihakpertamacabang($kontrak->kode_cabang, 'PC') }}</u><br>
                                Sales Marketing Manager
                            </td>
                        @endif
                    @endif
                    <td style="text-align: center">
                        <u>{{ $pihak_satu['nama_pihak_satu'] }}</u><br>{{ $pihak_satu['jabatan_pihak_satu'] }}
                    </td>
                </tr>
            </table>
            </p>
        </section>
    </body>

</html>

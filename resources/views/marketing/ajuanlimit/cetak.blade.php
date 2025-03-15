<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Formulir Pendaftaran </title>
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
            <table style="width:100%" class="datatable3">
                <tr>

                    <td>
                        <b style="font-size:18px">{{ $ajuanlimit->nama_pt }}</b><br>
                        <div style="font-size:14px; font-family:Tahoma">
                            {{ $ajuanlimit->alamat_cabang }}
                        </div>
                        <br>
                    </td>
                    <td class="text-center">
                        @if (!empty($ajuanlimit->foto))
                            @if (Storage::disk('public')->exists('/pelanggan/' . $ajuanlimit->foto))
                                <img src="{{ getfotoPelanggan($ajuanlimit->foto) }}" alt="user image"
                                    class="d-block h-auto mx-auto rounded user-profile-img" width="150">
                            @else
                                <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="user image"
                                    class="d-block h-auto mx-auto rounded user-profile-img" width="150">
                            @endif
                        @else
                            <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="user image"
                                class="d-block h-auto mx-auto rounded user-profile-img" width="150">
                        @endif
                    </td>
                    <td class="text-center">
                        @if (!empty($ajuanlimit->foto_owner))
                            @if (Storage::disk('public')->exists('/pelanggan/owner/' . $ajuanlimit->foto_owner))
                                <img src="{{ getfotoPelangganowner($ajuanlimit->foto_owner) }}" alt="user image"
                                    class="d-block h-auto mx-auto rounded user-profile-img" width="150">
                            @else
                                <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="user image"
                                    class="d-block h-auto mx-auto rounded user-profile-img" width="150">
                            @endif
                        @else
                            <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="user image"
                                class="d-block h-auto mx-auto rounded user-profile-img" width="150">
                        @endif
                    </td>
                </tr>
            </table>
            <h3 class="judul2">ANALISA AJUAN KREDIT</h3>
            <h4 class="judul2"><u>KUALITATIF</u></h4>
            <div style="display: flex; justify-content:space-between">
                <div style="width: 400px;">
                    <table class="datatable3" style="width: 100%">
                        <tr>
                            <td>No.Pengajuan</td>
                            <td>{{ $ajuanlimit->no_pengajuan }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>{{ DateToIndo($ajuanlimit->tanggal) }}</td>
                        </tr>
                        <tr>
                            <td>Cabang</td>
                            <td>{{ $ajuanlimit->nama_cabang }}</td>
                        </tr>
                        <tr>
                            <td>Salesman</td>
                            <td>{{ $ajuanlimit->nama_salesman }}</td>
                        </tr>
                        <tr>
                            <td>Alamat KTP</td>
                            <td>{{ ucwords(strtolower($ajuanlimit->alamat_pelanggan)) }}</td>
                        </tr>
                    </table>
                </div>
                <div style="width: 300px;">
                    <table class="datatable3" style="width: 100%">
                        <tr>
                            <td>ID Pelanggan</td>
                            <td>{{ $ajuanlimit->kode_pelanggan }}</td>
                        </tr>
                        <tr>
                            <td>Pelanggan</td>
                            <td>{{ $ajuanlimit->nama_pelanggan }}</td>
                        </tr>
                        <tr>
                            <td>Alamat Toko</td>
                            <td>{{ $ajuanlimit->alamat_toko }}</td>
                        </tr>
                        <tr>
                            <td>Koordinat</td>
                            <td>{{ $ajuanlimit->latitude }},{{ $ajuanlimit->longitude }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <h4 class="judul2"><u>KUANTITATIF</u></h4>
            <div style="display: flex; justify-content: space-between">
                <div style=" width:400px">
                    <table class="datatable3" style="width: 100%">
                        <tr>
                            <td>Status Outlet</td>
                            <td>{{ !empty($ajuanlimit->status_outlet) ? $status_outlet[$ajuanlimit->status_outlet] : '' }}</td>
                        </tr>
                        <tr>
                            <td>Cara Pembayaran</td>
                            <td>{{ !empty($ajuanlimit->cara_pembayaran) ? $cara_pembayaran[$ajuanlimit->cara_pembayaran] : '' }}</td>
                        </tr>
                        <tr>
                            <td>Histori Pembayaran Transaksi (6 Bulan Terakhir)</td>
                            <td>{{ !empty($ajuanlimit->histori_transaksi) ? $histori_transaksi[$ajuanlimit->histori_transaksi] : '' }}</td>
                        </tr>
                        <tr>
                            <td>Terakhir Top UP</td>
                            <td>
                                {{ !empty($ajuanlimit->topup_terakhir) ? DateToIndo($ajuanlimit->topup_terakhir) : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Lama Usaha</td>
                            <td>{{ !empty($ajuanlimit->lama_berjualan) ? $lama_berjualan[$ajuanlimit->lama_berjualan] : '' }}</td>
                        </tr>
                        <tr>
                            <td>Jumlah Faktur</td>
                            <td>{{ $ajuanlimit->jml_faktur }}</td>
                        </tr>

                    </table>
                </div>
                <div style=" width:300px">
                    <table class="datatable3" style="width: 100%">
                        <tr>
                            <td>TOP</td>
                            <td>{{ $ajuanlimit->ljt }} Hari</td>
                        </tr>
                        <tr>
                            <td>Tempat Usaha</td>
                            <td>{{ !empty($ajuanlimit->kepemilikan) ? $kepemilikan[$ajuanlimit->kepemilikan] : '' }}</td>
                        </tr>
                        <tr>
                            <td>Omset Toko</td>
                            <td style="text-align: right">{{ formatRupiah($ajuanlimit->omset_sebelumnya) }}</td>
                        </tr>
                        <tr>
                            <td>Lama Langganan</td>
                            <td>{{ !empty($ajuanlimit->lama_langganan) ? $lama_langganan[$ajuanlimit->lama_langganan] : '' }}</td>
                        </tr>
                        <tr>
                            <td>Type Outlet</td>
                            <td>
                                {{ !empty($ajuanlimit->type_outlet) ? $type_outlet[$ajuanlimit->type_outlet] : '' }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div style="margin-top:30px; display:flex; justify-content: space-between">
                <table class="datatable3">
                    <tr>
                        <td>Limit Kredit Sebelumnya</td>
                        <td style="text-align: right">{{ formatRupiah($ajuanlimit->limit_sebelumnya) }}</td>
                    </tr>
                    <tr>
                        <td>Pengajuan Tambahan</td>
                        <td style="text-align: right">{{ formatRupiah($ajuanlimit->jumlah - $ajuanlimit->limit_sebelumnya) }}</td>
                    </tr>
                </table>
                <table class="datatable3">
                    <tr>
                        <td>Referensi</td>
                        <td>
                            @php
                                $referensi = explode(',', $ajuanlimit->referensi);
                            @endphp
                            @if (empty($referensi))
                                <b>Tidak Ada Referensi</b>
                            @else
                                <ul style="list-style: none; display:flex; justify-content: space-between">
                                    @foreach ($referensi as $item)
                                        <li style="margin-right:10px"><i class="ti ti-check me-1"></i> &#9745; {{ textUpperCase($item) }}
                                            {{ $item == 'external' ? '(' . $ajuanlimit->ket_referensi . ')' : '' }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <table class="datatable3" style="margin-top:30px; width:100%">
                <tr>
                    <td>Total Limit</td>
                    <td style="text-align: right">{{ formatRupiah($ajuanlimit->jumlah) }}</td>
                    <td rowspan="4" valign="top">
                        @foreach ($disposisi as $index => $d)
                            @php
                                $next_role = @$disposisi[$index + 1]->role;
                            @endphp
                            @if ($d->role == $next_role)
                                @php
                                    continue;
                                @endphp
                            @endif
                            <b>{{ $d->username }} ( ({{ textCamelCase($d->role) }}))</b>
                            {{ $d->uraian_analisa }}<br>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td>Level Otorisasi</td>
                    <td>
                        @if ($ajuanlimit->jumlah > 15000000)
                            Direktur
                        @elseif($ajuanlimit->jumlah > 10000000)
                            General Manager
                        @elseif($ajuanlimit->jumlah > 5000000)
                            Regional Sales Manager
                        @elseif($ajuanlimit->jumlah > 2000000)
                            Sales Marketing Manager
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Total Skor</td>
                    <td style="text-align: right">{{ formatAngkaDesimal($ajuanlimit->skor) }}</td>
                </tr>
                <tr>
                    <td>Rekomendasi</td>
                    <td>
                        <?php
                        $scoreakhir = $ajuanlimit->skor;
                        if ($scoreakhir <= 2) {
                            $rekomendasi = 'Tidak Layak';
                        } elseif ($scoreakhir > 2 && $scoreakhir <= 4) {
                            $rekomendasi = 'Tidak Disarankan';
                        } elseif ($scoreakhir > 4 && $scoreakhir <= 6.75) {
                            $rekomendasi = 'Beresiko';
                        } elseif ($scoreakhir > 6.75 && $scoreakhir <= 8.5) {
                            $rekomendasi = 'Layak Dengan Pertimbangan';
                        } elseif ($scoreakhir > 8.5 && $scoreakhir <= 10) {
                            $rekomendasi = 'Layak';
                        }
                        echo $rekomendasi;
                        ?>
                    </td>
                </tr>
            </table>
            <table class="datatable3" style="width:100%; margin-top: 30px">
                <tr>
                    <td colspan="2" align="center" style="width: 100px;">Diajukan Oleh</td>
                    <td colspan="2" align="center" style="width: 100px;">Disetujui Cabang</td>
                    <td colspan="3" align="center" style="width: 200px;">Disetujui Pusat</td>
                </tr>
                <tr>
                    <td style="height: 100px; width:90px"></td>
                    <td style="height: 100px; width:90px"></td>
                    <td style="height: 100px; width:90px"></td>
                    <td style="height: 100px; width:90px"></td>
                    <td style="height: 100px; width:90px"></td>
                    <td style="height: 100px; width:90px"></td>
                    <td style="height: 100px; width:90px"></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr style="text-align: center;">
                    <td>Salesman</td>
                    <td>Driver</td>
                    <td>SMM</td>
                    <td>OM</td>
                    <td>RSM</td>
                    <td>GM</td>
                    <td>Direktur</td>
                </tr>
            </table>
        </section>
    </body>

</html>

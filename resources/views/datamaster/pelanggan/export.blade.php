<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Pelanggan {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4>DATA PELANGGAN</h4>
        <h4>{{ $cabang != null ? textUpperCase($cabang->nama_pt) . '(' . textUpperCase($cabang->nama_cabang) . ')' : '' }}</h4>
        {{-- <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4> --}}
    </div>
    <div class="body">
        <table class="datatable3" border="1" style="width: 300%;">
            <thead>
                <tr>
                    <th>Kode Pelanggan</th>
                    <th>Tanggal Register</th>
                    <th>NIK</th>
                    <th>No. KK</th>
                    <th>Nama Pelanggan</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat Pelanggan</th>
                    <th>Alamat Toko</th>
                    <th>No. HP Pelanggan</th>
                    <th>Kode Wilayah</th>
                    <th>Hari</th>
                    <th>Koordinat</th>
                    <th>LJT</th>
                    <th>Status Outlet</th>
                    <th>Type Outlet</th>
                    <th>Cara Pembayaran</th>
                    <th>Kepemilikan</th>
                    <th>Lama Berjualan</th>
                    <th>Jaminan</th>
                    <th>Omset Toko</th>
                    <th>Foto</th>
                    <th>Limit Pelanggan</th>
                    <th>Kode Salesman</th>
                    <th>Kode Cabang</th>
                    <th>Status</th>
                    <th>Klasifikasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pelanggan as $d)
                    @php
                        $color_status = $d->status_aktif_pelanggan == 0 ? 'red' : '';
                    @endphp
                    <tr style="background-color: {{ $color_status }};">
                        <td>{{ $d->kode_pelanggan }}</td>
                        <td>{{ $d->tanggal_register }}</td>
                        <td>{{ $d->nik }}</td>
                        <td>{{ $d->no_kk }}</td>
                        <td>{{ $d->nama_pelanggan }}</td>
                        <td>{{ !empty($d->tanggal_lahir) ? formatIndo($d->tanggal_lahir) : '' }}</td>
                        <td>{{ $d->alamat_pelanggan }}</td>
                        <td>{{ $d->alamat_toko }}</td>
                        <td>{{ !empty($d->no_hp_pelanggan) ? $d->no_hp_pelanggan : '-' }}</td>
                        <td>{{ $d->nama_wilayah }}</td>
                        <td>{{ $d->hari }}</td>
                        <td>{{ $d->latitude }} , {{ $d->longitude }}</td>
                        <td>{{ $d->ljt }}</td>
                        <td>{{ !empty($d->status_outlet) ? $status_outlet[$d->status_outlet] : '' }}</td>
                        <td>{{ !empty($d->type_outlet) ? $type_outlet[$d->type_outlet] : '' }}</td>
                        <td>{{ !empty($d->cara_pembayaran) ? $cara_pembayaran[$d->cara_pembayaran] : '' }}</td>
                        <td>{{ !empty($d->kepemilikan) ? $kepemilikan[$d->kepemilikan] : '' }}</td>
                        <td>{{ !empty($d->lama_langganan) ? $lama_langganan[$d->lama_langganan] : '' }}</td>
                        <td>{{ $d->jaminan == 1 ? 'Ada' : 'Tidak' }}</td>
                        <td class="right">{{ formatAngka($d->omset_toko) }}</td>
                        <td class="center">{{ !empty($d->foto) ? '✓' : '✗' }}</td>
                        <td class="right">{{ formatAngka($d->limit_pelanggan) }}</td>
                        <td>{{ $d->nama_salesman }}</td>
                        <td>{{ $d->nama_cabang }}</td>
                        <td>{{ $d->status_aktif_pelanggan == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                        <td>{{ $d->klasifikasi }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>

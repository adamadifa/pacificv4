<form action="#">
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <table class="table">
                <tr>
                    <th style="width:40%">No. Pengajuan</th>
                    <td>{{ $ajuanlimit->no_pengajuan }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($ajuanlimit->tanggal) }}</td>
                </tr>
            </table>

            <table class="table">
                <tr>
                    <th style="width: 40%">Kode Pelanggan</th>
                    <td>{{ $ajuanlimit->kode_pelanggan }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td>{{ $ajuanlimit->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Pelanggan</th>
                    <td>{{ $ajuanlimit->nama_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $ajuanlimit->alamat_pelanggan }}</td>
                </tr>
                <tr>
                    <th>No. HP</th>
                    <td>{{ $ajuanlimit->no_hp_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td>{{ textUpperCase($ajuanlimit->nama_cabang) }}</td>
                </tr>
                <tr>
                    <th>Salesman</th>
                    <td>{{ textUpperCase($ajuanlimit->nama_salesman) }}</td>
                </tr>
                <tr>
                    <th>Routing</th>
                    <td>{{ $ajuanlimit->hari }}</td>
                </tr>
                <tr>
                    <th>Lokasi</th>
                    <td>{{ $ajuanlimit->latitude }},{{ $ajuanlimit->longitude }}</td>
                </tr>
                <tr>
                    <th>Jumlah Ajuan</th>
                    <td style="font-weight: bold">{{ formatAngka($ajuanlimit->jumlah) }}</td>
                </tr>
                <tr>
                    <th>LJT Ajuan</th>
                    <td>{{ $ajuanlimit->ljt }} Hari</td>
                </tr>
            </table>
        </div>
        <div class="col-lg-1 col-md-12 col-sm-12">
            <div class="divider divider-vertical">
                <div class="divider-text">
                    <i class="ti ti-crown"></i>
                </div>
            </div>
        </div>
    </div>
</form>

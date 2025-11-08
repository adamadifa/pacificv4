<div class="row">
    <div class="col-12">
        <table class="table">
            <tr>
                <th>Kode</th>
                <td>{{ $saldoawalbukubesar->kode_saldo_awal }}</td>
            </tr>
            <tr>
                <th>Bulan</th>
                <td>{{ $nama_bulan[$saldoawalbukubesar->bulan] }}</td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td>{{ $saldoawalbukubesar->tahun }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ date('d-m-Y', strtotime($saldoawalbukubesar->tanggal)) }}</td>
            </tr>
        </table>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-12">
        <div class="table-responsive mb-2">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailsaldoawalbukubesar as $d)
                        <tr>
                            <td>{{ $d->kode_akun }}</td>
                            <td>{{ $d->nama_akun }}</td>
                            <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


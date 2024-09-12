<form action="{{ route('visitpelanggan.update', Crypt::encrypt($visit->kode_visit)) }}" method="POST" id="frmvisitpelanggan">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>No. Faktur</th>
                    <td>{{ $faktur->no_faktur }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($faktur->tanggal) }}</td>
                </tr>
                <tr>
                    <th> Pelanggan</th>
                    <td>{{ $faktur->kode_pelanggan }} {{ textUpperCase($faktur->nama_pelanggan) }}</td>
                </tr>
                <tr>
                    <th>Jenis Transaksi</th>
                    <th>{{ $faktur->jenis_transaksi == 'T' ? 'Tunai' : 'Kredit' }}</th>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $faktur->alamat_pelanggan }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <table>
                <tr>
                    <td class="fw-bold">Hasil Konfirmasi</td>
                </tr>
                <tr>
                    <td>{{ $visit->hasil_konfirmasi }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Note</td>
                </tr>
                <tr>
                    <td>{{ $visit->note }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Saran / Keluhan</td>
                </tr>
                <tr>
                    <td>{{ $visit->saran }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Action OM</td>
                </tr>
                <tr>
                    <td>{{ $visit->act_om }}</td>
                </tr>
            </table>
        </div>
    </div>
</form>

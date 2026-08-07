<form action="{{ route('penjualan.updatesetpkp', Crypt::encrypt($penjualan->no_faktur)) }}" method="POST" id="formSetpkp">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>No. Faktur</th>
                    <td>{{ $penjualan->no_faktur }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($penjualan->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Nama Pelanggan</th>
                    <td>{{ $penjualan->nama_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Cabang Asal</th>
                    <td>{{ strtoupper($penjualan->nama_cabang) }}</td>
                </tr>
                <tr>
                    <th>Kode PKP Saat Ini</th>
                    <td>
                        @if(!empty($penjualan->kode_pkp))
                            <span class="badge bg-success">{{ $penjualan->kode_pkp }}</span>
                        @else
                            <span class="badge bg-secondary">Belum ditentukan (NULL)</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <div class="form-group mb-3">
                <label for="kode_pkp" class="form-label">Tentukan Cabang PKP</label>
                <select name="kode_pkp" id="kode_pkp" class="form-select">
                    <option value="">Pilih Cabang PKP (Set NULL)</option>
                    @foreach($cabang as $c)
                        <option value="{{ $c->kode_cabang }}" {{ $penjualan->kode_pkp == $c->kode_cabang ? 'selected' : '' }}>
                            {{ strtoupper($c->kode_cabang) }} - {{ strtoupper($c->nama_cabang) }} {{ !empty($c->nama_pt) ? '(' . strtoupper($c->nama_pt) . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary w-100"><i class="ti ti-device-floppy me-1"></i>Simpan Cabang PKP</button>
        </div>
    </div>
</form>

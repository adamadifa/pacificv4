<form action="{{ route('barangproduksi.update', Crypt::encrypt($barangproduksi->kode_barang_produksi)) }}"
    id="formeditBarang" method="POST">
    @csrf
    @method('PUT')
    <x-input-with-icon-label icon="ti ti-barcode" label="Kode Barang" name="kode_barang_produksi"
        value="{{ $barangproduksi->kode_barang_produksi }}" readonly="true" />
    <x-input-with-icon-label icon="ti ti-box" label="Nama Barang" name="nama_barang"
        value="{{ $barangproduksi->nama_barang }}" />
    <x-input-with-icon-label icon="ti ti-box" label="Satuan" name="satuan" value="{{ $barangproduksi->satuan }}" />
    <div class="form-group mb-3">
        <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Asal Barang</label>
        <select name="kode_asal_barang" id="kode_asal_barang" class="form-select">
            <option value="">Asal Barang</option>
            <option value="GD" {{ $barangproduksi->kode_asal_barang == 'GD' ? 'selected' : '' }}>Gudang</option>
            <option value="SS" {{ $barangproduksi->kode_asal_barang == 'SS' ? 'selected' : '' }}>Seasoning</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Kategori Barang</label>
        <select name="kode_kategori" id="kode_kategori" class="form-select">
            <option value="">Kategori</option>
            @foreach ($list_kategori_barang_produksi as $d)
                <option value="{{ $d['kode_kategori'] }}"
                    {{ $barangproduksi->kode_kategori == $d['kode_kategori'] ? 'selected' : '' }}>
                    {{ $d['nama_kategori'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Status Aktif Barang</label>
        <select name="status_aktif_barang" id="status_aktif_barang" class="form-select">
            <option value="">Status</option>
            <option value="1" {{ $barangproduksi->status_aktif_barang === '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ $barangproduksi->status_aktif_barang === '0' ? 'selected' : '' }}>Non Aktif
            </option>
        </select>
    </div>
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>

<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/barangproduksi/edit.js') }}"></script>

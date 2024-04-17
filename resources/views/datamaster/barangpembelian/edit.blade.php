<form action="{{ route('barangpembelian.update', Crypt::encrypt($barangpembelian->kode_barang)) }}"
    id="formeditBarangpembelian" method="POST">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Kode Barang" name="kode_barang"
        value="{{ $barangpembelian->kode_barang }}" />
    <x-input-with-icon icon="ti ti-box" label="Nama Barang" name="nama_barang"
        value="{{ $barangpembelian->nama_barang }}" />
    <x-input-with-icon icon="ti ti-box" label="Satuan" name="satuan" value="{{ $barangpembelian->satuan }}" />
    <div class="form-group mb-3">
        <select name="kode_jenis_barang" id="kode_jenis_barang" class="form-select">
            <option value="">Jenis Barang</option>
            @foreach ($list_jenis_barang as $d)
                <option value="{{ $d['kode_jenis_barang'] }}"
                    {{ $barangpembelian->kode_jenis_barang == $d['kode_jenis_barang'] ? 'selected' : '' }}>
                    {{ $d['nama_jenis_barang'] }}</option>
            @endforeach
        </select>
    </div>
    <x-select label="Kategori" name="kode_kategori" :data="$kategori" key="kode_kategori" textShow="nama_kategori"
        selected="{{ $barangpembelian->kode_kategori }}" />
    <div class="form-group mb-3">
        <select name="kode_group" id="kode_group" class="form-select">
            <option value="">Group</option>
            @foreach ($list_group as $d)
                <option value="{{ $d['kode_group'] }}"
                    {{ $barangpembelian->kode_group == $d['kode_group'] ? 'selected' : '' }}>{{ $d['nama_group'] }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="status" id="status" class="form-select">
            <option value="">Status</option>
            <option value="1" {{ $barangpembelian->status == '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ $barangpembelian->status === '0' ? 'selected' : '' }}>Non Aktif</option>
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
<script src="{{ asset('assets/js/pages/barangpembelian/edit.js') }}"></script>

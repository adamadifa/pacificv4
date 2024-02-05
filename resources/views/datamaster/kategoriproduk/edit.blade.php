<form action="{{ route('kategoriproduk.update', Crypt::encrypt($kategoriproduk->kode_kategori_produk)) }}"
    id="formeditKategoriproduk" method="POST">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Kode Kategori Produk" name="kode_kategori_produk"
        value="{{ $kategoriproduk->kode_kategori_produk }}" disabled="true" />
    <x-input-with-icon icon="ti ti-file-text" label="Nama Kategori Produk" name="nama_kategori_produk"
        value="{{ $kategoriproduk->nama_kategori_produk }}" />
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
<script src="{{ asset('assets/js/pages/kategoriproduk/edit.js') }}"></script>

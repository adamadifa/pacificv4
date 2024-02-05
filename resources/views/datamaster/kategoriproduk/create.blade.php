<form action="{{ route('kategoriproduk.store') }}" id="formcreateKategoriproduk" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Kode Kategori Produk" name="kode_kategori_produk" />
    <x-input-with-icon icon="ti ti-file-text" label="Nama Kategori Produk" name="nama_kategori_produk" />
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
<script src="{{ asset('assets/js/pages/kategoriproduk/create.js') }}"></script>

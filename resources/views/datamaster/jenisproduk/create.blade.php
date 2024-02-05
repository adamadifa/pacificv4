<form action="{{ route('jenisproduk.store') }}" id="formcreateJenisproduk" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Kode Jenis Produk" name="kode_jenis_produk" />
    <x-input-with-icon icon="ti ti-file-text" label="Nama Jenis Produk" name="nama_jenis_produk" />
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
<script src="{{ asset('assets/js/pages/jenisproduk/create.js') }}"></script>

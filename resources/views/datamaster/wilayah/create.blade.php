<form action="{{ route('wilayah.store') }}" id="formcreateWilayah" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Kode Wilayah" name="kode_wilayah" />
    <x-input-with-icon icon="ti ti-map-pin" label="Nama Wilayah" name="nama_wilayah" />
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
<script src="{{ asset('assets/js/pages/wilayah/create.js') }}"></script>

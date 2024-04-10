<form action="{{ route('angkutan.store') }}" id="formcreateAngkutan" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Auto" name="kode_angkutan" readonly="true" />
    <x-input-with-icon icon="ti ti-file-text" label="Nama Angkutan" name="nama_angkutan" />
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
<script src="{{ asset('assets/js/pages/angkutan/create.js') }}"></script>

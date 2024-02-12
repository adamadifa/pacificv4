<form action="{{ route('regional.store') }}" id="formcreateRegional" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Kode Regional" name="kode_regional" />
    <x-input-with-icon icon="ti ti-map-pin" label="Nama Regional" name="nama_regional" />
    <div class="form-group">
        <button class="btn btn-primary w-100" id="btnSubmit" type="submit" name="submitButton">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>


<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/regional/create.js') }}"></script>

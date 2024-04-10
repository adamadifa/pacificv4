<form action="{{ route('tujuanangkutan.store') }}" id="formcreateTujuanangkutan" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Kode Tujuan" name="kode_tujuan" />
    <x-input-with-icon icon="ti ti-file-text" label="Nama Tujuan" name="tujuan" />
    <x-input-with-icon icon="ti ti-file-text" label="Tarif" name="tarif" align="right" money="true" />
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
<script src="{{ asset('assets/js/pages/tujuanangkutan/create.js') }}"></script>
<script>
    $(function() {
        $(".money").maskMoney();
    });
</script>

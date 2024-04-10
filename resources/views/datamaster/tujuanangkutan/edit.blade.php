<form action="{{ route('tujuanangkutan.update', Crypt::encrypt($tujuanangkutan->kode_tujuan)) }}"
    id="formeditTujuanangkutan" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Kode Tujuan" name="kode_tujuan"
        value="{{ $tujuanangkutan->kode_tujuan }}" readonly="true" />
    <x-input-with-icon icon="ti ti-file-text" label="Nama Tujuan" name="tujuan" value="{{ $tujuanangkutan->tujuan }}" />
    <x-input-with-icon icon="ti ti-file-text" label="Tarif" name="tarif" align="right" money="true"
        value="{{ formatAngka($tujuanangkutan->tarif) }}" />
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
<script src="{{ asset('assets/js/pages/tujuanangkutan/edit.js') }}"></script>
<script>
    $(function() {
        $(".money").maskMoney();
    });
</script>

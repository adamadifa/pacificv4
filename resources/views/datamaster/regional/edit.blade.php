<form action="{{ route('regional.update', Crypt::encrypt($regional->kode_regional)) }}" id="formeditRegional"
    method="POST">
    @method('PUT')
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Kode Regional" value="{{ $regional->kode_regional }}"
        name="kode_regional" readonly="true" />
    <x-input-with-icon icon="ti ti-map-pin" label="Nama Regional" value="{{ $regional->nama_regional }}"
        name="nama_regional" />
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Update
        </button>
    </div>
</form>

<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/regional/edit.js') }}"></script>

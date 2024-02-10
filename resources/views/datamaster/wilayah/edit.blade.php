<form action="{{ route('wilayah.update', Crypt::encrypt($wilayah->kode_wilayah)) }}" id="formeditWilayah" method="POST">
    @method('PUT')
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Kode Wilayah" value="{{ $wilayah->kode_wilayah }}" name="kode_wilayah"
        readonly="true" />
    <x-input-with-icon icon="ti ti-map-pin" label="Nama Wilayah" value="{{ $wilayah->nama_wilayah }}"
        name="nama_wilayah" />
    @hasanyrole($roles_show_cabang)
        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
            selected="{{ $wilayah->kode_cabang }}" />
    @endhasanyrole
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
<script src="{{ asset('assets/js/pages/wilayah/edit.js') }}"></script>

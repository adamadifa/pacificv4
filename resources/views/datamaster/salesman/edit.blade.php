<form action="{{ route('salesman.update', Crypt::encrypt($salesman->kode_salesman)) }}" id="formeditSalesman"
    method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Kode Salesman" name="kode_salesman"
        value="{{ $salesman->kode_salesman }}" disabled="true" />
    <x-input-with-icon icon="ti ti-file-text" label="Nama Salesman" name="nama_salesman"
        value="{{ $salesman->nama_salesman }}" />
    <x-input-with-icon icon="ti ti-map-pin" label="Alamat Salesman" name="alamat_salesman"
        value="{{ $salesman->alamat_salesman }}" />
    <x-input-with-icon icon="ti ti-phone" label="No. HP Salesman" name="no_hp_salesman"
        value="{{ $salesman->no_hp_salesman }}" />
    <x-select label="Kategori Salesman" name="kode_kategori_salesman" :data="$kategori_salesman" key="kode_kategori_salesman"
        textShow="nama_kategori_salesman" selected="{{ $salesman->kode_kategori_salesman }}" />
    <div class="form-group mb-3">
        <select name="status_komisi_salesman" id="status_komisi_salesman" class="form-select">
            <option value="">Status Komisi</option>
            <option value="1" {{ $salesman->status_komisi_salesman === '1' ? 'selected' : '' }}>Ya</option>
            <option value="0" {{ $salesman->status_komisi_salesman === '0' ? 'selected' : '' }}>Tidak</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="status_aktif_salesman" id="status_aktif_salesman" class="form-select">
            <option value="">Status</option>
            <option value="1" {{ $salesman->status_aktif_salesman === '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ $salesman->status_aktif_salesman === '0' ? 'selected' : '' }}>Non Aktif</option>
        </select>
    </div>
    @hasanyrole($roles_show_cabang)
        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
            selected="{{ $salesman->kode_cabang }}" />
    @endhasanyrole
    <x-input-file name="marker" label="Marker" />
    @if (!empty($salesman->marker))
        <div class="mb-3">
            <img src="{{ getdocMarker($salesman->marker) }}" alt="user-avatar"
                class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar">
        </div>
    @endif

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
<script src="{{ asset('assets/js/pages/salesman/edit.js') }}"></script>

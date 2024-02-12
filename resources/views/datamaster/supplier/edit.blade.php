<form action="{{ route('supplier.update', Crypt::encrypt($supplier->kode_supplier)) }}" id="formeditSupplier"
    method="POST">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Kode Supplier" value="{{ $supplier->kode_supplier }}" disabled="true"
        name="kode_supplier" />
    <x-input-with-icon icon="ti ti-user" label="Nama Supplier" name="nama_supplier"
        value="{{ $supplier->nama_supplier }}" />
    <x-input-with-icon icon="ti ti-map" label="Alamat" name="alamat_supplier"
        value="{{ $supplier->alamat_supplier }}" />
    <x-input-with-icon icon="ti ti-user" label="Contact Person" name="contact_person"
        value="{{ $supplier->contact_person }}" />
    <x-input-with-icon icon="ti ti-phone" label="No. HP" name="no_hp_supplier"
        value="{{ $supplier->no_hp_supplier }}" />
    <x-input-with-icon icon="ti ti-mail" label="Email" name="email_supplier" value="{{ $supplier->email_supplier }}" />
    <x-input-with-icon icon="ti ti-credit-card" label="No. Rekening" name="no_rekening_supplier"
        value="{{ $supplier->no_rekening_supplier }}" />
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
<script src="{{ asset('assets/js/pages/supplier/edit.js') }}"></script>

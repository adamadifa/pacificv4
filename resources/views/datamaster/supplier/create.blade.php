<form action="{{ route('supplier.store') }}" id="formcreateSupplier" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Auto" disabled="true" name="kode_supplier" />
    <x-input-with-icon icon="ti ti-user" label="Nama Supplier" name="nama_supplier" />
    <x-input-with-icon icon="ti ti-map" label="Alamat" name="alamat_supplier" />
    <x-input-with-icon icon="ti ti-user" label="Contact Person" name="contact_person" />
    <x-input-with-icon icon="ti ti-phone" label="No. HP" name="no_hp_supplier" />
    <x-input-with-icon icon="ti ti-mail" label="Email" name="email_supplier" />
    <x-input-with-icon icon="ti ti-credit-card" label="No. Rekening" name="no_rekening_supplier" />
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
<script src="{{ asset('assets/js/pages/supplier/create.js') }}"></script>

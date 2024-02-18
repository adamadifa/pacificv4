<form action="{{ route('bpjskesehatan.store') }}" aria-autocomplete="false" id="formcreateBpjskesehatan" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal Berlaku" name="tanggal_berlaku" datepicker="flatpickr-date" />
    <x-select label="Karyawan" name="nik" :data="$karyawan" key="nik" textShow="nama_karyawan"
        select2="select2Karyawan" />
    <x-input-with-icon icon="ti ti-moneybag" label="Iuran" name="iuran" money="true" align="right" />
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/bpjskesehatan/create.js') }}"></script>
<script>
    $(function() {
        $(".flatpickr-date").flatpickr();
        $(".money").maskMoney();
        const select2Karyawan = $('.select2Karyawan');
        if (select2Karyawan.length) {
            select2Karyawan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Karyawan',
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>

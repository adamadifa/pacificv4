<form action="{{ route('ajuantransfer.update', Crypt::encrypt($ajuantransfer->no_pengajuan)) }}" method="POST" id="formAjuantransferdana">
    @csrf
    @method('PUT')
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" value="{{ $ajuantransfer->tanggal }}" />
    <x-input-with-icon label="Nama" name="nama" icon="ti ti-user" value="{{ $ajuantransfer->nama }}" />
    <x-input-with-icon label="Nama Bank" name="nama_bank" icon="ti ti-building" value="{{ $ajuantransfer->nama_bank }}" />
    <x-input-with-icon label="No Rekening" name="no_rekening" icon="ti ti-credit-card" value="{{ $ajuantransfer->no_rekening }}" />
    <x-input-with-icon label="Jumlah" name="jumlah" icon="ti ti-moneybag" align="right" money="true"
        value="{{ formatAngka($ajuantransfer->jumlah) }}" />
    <x-textarea label="Keterangan" name="keterangan" value="{{ $ajuantransfer->keterangan }}" />
    @hasanyrole($roles_show_cabang)
        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
            select2="select2KodeCabang" selected="{{ $ajuantransfer->kode_cabang }}" />
    @endhasanyrole
    <div class="form-group mb-3">
        <button type="submit" class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i> Submit</button>
    </div>
</form>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/ajuantransferdana.js') }}"></script>
<script>
    $(function() {
        $(".flatpickr-date").flatpickr();
        $(".money").maskMoney();
        const select2KodeCabang = $('.select2KodeCabang');
        if (select2KodeCabang.length) {
            select2KodeCabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>

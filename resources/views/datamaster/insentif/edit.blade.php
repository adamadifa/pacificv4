<form action="{{ route('insentif.update', Crypt::encrypt($insentif->kode_insentif)) }}" aria-autocomplete="false" id="formeditInsentif" method="POST">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal Berlaku" name="tanggal_berlaku" datepicker="flatpickr-date"
        value="{{ $insentif->tanggal_berlaku }}" />
    <x-select label="Karyawan" name="nik" :data="$karyawan" key="nik" textShow="nama_karyawan" select2="select2Karyawan"
        selected="{{ $insentif->nik }}" />
    <div class="divider text-start">
        <div class="divider-text">
            <i class="ti ti-moneybag me-1"></i> Insentif Umum
        </div>
    </div>
    <x-input-inline-label icon="ti ti-moneybag" label="Masa Kerja" name="iu_masakerja" money="true" align="right"
        value="{{ formatRupiah($insentif->iu_masakerja) }}" />
    <x-input-inline-label icon="ti ti-moneybag" label="Lembur" name="iu_lembur" money="true" align="right"
        value="{{ formatRupiah($insentif->iu_lembur) }}" />
    <x-input-inline-label icon="ti ti-moneybag" label="Penempatan" name="iu_penempatan" money="true" align="right"
        value="{{ formatRupiah($insentif->iu_penempatan) }}" />
    <x-input-inline-label icon="ti ti-moneybag" label="KPI" name="iu_kpi" money="true" align="right"
        value="{{ formatRupiah($insentif->iu_kpi) }}" />
    <div class="divider text-start">
        <div class="divider-text">
            <i class="ti ti-moneybag me-1"></i> Insentif Manager
        </div>
    </div>
    <x-input-inline-label icon="ti ti-moneybag" label="Ruang Lingkup" name="im_ruanglingkup" money="true" align="right"
        value="{{ formatRupiah($insentif->im_ruanglingkup) }}" />
    <x-input-inline-label icon="ti ti-moneybag" label="Penempatan" name="im_penempatan" money="true" align="right"
        value="{{ formatRupiah($insentif->im_penempatan) }}" />
    <x-input-inline-label icon="ti ti-moneybag" label="Kinerja" name="im_kinerja" money="true" align="right"
        value="{{ formatRupiah($insentif->im_kinerja) }}" />
    <x-input-inline-label icon="ti ti-moneybag" label="Kendaraan" name="im_kendaraan" money="true" align="right"
        value="{{ formatRupiah($insentif->im_kendaraan) }}" />
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
<script src="{{ asset('assets/js/pages/insentif/edit.js') }}"></script>
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

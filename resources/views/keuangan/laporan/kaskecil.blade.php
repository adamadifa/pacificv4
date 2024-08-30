<form action="{{ route('laporankeuangan.cetakkaskecil') }}" id="formKaskecil" target="_blank" method="POST">
    @csrf
    <div class="form-group mb-3">
        <select name="kode_bank_kaskecil" id="kode_bank_kaskecil" class="form-select select2Kodebankkaskecil">
            <option value="">Bank</option>
            @foreach ($bank as $d)
                <option {{ Request('kode_bank_search') == $d->kode_bank ? 'selected' : '' }} value="{{ $d->kode_bank }}">{{ $d->nama_bank }}
                    ({{ $d->no_rekening }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="formatlaporan" id="formatlaporan" class="form-select">
            <option value="">Format Laporan</option>
            <option value="1">Detail</option>
            <option value="2">Rekap</option>
        </select>
    </div>
    <div class="row" id="coa">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <select name="kode_akun_dari" id="kode_akun_dari" class="form-select select2Kodeakundari">
                    <option value="">Semua Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ $d->nama_akun }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <select name="kode_akun_sampai" id="kode_akun_sampai" class="form-select select2Kodeakunsampai">
                    <option value="">Semua Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ $d->nama_akun }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date" />
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButton">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButton">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(function() {
            const formKaskecil = $("#formKaskecil");
            const select2Kodebankkaskecil = $(".select2Kodebankkaskecil");
            if (select2Kodebankkaskecil.length) {
                select2Kodebankkaskecil.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Kas Kecil',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodeakundari = $(".select2Kodeakundari");
            if (select2Kodeakundari.length) {
                select2Kodeakundari.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Akun',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodeakunsampai = $(".select2Kodeakunsampai");
            if (select2Kodeakunsampai.length) {
                select2Kodeakunsampai.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Akun',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function showcoa() {
                const formatlaporan = formKaskecil.find("#formatlaporan").val();
                if (formatlaporan == '1') {
                    $("#coa").show();
                } else {
                    $("#coa").hide();
                }
            }
            showcoa();

            formKaskecil.find("#formatlaporan").change(function() {
                showcoa();
            });

            formKaskecil.submit(function(e) {
                const formatlaporan = formKaskecil.find("#formatlaporan").val();
                const dari = formKaskecil.find("#dari").val();
                const sampai = formKaskecil.find("#sampai").val();
                const start = new Date(dari);
                const end = new Date(sampai);
                if (formatlaporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Jenis Laporan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formKaskecil.find("#formatlaporan").focus();
                        },
                    });
                    return false;
                } else if (dari == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Dari Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formKaskecil.find("#dari").focus();
                        },
                    });
                    return false;
                } else if (sampai == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Sampai Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formKaskecil.find("#sampai").focus();
                        },
                    });
                    return false;
                } else if (start > end) {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Tidak Valid !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formKaskecil.find("#sampai").focus();
                        },
                    });
                    return false;
                }
            });

        });
    </script>
@endpush

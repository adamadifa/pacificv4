<form action="{{ route('laporankeuangan.cetakledger') }}" id="formLedger" target="_blank" method="POST">
    @csrf
    <div class="form-group mb-3">
        <select name="kode_bank_ledger" id="kode_bank_ledgers" class="form-select select2Kodebankledgers">
            <option value="">Ledger</option>
            @foreach ($bank as $d)
                <option {{ Request('kode_bank_search') == $d->kode_bank ? 'selected' : '' }} value="{{ $d->kode_bank }}">
                    {{ $d->nama_bank }}
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
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ $d->nama_akun }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <select name="kode_akun_sampai" id="kode_akun_sampai" class="form-select select2Kodeakunsampai">
                    <option value="">Semua Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ $d->nama_akun }}
                        </option>
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
            const formLedger = $("#formLedger");
            const select2Kodebankledgers = $(".select2Kodebankledgers");
            if (select2Kodebankledgers.length) {
                select2Kodebankledgers.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Ledger',
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
                const formatlaporan = formLedger.find("#formatlaporan").val();
                if (formatlaporan == '1') {
                    $("#coa").show();
                } else {
                    $("#coa").hide();
                }
            }
            showcoa();

            formLedger.find("#formatlaporan").change(function() {
                showcoa();
            });

            formLedger.submit(function(e) {
                const formatlaporan = formLedger.find("#formatlaporan").val();
                const dari = formLedger.find("#dari").val();
                const sampai = formLedger.find("#sampai").val();
                const start = new Date(dari);
                const end = new Date(sampai);
                if (formatlaporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Jenis Laporan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formLedger.find("#formatlaporan").focus();
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
                            formLedger.find("#dari").focus();
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
                            formLedger.find("#sampai").focus();
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
                            formLedger.find("#sampai").focus();
                        },
                    });
                    return false;
                }
            });

        });
    </script>
@endpush

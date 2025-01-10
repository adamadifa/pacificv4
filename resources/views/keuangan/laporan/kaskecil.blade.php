<form action="{{ route('laporankeuangan.cetakkaskecil') }}" id="formKaskecil" target="_blank" method="POST">
    @csrf
    @php
        $role_admin_pusat = ['admin pusat'];
    @endphp
    @hasanyrole(array_merge($roles_show_cabang, $role_admin_pusat))
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_kaskecil" class="form-select select2Kodecabangkaskecil">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole
    <div class="form-group mb-3">
        <select name="formatlaporan" id="formatlaporan" class="form-select">
            <option value="">Format Laporan</option>
            <option value="1">Detail</option>
            <option value="2">Rekap</option>
        </select>
    </div>
    <div class="row" id="coakaskecil">
        <div class="col">
            <div class="form-group">
                <select name="kode_akun_dari" id="kode_akun_dari_kaskecil" class="form-select select2Kodeakundarikaskecil">
                    <option value="">Semua Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ truncateText($d->nama_akun) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col">
            <div class="row">
                <select name="kode_akun_sampai" id="kode_akun_sampai_kaskecil" class="form-select select2Kodeakunsampaikaskecil">
                    <option value="">Semua Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ truncateText($d->nama_akun) }}</option>
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
        $(document).ready(function() {
            const formKaskecil = $("#formKaskecil");
            const select2Kodecabangkaskecil = $(".select2Kodecabangkaskecil");
            if (select2Kodecabangkaskecil.length) {
                select2Kodecabangkaskecil.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }
            const select2Kodeakundarikaskecil = $(".select2Kodeakundarikaskecil");
            if (select2Kodeakundarikaskecil.length) {
                select2Kodeakundarikaskecil.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Akun',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }
            const select2Kodeakunsampaikaskecil = $(".select2Kodeakunsampaikaskecil");
            if (select2Kodeakunsampaikaskecil.length) {
                select2Kodeakunsampaikaskecil.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Akun',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }


            function showcoakaskecil() {
                const formatlaporan = formKaskecil.find("#formatlaporan").val();
                if (formatlaporan == '1') {
                    formKaskecil.find("#coakaskecil").show();
                } else {
                    formKaskecil.find("#coakaskecil").hide();
                }
            }
            showcoakaskecil();

            formKaskecil.find("#formatlaporan").change(function() {
                showcoakaskecil();
            });

            formKaskecil.submit(function(e) {
                const kode_cabang = formKaskecil.find("#kode_cabang_kaskecil").val();
                const formatlaporan = formKaskecil.find("#formatlaporan").val();
                const dari = formKaskecil.find('#dari').val();
                const sampai = formKaskecil.find('#sampai').val();
                const start = new Date(dari);
                const end = new Date(sampai);
                // if (kode_cabang == "") {
                //     Swal.fire({
                //         title: "Oops!",
                //         text: 'Cabang Harus Diisi !',
                //         icon: "warning",
                //         showConfirmButton: true,
                //         didClose: (e) => {
                //             formKaskecil.find("#kode_cabang_kaskecil").focus();
                //         },
                //     })
                //     return false;
                // } else
                if (formatlaporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Format Laporan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formKaskecil.find("#formatlaporan").focus();
                        },
                    })
                    return false;
                } else if (dari == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Dari Tanggal Harus Diisi !',
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
                        text: 'Sampai Tanggal Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formKaskecil.find("#sampai").focus();
                        },
                    });
                    return false;
                } else if (start.getTime() > end.getTime()) {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Tidak Valid !, Periode Sampai Harus Lebih Akhir dari Periode Dari',
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

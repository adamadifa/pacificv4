<form method="POST" action="{{ route('laporangudangcabang.cetakmutasidpb') }}" id="frmMutasidpb" target="_blank">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="row">
            <div class="col">
                <x-select label="Pilih Cabang" name="kode_cabang_mutasidpb" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
                    select2="select2Kodecabangmutasidpb" />
            </div>
        </div>
    @endrole
    <div class="row">
        <div class="col">
            <x-select label="Pilih Produk" name="kode_produk_mutasidpb" :data="$produk" key="kode_produk" textShow="nama_produk"
                select2="select2Kodeprodukmutasidpb" showKey="true" upperCase="true" />
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
            const select2Kodecabangmutasidpb = $('.select2Kodecabangmutasidpb');
            if (select2Kodecabangmutasidpb.length) {
                select2Kodecabangmutasidpb.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }


            const select2Kodeprodukmutasidpb = $('.select2Kodeprodukmutasidpb');
            if (select2Kodeprodukmutasidpb.length) {
                select2Kodeprodukmutasidpb.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Produk',
                        dropdownParent: $this.parent(),
                        allowClear: true
                    });
                });
            }
            $("#frmMutasidpb").submit(function() {
                const kode_produk = $(this).find("#kode_produk_mutasidpb").val();
                const dari = $(this).find("#dari").val();
                const sampai = $(this).find("#sampai").val();
                const kode_cabang = $(this).find("#kode_cabang_mutasidpb").val();
                var start = new Date(dari);
                var end = new Date(sampai);
                if (kode_cabang == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Kode Cabang Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#kode_cabang").focus();
                        },
                    });

                    return false;
                } else if (kode_produk == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Kode Produk Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#kode_produk").focus();
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
                            $(this).find("#dari").focus();
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
                            $(this).find("#sampai").focus();
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
                            $(this).find("#sampai").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush

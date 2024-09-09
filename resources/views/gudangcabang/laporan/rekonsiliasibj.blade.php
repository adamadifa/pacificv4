<form method="POST" action="{{ route('laporangudangcabang.cetakrekonsiliasibj') }}" id="frmRekonsiliasibj" target="_blank">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="row">
            <div class="col">
                <x-select label="Pilih Cabang" name="kode_cabang_rekonsiliasi" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
                    select2="select2Kodecabangrekonsiliasi" />
            </div>
        </div>
    @endrole
    <div class="form-group mb-3">
        <select name="kode_salesman" id="kode_salesman_rekonsiliasi" class="select2Kodesalesmanrekonsiliasi form-select">
        </select>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date" />
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <select name="jenis_rekonsiliasi" id="jenis_rekonsiliasi" class="form-select">
                <option value="">Jenis Rekonsiliasi</option>
                <option value="1">Penjualan</option>
                <option value="2">Retur</option>
                <option value="3">Promosi</option>
            </select>
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
            const formRekonsiliasibj = $('#frmRekonsiliasibj');
            const select2Kodecabangrekonsiliasi = $('.select2Kodecabangrekonsiliasi');
            if (select2Kodecabangrekonsiliasi.length) {
                select2Kodecabangrekonsiliasi.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmanrekonsiliasi = $(".select2Kodesalesmanrekonsiliasi");
            if (select2Kodesalesmanrekonsiliasi.length) {
                select2Kodesalesmanrekonsiliasi.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmanbyCabang() {
                var kode_cabang = formRekonsiliasibj.find("#kode_cabang_rekonsiliasi").val();
                //alert(selected);
                $.ajax({
                    type: 'POST',
                    url: '/salesman/getsalesmanbycabang',
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(respond) {
                        console.log(respond);
                        formRekonsiliasibj.find("#kode_salesman_rekonsiliasi").html(respond);
                    }
                });
            }

            getsalesmanbyCabang();
            formRekonsiliasibj.find("#kode_cabang_rekonsiliasi").change(function(e) {
                getsalesmanbyCabang();
            });

            $("#frmRekonsiliasibj").submit(function() {
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

<form id="formBayar" method="POST" action="{{ route('pembayaranpenjualan.update', Crypt::encrypt($historibayar->no_bukti)) }}">
    @csrf
    @method('PUT')
    {{-- {{ $historibayar->no_bukti }} --}}
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal Pembayaran" name="tanggal" datepicker="flatpickr-date" value="{{ $historibayar->tanggal }}" />
    <x-input-with-icon icon="ti ti-moneybag" label="Jumlah Bayar" name="jumlah" align="right" value="{{ formatAngka($historibayar->jumlah) }}" />
    @if ($level_user !== 'salesman')
        <x-select label="Salesman Penagih" name="kode_salesman" :data="$salesman" key="kode_salesman" textShow="nama_salesman" upperCase="true"
            select2="select2Kodesalesman" selected="{{ $historibayar->kode_salesman }}" />
    @else
        <input type="hidden" name="kode_salesman" value="{{ Auth::user()->kode_salesman }}" />
    @endif

    <div class="row mt-2">
        <div class="col-12">
            <div class="form-check mt-3 mb-2">
                <input class="form-check-input agreementvoucher" name="agreementvoucher" value="1" type="checkbox" value=""
                    id="agreementvoucher" {{ $historibayar->voucher == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="agreementvoucher"> Bayar Menggunakan Voucher ? </label>
            </div>
        </div>
    </div>
    <div class="row" id="voucher">
        <div class="col">
            <x-select label="Pilih Voucher" name="jenis_voucher" :data="$jenis_voucher" key="id" textShow="nama_voucher" upperCase="true"
                select2="select2Kodevoucher" selected="{{ $historibayar->jenis_voucher }}" />
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-check mb-3">
                <input class="form-check-input agreementgiro" name="agreementgiro" value="1" type="checkbox" id="agreementgiro"
                    {{ $historibayar->giro_to_cash == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="agreementgiro"> Ganti Giro Ke Cash ? </label>
            </div>
        </div>
    </div>
    <div class="row" id="giroditolak">
        <div class="col">
            <x-select label="Pilih Giro" name="kode_giro" :data="$giroditolak" key="kode_giro" textShow="no_giro" upperCase="true"
                select2="select2Kodegiro" selected="{{ $historibayar->kode_giro }}" />
        </div>
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const form = $("#formBayar");

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }
        $(".flatpickr-date").flatpickr({
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }, ]
        });


        const select2Kodesalesman = $('.select2Kodesalesman');
        if (select2Kodesalesman.length) {
            select2Kodesalesman.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Salesman Penagih',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodevoucher = $('.select2Kodevoucher');
        if (select2Kodevoucher.length) {
            select2Kodevoucher.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Voucher',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
        $("#jumlah").maskMoney();

        form.find("#voucher").hide();
        form.find("#giroditolak").hide();
        form.find('.agreementvoucher').change(function() {
            if (this.checked) {
                console.log($(".agreementvoucher").is(':checked'));
                form.find("#voucher").show();
            } else {
                form.find("#voucher").hide();
            }
        });
        form.find('.agreementgiro').change(function() {
            if (this.checked) {
                form.find("#giroditolak").show();
            } else {
                form.find("#giroditolak").hide();
            }
        });

        function loadgiroditolak() {
            if ($(".agreementgiro").is(':checked')) {
                form.find("#giroditolak").show();
            } else {
                form.find("#giroditolak").hide();
            }
        }

        loadgiroditolak();
        form.submit(function(e) {
            //e.preventDefault();
            const jumlahbayar = "{{ $historibayar->jumlah }}";
            const sisabayar = $("#sisabayar").text();
            let sb = sisabayar == "" ? "0" : sisabayar; // Ubah 0 menjadi string
            let sisa_bayar = (parseInt(sb.replace(/\./g, '')) || 0) + (parseInt(jumlahbayar) || 0);

            // alert(sisa_bayar);
            const tanggal = $(this).find("#tanggal").val();
            const jml = $(this).find("#jumlah").val();
            const jumlah = parseInt(jml.replace(/\./g, ''));
            const kode_salesman = $(this).find("#kode_salesman").val();
            const jenis_voucher = $(this).find("#jenis_voucher").val();
            const kode_giro = $(this).find("#kode_giro").val();
            if (isNaN(sisa_bayar)) {
                sisa_bayar = 0;
            } else {
                sisa_bayar = sisa_bayar;
            }


            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (jml === "" || jml === '0') {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });
                return false;
            } else if (kode_salesman == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Salesman Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_salesman").focus();
                    },
                });

                return false;
            } else if (parseInt(jumlah) > parseInt(sisa_bayar)) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Bayar Melebihi Sisa Bayar !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });
                return false;
            } else if ($(".agreementvoucher").is(':checked') && jenis_voucher == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jenis Voucher Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jenis_voucher").focus();
                    },
                });

                return false;
            } else if ($(".agreementgiro").is(':checked') && kode_giro == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih Giro Yang Diganti !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_giro").focus();
                    },
                });

                return false;
            } else {
                buttonDisable();
            }


        });
    });
</script>

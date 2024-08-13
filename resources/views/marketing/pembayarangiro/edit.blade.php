<form id="formBayar" method="POST" action="{{ route('pembayarangiro.update', [Crypt::encrypt($no_faktur), Crypt::encrypt($kode_giro)]) }}">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="No. Giro" name="no_giro" value="{{ $giro->no_giro }}" />
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal Giro" name="tanggal" value="{{ $giro->tanggal }}" datepicker="flatpickr-date" />
    <x-input-with-icon icon="ti ti-moneybag" label="Jumlah Bayar" name="jumlah" align="right" value="{{ formatAngka($giro->jumlah) }}" />
    @if ($level_user == 'salesman')
        <input type="hidden" name="kode_salesman" value="{{ auth()->user()->kode_salesman }}">
    @else
        <x-select label="Salesman Penagih" name="kode_salesman" :data="$salesman" key="kode_salesman" textShow="nama_salesman" upperCase="true"
            select2="select2Kodesalesman" selected="{{ $giro->kode_salesman }}" />
    @endif

    <x-input-with-icon icon="ti ti-building" label="Bank Pengirim" name="bank_pengirim" value="{{ $giro->bank_pengirim }}" />
    <x-input-with-icon icon="ti ti-calendar" label="Jatuh Tempo" name="jatuh_tempo" value="{{ $giro->jatuh_tempo }}" datepicker="flatpickr-date" />
    <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" value="{{ $giro->keterangan }}" />
    <div class="row">
        <div class="col">
            <button class="btn btn-primary w-100"><i class="ti ti-send me-1"></i>Submit</button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const form = $("#formBayar");
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


        $("#jumlah").maskMoney();





        form.submit(function(e) {

            const no_giro = $(this).find("#no_giro").val();
            const bank_pengirim = $(this).find("#bank_pengirim").val();
            const jatuh_tempo = $(this).find("#jatuh_tempo").val();
            const sisabayar = $("#sisabayar").text();
            let sisa_bayar = parseInt(sisabayar.replace(/\./g, ''));
            const tanggal = $(this).find("#tanggal").val();
            const jml = $(this).find("#jumlah").val();
            const jumlah = parseInt(jml.replace(/\./g, ''));
            const kode_salesman = $(this).find("#kode_salesman").val();
            if (isNaN(sisa_bayar)) {
                sisa_bayar = 0;
            } else {
                sisa_bayar = sisa_bayar;
            }

            if (no_giro == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No Giro Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_giro").focus();
                    },
                });
                return false;
            } else if (tanggal == "") {
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
            } else if (bank_pengirim == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bank Pengirim Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#bank_pengirim").focus();
                    },
                });
                return false;
            } else if (jatuh_tempo == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Jatuh Tempo Harus  Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jatuh_tempo").focus();
                    },
                });
                return false;
            }
        });
    });
</script>

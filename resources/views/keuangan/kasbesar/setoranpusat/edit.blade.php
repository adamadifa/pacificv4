<form action="{{ route('setoranpusat.update', Crypt::encrypt($setoranpusat->kode_setoran)) }}" id="formSetoranpusat" method="POST">
    @csrf
    @method('PUT')
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" value="{{ $setoranpusat->tanggal }}" />
    @hasanyrole($roles_show_cabang)
        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true" select2="select2Kodecabang" selected="{{ $setoranpusat->kode_cabang }}" />
    @endhasanyrole
    <x-input-with-icon label="Setoran Kertas" name="setoran_kertas" icon="ti ti-moneybag" align="right" money="true" value="{{ formatAngka($setoranpusat->setoran_kertas) }}" />
    <x-input-with-icon label="Setoran Logam" name="setoran_logam" icon="ti ti-moneybag" align="right" money="true" value="{{ formatAngka($setoranpusat->setoran_logam) }}" />
    <x-input-with-icon label="Total Setoran" name="total_setoran" icon="ti ti-moneybag" align="right" readonly="true"
        value="{{ formatAngka($setoranpusat->setoran_kertas + $setoranpusat->setoran_logam) }}" />
    <x-textarea label="Keterangan" name="keterangan" value="{{ $setoranpusat->keterangan }}" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {

        const form = $("#formSetoranpusat");
        $(".flatpickr-date").flatpickr();
        $(".money").maskMoney();

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }

        function convertToRupiah(number) {
            if (number) {
                var rupiah = "";
                var numberrev = number
                    .toString()
                    .split("")
                    .reverse()
                    .join("");
                for (var i = 0; i < numberrev.length; i++)
                    if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
                return (
                    rupiah
                    .split("", rupiah.length - 1)
                    .reverse()
                    .join("")
                );
            } else {
                return number;
            }
        }
        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih  Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function loadtotal() {
            let sk = form.find('#setoran_kertas').val();
            let sl = form.find('#setoran_logam').val();
            let setoran_kertas = sk != "" ? parseInt(sk.replace(/\./g, '')) : 0;
            let setoran_logam = sl != "" ? parseInt(sl.replace(/\./g, '')) : 0;
            let total = parseInt(setoran_kertas) + parseInt(setoran_logam);
            form.find("#total_setoran").val(convertToRupiah(total));
            // console.log(total);
        }

        form.find('#setoran_kertas, #setoran_logam').on('keyup keydown', function() {
            loadtotal();
        });

        form.submit(function() {
            const tanggal = $(this).find("#tanggal").val();
            const total_setoran = $(this).find("#total_setoran").val();
            const kode_cabang = $(this).find("#kode_cabang").val();
            const keterangan = $(this).find("#keterangan").val();
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Tanggal  Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });

                return false;
            } else if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Cabang Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_cabang").focus();
                    },
                });
                return false;
            } else if (total_setoran == "" || total_setoran === '0') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Total Setoran Tidak Boleh 0',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#total_setoran").focus();
                    },
                });

                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Keterangan Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#keterangan").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }

        });
    });
</script>

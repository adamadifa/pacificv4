<form action="{{ route('sakasbesar.store') }}" method="POST" id="formSaldoawalkasbesar">
    @csrf
    <input type="hidden" name="cekgetsaldo" id="cekgetsaldo" value="0">
    @hasanyrole($roles_show_cabang)
        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" select2="select2Kodecabang"
            upperCase="true" />
    @endhasanyrole
    <div class="form-group mb-3">
        <select name="bulan" id="bulan" class="form-select">
            <option value="">Bulan</option>
            @foreach ($list_bulan as $d)
                <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="tahun" id="tahun" class="form-select">
            <option value="">Tahun</option>
            @for ($t = $start_year; $t <= date('Y'); $t++)
                <option value="{{ $t }}">{{ $t }}</option>
            @endfor
        </select>
    </div>
    <div class="form-group mb-3">
        <a class="btn btn-success w-100" href="#" id="getSaldo"><i class="ti ti-moneybag me-1"></i>Get Saldo</a>
    </div>
    <x-input-with-icon label="Uang Kertas" name="uang_kertas" align="right" money="true" icon="ti ti-moneybag" readonly />
    <x-input-with-icon label="Uang Logam" name="uang_logam" align="right" money="true" icon="ti ti-moneybag" readonly />
    <x-input-with-icon label="Transfer" name="transfer" align="right" money="true" icon="ti ti-moneybag" readonly />
    <x-input-with-icon label="Giro" name="giro" align="right" money="true" icon="ti ti-moneybag" readonly />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formSaldoawalkasbesar");
        $(".flatpickr-date").flatpickr();
        $(".money").maskMoney();

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

        function buttonDisable() {
            $("#btnSimpan, #getSaldo").prop('disabled', true);
            $("#btnSimpan, #getSaldo").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        function buttonEnable() {
            $("#btnSimpan,#getSaldo").prop('disabled', false);
            $("#btnSimpan").html(`<i class="ti ti-send me-1"></i>Submit`);
            $("#getSaldo").html(`<i class="ti ti-moneybag me-1"></i>Get Saldo`);
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

        function getsaldo() {
            const kode_cabang = form.find("#kode_cabang").val();
            const bulan = form.find("#bulan").val();
            const tahun = form.find("#tahun").val();
            if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_cabang").focus();
                    },
                });
                return false;
            } else if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#bulan").focus();
                    },
                });
                return false;
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tahun").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
                form.find("#cekgetsaldo").val(1);
                $.ajax({
                    type: 'POST',
                    url: '/sakasbesar/getsaldo',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun,
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(response) {
                        form.find("#uang_kertas").val(convertToRupiah(response.data.uang_kertas));
                        form.find("#uang_logam").val(convertToRupiah(response.data.uang_logam));
                        form.find("#transfer").val(convertToRupiah(response.data.transfer));
                        form.find("#giro").val(convertToRupiah(response.data.giro));
                        buttonEnable();
                    }
                });
            }
        }

        $("#getSaldo").click(function(e) {
            getsaldo();
        });

        form.find("#kode_cabang,#bulan,#tahun").change(function() {
            form.find("#cekgetsaldo").val(0);
        });

        form.submit(function() {
            const cekgetsaldo = form.find("#cekgetsaldo").val();
            if (cekgetsaldo === '0') {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Get Saldo Terlebih Dahulu !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#getsaldo").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>

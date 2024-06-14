<form action="#" method="POST" id="formSaldoawalkasbesar">
    @csrf
    @hasanyrole($roles_show_cabang)
        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" select2="select2Kodecabang" upperCase="true" />
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
        <a class="btn btn-primary w-100" href="#" id="getSaldo"><i class="ti ti-moneybag me-1"></i>Get Saldo</a>
    </div>
    <x-input-with-icon label="Uang Kertas" name="uang_kertas" align="right" money="true" icon="ti ti-moneybag" />
    <x-input-with-icon label="Uang Logam" name="uang_kertas" align="right" money="true" icon="ti ti-moneybag" />
    <x-input-with-icon label="Transfer" name="transfer" align="right" money="true" icon="ti ti-moneybag" />
    <x-input-with-icon label="Giro" name="giro" align="right" money="true" icon="ti ti-moneybag" />
</form>
<script>
    $(function() {
        const form = $("#formSaldoawalkasbesar");
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
                    success: function(respond) {

                    }
                });
            }
        }

        $("#getSaldo").click(function(e) {
            getsaldo();
        });

    });
</script>

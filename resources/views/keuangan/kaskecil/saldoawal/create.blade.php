<form action="{{ route('sakaskecil.store') }}" id="formSaldoawalkaskecil" method="POST">
    @csrf
    <input type="hidden" name="cekgetsaldo" id="cekgetsaldo" value="0">
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
        <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
            <option value="">Pilih Cabang</option>
            @foreach ($cabang as $d)
                <option value="{{ $d->kode_cabang }}">{{ $d->nama_cabang }}</option>
            @endforeach
        </select>
    </div>
    <div class="row mb-3">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <x-input-with-icon label="Jumlah" name="jumlah" icon="ti ti-moneybag" align="right" readonly="true" />
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <a href="#" id="getsaldo" class="btn btn-success">Get Saldo</a>
        </div>
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formSaldoawalkaskecil");

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
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        form.find("#kode_cabang,#bulan,#tahun").change(function() {
            form.find("#cekgetsaldo").val(0);
        });

        $("#getsaldo").click(function() {
            const bulan = form.find("#bulan").val();
            const tahun = form.find("#tahun").val();
            const kode_cabang = form.find("#kode_cabang").val();
            if (bulan == "") {
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
            } else if (kode_cabang == "") {
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
            } else {
                $.ajax({
                    type: 'POST',
                    url: '/sakaskecil/getsaldo',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun,
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(response) {
                        console.log(response);
                        if (response.data.ceksaldo == 0) {
                            form.find("#jumlah").prop('readonly', false);
                            form.find("#jumlah").maskMoney();
                            form.find("#jumlah").focus();
                            form.find("#cekgetsaldo").val(1);
                        } else if (response.data.ceksaldobulanlalu == 0 && response.data
                            .ceksaldobulanini == 0) {
                            Swal.fire({
                                title: "Oops!",
                                text: "Saldo Bulan Sebelumnya Belum Di Set !",
                                icon: "warning",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    form.find("#bulan").focus();
                                },
                            });
                        } else {
                            form.find("#cekgetsaldo").val(1);
                            form.find("#jumlah").val(convertToRupiah(response.data.saldo));
                            form.find("#jumlah").prop('readonly', true);
                        }
                    }
                });
            }
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

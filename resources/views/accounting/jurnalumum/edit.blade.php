<form action="{{ route('jurnalumum.update', Crypt::encrypt($jurnalumum->kode_ju)) }}" id="formJurnalumum" method="POST">
    @csrf
    @method('PUT')
    <x-input-with-icon label="Tanggal" name="tanggal" datepicker="flatpickr-date" icon="ti ti-calendar" :value="$jurnalumum->tanggal" />
    <div class="form-group mb-3">
        <select name="kode_akun" id="kode_akun" class="form-select select2Kodeakun">
            <option value="">Akun</option>
            @foreach ($coa as $d)
                <option value="{{ $d->kode_akun }}" {{ $jurnalumum->kode_akun == $d->kode_akun ? 'selected' : '' }}>{{ $d->kode_akun }} -
                    {{ $d->nama_akun }}</option>
            @endforeach
        </select>
    </div>
    <x-input-with-icon label="Keterangan" name="keterangan" icon="ti ti-file-description" :value="$jurnalumum->keterangan" />
    <x-input-with-icon label="Jumlah" name="jumlah" align="right" icon="ti ti-moneybag" :value="formatAngka($jurnalumum->jumlah)" numberFormat="true" />
    <div class="form-group mb-3">
        <select name="debet_kredit" id="debet_kredit" class="form-select">
            <option value="">Debet / Kredit</option>
            <option value="D" {{ $jurnalumum->debet_kredit == 'D' ? 'selected' : '' }}>Debet</option>
            <option value="K" {{ $jurnalumum->debet_kredit == 'K' ? 'selected' : '' }}>Kredit</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="kode_peruntukan" id="kode_peruntukan" class="form-select">
            <option value="">Peruntukan</option>
            <option value="MP" {{ $jurnalumum->kode_peruntukan == 'MP' ? 'selected' : '' }}>MP</option>
            <option value="PC" {{ $jurnalumum->kode_peruntukan == 'PC' ? 'selected' : '' }}>PACIFIC</option>
        </select>
    </div>
    <div class="form-group mb-3" id="cabang">
        <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
            <option value="">Pilih Cabang</option>
            @foreach ($cabang as $d)
                <option value="{{ $d->kode_cabang }}" {{ $jurnalumum->kode_cabang == $d->kode_cabang ? 'selected' : '' }}>
                    {{ textUpperCase($d->nama_cabang) }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formJurnalumum");
        $(".flatpickr-date").flatpickr();

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        const select2Kodeakun = $('.select2Kodeakun');
        if (select2Kodeakun.length) {
            select2Kodeakun.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih  Kode Akun',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
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

        easyNumberSeparator({
            selector: '.number-separator',
            separator: '.',
            decimalSeparator: ',',
        });
        form.find("#cabang").hide();

        function loadkodecabang() {
            const kode_peruntukan = form.find("#kode_peruntukan").val();
            if (kode_peruntukan == "PC") {
                form.find("#cabang").show();
            } else {
                form.find("#cabang").hide();
            }
        }

        loadkodecabang();
        $("#kode_peruntukan").change(function(e) {
            loadkodecabang();
        });

        form.submit(function() {
            const tanggal = form.find("#tanggal").val();
            const kode_akun = form.find("#kode_akun").val();
            const keterangan = form.find("#keterangan").val();
            const jumlah = form.find("#jumlah").val();
            const debet_kredit = form.find("#debet_kredit").val();
            const kode_peruntukan = form.find("#kode_peruntukan").val();
            const kode_cabang = form.find("#kode_cabang").val();

            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#tanggal").focus();
                    },
                });

                return false;
            } else if (kode_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kode Akun harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_akun").focus();
                    },
                });

                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#keterangan").focus();
                    },
                });

                return false;
            } else if (jumlah == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#jumlah").focus();
                    },
                });

                return false;
            } else if (debet_kredit == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Debet/Kredit harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#debet_kredit").focus();
                    },
                });

                return false;
            } else if (kode_peruntukan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Peruntukan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_peruntukan").focus();
                    },
                });

                return false;
            } else if (kode_peruntukan == "PC" && kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_cabang").focus();
                    },
                });

                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>

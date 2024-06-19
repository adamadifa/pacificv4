<form action="{{ route('ledger.update', Crypt::encrypt($ledger->no_bukti)) }}" method="POST" id="formLedger">
    @method('PUT')
    <input type="hidden" id="cektutuplaporan">
    @csrf
    <div class="form-group mb-4">
        <select name="kode_bank" id="kode_bank" class="form-select select2Kodebank">
            <option value="">Pilih Bank</option>
            @foreach ($bank as $d)
                <option value="{{ $d->kode_bank }}" {{ $ledger->kode_bank == $d->kode_bank ? 'selected' : '' }}>{{ $d->nama_bank }}
                    {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}</option>
            @endforeach
        </select>
    </div>
    <div class="divider text-start">
        <div class="divider-text">
            <i class="ti ti-file-description me-2"></i>
        </div>
    </div>
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" :value="$ledger->tanggal" />
    <x-input-with-icon label="Pelanggan" name="pelanggan" icon="ti ti-user" :value="$ledger->pelanggan" />
    <x-textarea label="Keterangan" name="keterangan" :value="$ledger->keterangan"></x-textarea>
    <x-input-with-icon label="Jumlah" name="jumlah" icon="ti ti-moneybag" align="right" money="true" :value="formatAngka($ledger->jumlah)" />
    <div class="form-group mb-3">
        <select name="kode_akun" id="kode_akun" class="form-select select2Kodeakun">
            <option value="">Pilih Kode Akun</option>
            @foreach ($coa as $d)
                <option value="{{ $d->kode_akun }}" {{ $ledger->kode_akun == $d->kode_akun ? 'selected' : '' }}>{{ $d->kode_akun }}
                    {{ $d->nama_akun }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group row mb-3">
        <div class="col-6">
            <select name="debet_kredit" id="debet_kredit" class="form-select">
                <option value="">Debet / Kredit</option>
                <option value="D" {{ $ledger->debet_kredit == 'D' ? 'selected' : '' }}>Debet</option>
                <option value="K" {{ $ledger->debet_kredit == 'K' ? 'selected' : '' }}>Kredit</option>
            </select>
        </div>
        <div class="col-6">
            <select name="kode_peruntukan" id="kode_peruntukan" class="form-select">
                <option value="">Peruntukan</option>
                <option value="MP" {{ $ledger->kode_peruntukan == 'MP' ? 'selected' : '' }}>MP</option>
                <option value="PC" {{ $ledger->kode_peruntukan == 'PC' ? 'selected' : '' }}>PACIFIC</option>
            </select>
        </div>
    </div>
    <div class="form-group mb-3" id="ket_peruntukan">
        <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
            <option value="">Pilih Cabang</option>
            @foreach ($cabang as $d)
                <option value="{{ $d->kode_cabang }}" {{ $ledger->keterangan_peruntukan == $d->kode_cabang ? 'selected' : '' }}>
                    {{ textUpperCase($d->nama_cabang) }}</option>
            @endforeach
        </select>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="form-check mt-3 mb-3">
                <input class="form-check-input agreement" name="aggrement" value="aggrement" type="checkbox" value="" id="defaultCheck3"
                    {{ $ledger->aggrement ? 'checked' : '' }}>
                <label class="form-check-label" for="defaultCheck3"> Yakin Akan Disimpan ? </label>
            </div>
            <div class="form-group" id="saveButton">
                <button class="btn btn-primary w-100" type="submit" id="btnSimpan">
                    <ion-icon name="send-outline" class="me-1"></ion-icon>
                    Submit
                </button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formLedger");
        $(".flatpickr-date").flatpickr();
        $(".money").maskMoney();

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        function loadketperuntukan() {
            const kode_peruntukan = form.find("#kode_peruntukan").val();
            if (kode_peruntukan == "PC") {
                form.find("#ket_peruntukan").show();
            } else {
                form.find("#ket_peruntukan").hide();
            }
        }


        function cektutuplaporan() {
            const tanggal = form.find("#tanggal").val();
            $.ajax({
                type: 'POST',
                url: '/tutuplaporan/cektutuplaporan',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    jenis_laporan: "ledger"
                },
                cache: false,
                success: function(response) {
                    form.find("#cektutuplaporan").val(response);
                }
            });
        }

        form.find("#tanggal").change(function() {
            cektutuplaporan();
        });
        loadketperuntukan();
        $("#kode_peruntukan").change(function() {
            loadketperuntukan();
        });
        const select2Kodebank = $('.select2Kodebank');
        if (select2Kodebank.length) {
            select2Kodebank.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih  Bank',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
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


        form.find("#saveButton").hide();

        form.find('.agreement').change(function() {
            if (this.checked) {
                form.find("#saveButton").show();
            } else {
                form.find("#saveButton").hide();
            }
        });

        form.submit(function(e) {
            const kode_bank = form.find("#kode_bank").val();
            const tanggal = form.find("#tanggal").val();
            const pelanggan = form.find("#pelanggan").val();
            const keterangan = form.find("#keterangan").val();
            const dataCoa = form.find("#kode_akun :selected").select2(this.data);
            const kode_akun = $(dataCoa).val();
            const nama_akun = $(dataCoa).text();
            const jumlah = form.find("#jumlah").val();
            const debet_kredit = form.find("#debet_kredit").val();
            const kode_peruntukan = form.find("#kode_peruntukan").val();
            const kredit = debet_kredit == 'K' ? jumlah : '';
            const debet = debet_kredit == 'D' ? jumlah : '';
            const kode_cabang = form.find("#kode_cabang").val();
            const cektutuplaporan = form.find("#cektutuplaporan").val();
            let bgperuntukan = "";
            if (kode_peruntukan == "MP") {
                bgperuntukan = "bg-success text-white";
            } else if (kode_peruntukan == "PC") {
                bgperuntukan = "bg-info text-white";
            } else {
                bgperuntukan = "";
            }

            // Pisahkan tanggal menjadi tahun, bulan, dan hari
            let bagianTanggal = tanggal.split("-");
            let bagianTahun = bagianTanggal[0].substr(-2);
            // Susun kembali bagian-bagian tanggal dalam format d-m-y
            let tanggalledger = `${parseInt(bagianTanggal[2])}-${parseInt(bagianTanggal[1])}-${bagianTahun}`;
            if (kode_bank == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih Bank Terlebih Dahulu !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_bank").focus();
                    },
                });

                return false;
            } else if (cektutuplaporan > 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Laporan Periode Ini Sudah Ditutup !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
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
            } else if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#keterangan").focus();
                    },

                });
                return false;
            } else if (kode_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kode Akun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_akun").focus();
                    },

                });
                return false;
            } else if (debet_kredit == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Debet / Kredit Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#debet_kredit").focus();
                    },

                });
                return false;
            } else if (jumlah == "") {
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
            } else if (kode_peruntukan == "PC" && kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan Peruntukan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
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

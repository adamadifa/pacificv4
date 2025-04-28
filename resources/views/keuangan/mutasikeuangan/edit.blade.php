<form action="{{ route('mutasikeuangan.update', ['id' => Crypt::encrypt($mutasikeuangan->id)]) }}" method="POST"
    id="formLedger">

    @csrf
    @method('PUT')
    <div class="form-group mb-4">
        <select name="kode_bank" id="kode_bank" class="form-select select2Kodebank">
            <option value="">Pilih Bank</option>
            @foreach ($bank as $d)
                <option value="{{ $d->kode_bank }}" {{ $mutasikeuangan->kode_bank == $d->kode_bank ? 'selected' : '' }}>
                    {{ $d->nama_bank }}
                    {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}</option>
            @endforeach
        </select>
    </div>
    <div class="divider text-start">
        <div class="divider-text">
            <i class="ti ti-file-description me-2"></i>
        </div>
    </div>
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date"
        :value="$mutasikeuangan->tanggal" />
    <x-input-with-icon label="No. Bukti" name="no_bukti" icon="ti ti-barcode" :value="$mutasikeuangan->no_bukti" />

    <x-textarea label="Keterangan" name="keterangan" :value="$mutasikeuangan->keterangan" />
    <x-input-with-icon label="Jumlah" name="jumlah" icon="ti ti-moneybag" align="right" money="true"
        :value="formatAngka($mutasikeuangan->jumlah)" />

    <div class="form-group mb-3">
        <select name="debet_kredit" id="debet_kredit" class="form-select">
            <option value="">Debet / Kredit</option>
            <option value="D" {{ $mutasikeuangan->debet_kredit == 'D' ? 'selected' : '' }}>Debet</option>
            <option value="K" {{ $mutasikeuangan->debet_kredit == 'K' ? 'selected' : '' }}>Kredit</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="kode_kategori" id="kode_kategori" class="form-select">
            <option value="">Kategori</option>
            @foreach ($kategori as $d)
                <option value="{{ $d->kode_kategori }}"
                    {{ $mutasikeuangan->kode_kategori == $d->kode_kategori ? 'selected' : '' }}>{{ $d->nama_kategori }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group" id="saveButton">
        <button class="btn btn-primary w-100" type="submit" id="btnSimpan">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Simpan
        </button>
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

        form.submit(function(e) {
            const kode_bank = form.find("#kode_bank").val();
            const tanggal = form.find("#tanggal").val();
            const keterangan = form.find("#keterangan").val();
            const jumlah = form.find("#jumlah").val();
            const debet_kredit = form.find("#debet_kredit").val();
            if (!kode_bank) {
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
            } else {
                buttonDisable();
            }
        });
    });
</script>

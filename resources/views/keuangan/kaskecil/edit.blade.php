<form action="{{ route('kaskecil.update', Crypt::encrypt($kaskecil->id)) }}" method="POST" id="formeditKaskecil">
    @csrf
    @method('PUT')
    {{-- {{ $kaskecil->id }} --}}
    {{-- {{ $kaskecil->kode_klaim }}
    {{ var_dump($kaskecil->kode_klaim) }} --}}
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabangedit" {{ !empty($kaskecil->kode_klaim) ? 'readonly' : '' }}>
                <option value="">Pilih Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}" {{ $kaskecil->kode_cabang == $d->kode_cabang ? 'selected' : '' }}>
                        {{ textuppercase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endhasanyrole
    <x-input-with-icon label="No. Bukti" name="no_bukti" value="{{ $kaskecil->no_bukti }}" icon="ti ti-barcode"
        non_aktif="{{ !empty($kaskecil->kode_klaim) ? 'true' : 'false' }}" />
    <x-input-with-icon label="Tanggal" name="tanggal" datepicker="flatpickr-date" value="{{ $kaskecil->tanggal }}" icon="ti ti-calendar" />
    <x-input-with-icon label="Keterangan" name="keterangan" value="{{ $kaskecil->keterangan }}" icon="ti ti-file-description" />
    <x-input-with-icon label="Jumlah" name="jumlah" value="{{ formatAngka($kaskecil->jumlah) }}" icon="ti ti-moneybag" align="right" money="true"
        non_aktif="{{ !empty($kaskecil->kode_klaim) ? 'true' : 'false' }}" />
    <div class="form-group mb-3">
        <select name="kode_akun" id="kode_akun" class="form-select select2Kodeakunedit">
            <option value="">Pilih Akun</option>
            @foreach ($coa as $d)
                <option value="{{ $d->kode_akun }}" {{ $kaskecil->kode_akun == $d->kode_akun ? 'selected' : '' }}>
                    {{ $d->kode_akun }} - {{ $d->nama_akun }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="debet_kredit_edit" id="debet_kredit" class="form-select" {{ !empty($kaskecil->kode_klaim) ? 'disabled' : '' }}>
            <option value="D" {{ $kaskecil->debet_kredit == 'D' ? 'selected' : '' }}>DEBET</option>
            <option value="K" {{ $kaskecil->debet_kredit == 'K' ? 'selected' : '' }}>KREDIT</option>
        </select>
        <input type="hidden" name="debet_kredit" value="{{ $kaskecil->debet_kredit }}">
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" type="submit" id="btnSimpan">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>
<script>
    $(document).ready(function() {
        const formeditKaskecil = $('#formeditKaskecil');
        $(".flatpickr-date").flatpickr();
        $(".money").maskMoney();
        const select2Kodecabangedit = $(".select2Kodecabangedit");
        if (select2Kodecabangedit.length) {
            select2Kodecabangedit.select2({
                placeholder: 'Pilih Cabang',
                allowClear: true,
                dropdownParent: select2Kodecabangedit.parent()
            });
        }

        const select2Kodeakunedit = $(".select2Kodeakunedit");
        if (select2Kodeakunedit.length) {
            select2Kodeakunedit.select2({
                placeholder: 'Pilih Akun',
                allowClear: true,
                dropdownParent: select2Kodeakunedit.parent()
            });
        }


        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }


        formeditKaskecil.submit(function(e) {
            const kode_cabang = formeditKaskecil.find("#kode_cabang").val();
            const kode_akun = formeditKaskecil.find("#kode_akun").val();
            const keterangan = formeditKaskecil.find("#keterangan").val();
            const debet_kredit = formeditKaskecil.find("#debet_kredit").val();
            const jumlah = formeditKaskecil.find("#jumlah").val();
            if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formeditKaskecil.find("#kode_cabang").focus();
                    },
                });
                return false;
            } else if (kode_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Akun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formeditKaskecil.find("#kode_akun").focus();
                    }
                });
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formeditKaskecil.find("#keterangan").focus();
                    }
                });
                return false;
            } else if (jumlah == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formeditKaskecil.find("#jumlah").focus();
                    },

                });
                return false;
            } else if (debet_kredit == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Debet/Kredit Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formeditKaskecil.find("#debet_kredit").focus();
                    }
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>

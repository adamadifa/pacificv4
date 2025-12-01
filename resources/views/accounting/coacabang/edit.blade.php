<form action="{{ route('coacabang.update', Crypt::encrypt($coaCabang->id)) }}" method="POST" id="formCoaCabang">
    @csrf
    @method('PUT')
    <div class="form-group mb-3">
        <label for="kode_cabang" class="form-label">Cabang</label>
        <select name="kode_cabang" id="kode_cabang" class="form-select select2Cabang">
            <option value="">Pilih Cabang</option>
            @foreach ($cabang as $d)
                <option value="{{ $d->kode_cabang }}" @selected($coaCabang->kode_cabang == $d->kode_cabang)>
                    {{ $d->kode_cabang }} - {{ $d->nama_cabang }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="kode_akun" class="form-label">Kode Akun</label>
        <select name="kode_akun" id="kode_akun" class="form-select select2Kodeakun">
            <option value="">Pilih Kode Akun</option>
            @foreach ($coa as $d)
                <option value="{{ $d->kode_akun }}" @selected($coaCabang->kode_akun == $d->kode_akun)>
                    {{ $d->kode_akun }} - {{ $d->nama_akun }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formCoaCabang");

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        const select2Cabang = $('.select2Cabang');
        if (select2Cabang.length) {
            select2Cabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
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
                    placeholder: 'Pilih Kode Akun',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        form.submit(function() {
            const kode_cabang = form.find("#kode_cabang").val();
            const kode_akun = form.find("#kode_akun").val();

            if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_cabang").focus();
                    },
                });
                return false;
            } else if (kode_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kode Akun harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_akun").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>

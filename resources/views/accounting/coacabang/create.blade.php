<form action="{{ route('coacabang.store') }}" method="POST" id="formCoaCabang">
    @csrf
    <input type="hidden" name="kode_cabang" value="{{ request('kode_cabang') }}">
    <div class="form-group mb-3">
        <label class="form-label">Cabang</label>
        <input type="text" class="form-control" 
            value="{{ $cabang_selected ? $cabang_selected->kode_cabang . ' - ' . $cabang_selected->nama_cabang : request('kode_cabang') }}" 
            readonly>
    </div>
    <div class="form-group mb-3">
        <label for="kode_akun" class="form-label">Kode Akun</label>
        <select name="kode_akun" id="kode_akun" class="form-select select2Kodeakun">
            <option value="">Pilih Kode Akun</option>
            @foreach ($coa as $d)
                <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} - {{ $d->nama_akun }}</option>
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
            const kode_cabang = form.find("input[name='kode_cabang']").val();
            const kode_akun = form.find("#kode_akun").val();

            if (!kode_cabang || kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang harus dipilih terlebih dahulu!",
                    icon: "warning",
                    showConfirmButton: true,
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

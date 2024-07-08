<form action="{{ route('coa.update', Crypt::encrypt($coa->kode_akun)) }}" method="POST" id="formCoa">
    @csrf
    @method('PUT')
    <x-input-with-icon label="Kode Akun" name="kode_akun" icon="ti ti-barcode" :value="$coa->kode_akun ?? ''" />
    <x-input-with-icon label="Nama Akun" name="nama_akun" icon="ti ti-file-description" :value="$coa->nama_akun ?? ''" />
    <div class="form-group mb-3">
        <select name="sub_akun" id="sub_akun" class="form-select select2Kodeakun">
            <option value="">Parent Account</option>
            @foreach ($sub_akun as $d)
                <option value="{{ $d->kode_akun }}" @selected($coa->sub_akun == $d->kode_akun)>{{ $d->kode_akun }} - {{ $d->nama_akun }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="kode_kategori" id="kode_kategori" class="form-select">
            <option value="">Kategori</option>
            @foreach ($kategori as $d)
                <option value="{{ $d->kode_kategori }}" @selected($coa->kode_kategori == $d->kode_kategori)>{{ $d->nama_kategori }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formCoa");

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
                    placeholder: 'Parent Account',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $("#kode_akun").mask('0-0000');
        form.submit(function() {
            const kode_akun = form.find("#kode_akun").val();
            const nama_akun = form.find("#nama_akun").val();
            const sub_akun = form.find("#sub_akun").val();
            const kode_kategori = form.find("#kode_kategori").val();

            if (kode_akun == "") {
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
            } else if (nama_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Nama Akun harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#nama_akun").focus();
                    },
                });
                return false;
            } else if (sub_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Sub Akun harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#sub_akun").focus();
                    },
                });
                return false;
            } else if (kode_kategori == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kategori harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_kategori").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>

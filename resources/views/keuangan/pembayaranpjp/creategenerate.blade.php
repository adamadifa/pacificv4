<form action="{{ route('pembayaranpjp.generatepjp') }}" method="POST" id="formGeneratepjp">
    @csrf
    <div class="form-group mb-3">
        <select name="bulan" id="bulan" class="form-select">
            <option value="">Bulan</option>
            @foreach ($list_bulan as $d)
                <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }} value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="tahun" id="tahun" class="form-select">
            <option value="">Tahun</option>
            @for ($t = $start_year; $t <= date('Y'); $t++)
                <option
                    @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                    @else
                    {{ date('Y') == $t ? 'selected' : '' }} @endif
                    value="{{ $t }}">{{ $t }}</option>
            @endfor
        </select>
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formGeneratepjp");

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        form.submit(function(e) {
            const bulan = $(this).find("#bulan");
            const tahun = $(this).find("#tahun");

            if (bulan.val() == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        bulan.focus();
                    },
                });
                return false;
            } else if (tahun.val() == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        tahun.focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>

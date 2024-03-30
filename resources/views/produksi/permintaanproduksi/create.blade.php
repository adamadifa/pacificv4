<style>
    .form-table {
        width: 100%;
        border: 0px;
    }

    .form-table:focus {
        outline: none;
    }
</style>
<form action="{{ route('permintaanproduksi.store') }}" method="POST" id="frmCreatePermintaanproduksi">
    @csrf
    <select name="kode_oman" id="kode_oman" class="form-select">
        <option value="">Pilih Oman</option>
        @foreach ($oman as $d)
            <option value="{{ Crypt::encrypt($d['kode_oman']) }}">{{ $d['kode_oman'] }} - {{ $namabulan[$d->bulan] }}
                {{ $d->tahun }}</option>
        @endforeach
    </select>
    <div class="row mt-2">
        <div class="col-12">
            <table class="table table-bordered" id="mytable">
                <thead class="table-dark">
                    <tr>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Oman</th>
                        <th>Stok Gudang</th>
                        <th>Buffer</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="loadoman"></tbody>
            </table>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <button class="btn btn-primary w-100" type="submit" name="submit"><i
                    class="ti ti-send me-1"></i>Submit</button>
        </div>
    </div>
</form>
<script>
    $(function() {
        $("#kode_oman").change(function() {
            const kode_oman = $(this).val() == "" ? null : $(this).val();
            $("#loadoman").load("/oman/" + kode_oman + "/getoman");
        });

        $("#frmCreatePermintaanproduksi").submit(function(e) {
            const kode_oman = $("#kode_oman").val();
            if (kode_oman == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih Oman Terlebih Dahulu !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#kode_oman").focus();
                    },
                });
                return false;
            }
        });

    });
</script>

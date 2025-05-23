<form action="{{ route('pencairanprogramikatan.storepelanggan', Crypt::encrypt($kode_pencairan)) }}"
    id="formprosesPelanggan" method="POST">

    @csrf
    <table class="table table-bordered ">
        <thead class="table-dark">
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Kode Pelanggan</th>
                <th rowspan="2">Nama Pelanggan</th>
                <th class="text-center" rowspan="2">Target</th>
                <th colspan="3" class="text-center">Budget</th>
                <th colspan="3" class="text-center">Realisasi</th>
                <th colspan="3" class="text-center">Reward</th>
                <th rowspan="2"><i class="ti ti-file-dollar"></i></th>
                <th rowspan="2"><i class="ti ti-square-check"></i></th>
            </tr>
            <tr>
                <th>SMM</th>
                <th>RSM</th>
                <th>GM</th>
                <th>Tunai</th>
                <th>Kredit</th>
                <th>Total</th>
                <th>Tunai</th>
                <th>Kredit</th>
                <th>Total</th>
            </tr>

        </thead>
        <tbody id="loadpenjualanpelanggan">

        </tbody>
    </table>

    <div class="row mt-3">
        <div class="col">
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block w-100" id="btnSimpan"><i
                        class="ti ti-send me-1 "></i>Proses</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        function loadpenjualanpelanggan() {
            let kode_pencairan = "{{ Crypt::encrypt($kode_pencairan) }}";
            $("#loadpenjualanpelanggan").html("<tr class='text-center'><td colspan='8'>Loading...</td></tr>");
            $.ajax({
                type: 'POST',
                url: '/pencairanprogramikatan/getpelanggan',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_pencairan: kode_pencairan
                },
                cache: false,
                success: function(data) {
                    $("#loadpenjualanpelanggan").html(data);
                }
            })
        }

        loadpenjualanpelanggan();

        $("#formprosesPelanggan").submit(function(e) {
            $("#btnSimpan").attr("disabled", true);
            $("#btnSimpan").html(`
                <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Loading..
            `);
        });

    });
</script>

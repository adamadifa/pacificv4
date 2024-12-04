<table class="table table-bordered ">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Kode Pelanggan</th>
            <th>Nama Pelanggan</th>
            <th class="text-center">Target</th>
            <th>Realisasi</th>
            <th>Reward</th>
            <th>Total Reward</th>
            <th>#</th>
        </tr>

    </thead>
    <tbody id="loadpenjualanpelanggan">

    </tbody>
</table>
<div class="row mt-3">
    <div class="col">
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block" id="btnSimpan"><i class="ti ti-plus me-1"></i>Tambahkan Semua</button>
        </div>
    </div>
</div>
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


    });
</script>

<table class="table table-bordered table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Kode Pelanggan</th>
            <th rowspan="2">Nama Pelanggan</th>
            <th rowspan="2">Qty</th>
            <th colspan="2">Diskon</th>
            <th rowspan="2">Cashback</th>
            <th rowspan="2">#</th>
        </tr>
        <tr>
            <th>Reguler</th>
            <th>Kumulatif</th>
        </tr>
    </thead>
    <tbody id="loadpenjualanpelanggan">

    </tbody>
</table>
<script>
    $(document).ready(function() {
        function loadpenjualanpelanggan() {
            let kode_pencairan = "{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}";
            $("#loadpenjualanpelanggan").html("<tr class='text-center'><td colspan='8'>Loading...</td></tr>");
            $.ajax({
                type: 'POST',
                url: '/pencairanprogram/getpelanggan',
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

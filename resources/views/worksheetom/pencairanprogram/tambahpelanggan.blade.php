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
            let kode_program = "{{ $kode_program }}";
            let bulan = "{{ $bulan }}";
            let tahun = "{{ $tahun }}";
            let kode_cabang = "{{ $kode_cabang }}";
            $("#loadpenjualanpelanggan").html("<tr class='text-center'><td colspan='8'>Loading...</td></tr>");
            $.ajax({
                type: 'POST',
                url: '/pencairanprogram/getpelanggan',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_program: kode_program,
                    bulan: bulan,
                    tahun: tahun,
                    kode_cabang: kode_cabang
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

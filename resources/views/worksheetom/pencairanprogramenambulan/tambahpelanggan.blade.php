<form action="{{ route('pencairanprogramenambulan.storepelanggan', Crypt::encrypt($kode_pencairan)) }}" id="formprosesPelanggan" method="POST">

    @csrf
    <div class="table-responsive">
        <table class="table table-bordered " style="width: 120%">
            <thead class="table-dark" style="font-size: 11px;">
                <tr>
                    <th rowspan="3">No</th>
                    <th rowspan="3">Kode</th>
                    <th rowspan="3">Nama Pelanggan</th>
                    <th class="text-center" colspan="{{ 6 * 3 }}">Realisasi</th>
                    <th class="text-center" colspan="5" rowspan="2">Total</th>
                    <th rowspan="3"><i class="ti ti-file-dollar"></i></th>
                    <th rowspan="3"><i class="ti ti-square-check"></i></th>
                </tr>
                <tr>
                    @for ($i = date('m', strtotime($start_date)); $i <= date('m', strtotime($end_date)); $i++)
                        <th class="text-center" colspan="3">{{ getMonthName($i) }}</th>
                    @endfor
                </tr>
                <tr>
                    @for ($i = date('m', strtotime($start_date)); $i <= date('m', strtotime($end_date)); $i++)
                        <th>T</th>
                        <th>R</th>
                        <th>RW</th>
                    @endfor
                    <th>T</th>
                    <th>R</th>
                    <th>RW</th>
                    <th>RC</th>
                    <th>RN</th>
                </tr>
            </thead>
            <tbody id="loadpenjualanpelanggan" style="font-size: 10px;">

            </tbody>
        </table>
    </div>

    <div class="row mt-3">
        <div class="col">
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block w-100" id="btnSimpan"><i class="ti ti-send me-1 "></i>Proses</button>
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
                url: '/pencairanprogramenambulan/getpelanggan',
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

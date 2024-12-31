<div class="nav-align-top nav-tabs mb-6">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link waves-effect active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-home"
                aria-controls="navs-top-home" aria-selected="false" tabindex="-1">TOP 14 Hari</button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link waves-effect " role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-profile"
                aria-controls="navs-top-profile" aria-selected="true">TOP 30 Hari</button>
        </li>

    </ul>
    <div class="tab-content p-0 bg-transparent">
        <div class="tab-pane fade active show" id="navs-top-home" role="tabpanel">
            <table class="table table-bordered table-striped table-hover mt-2">
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
            <div class="row mt-2">
                <div class="col">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block" id="btnSimpan"><i class="ti ti-plus me-1"></i>Tambahkan
                            Semua</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade " id="navs-top-profile" role="tabpanel">
            <table class="table table-bordered table-striped table-hover mt-2">
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
                <tbody id="loadpenjualanpelanggantop30">

                </tbody>
            </table>
        </div>

    </div>
</div>

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

        function loadpenjualanpelanggantop30() {
            let kode_pencairan = "{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}";
            $("#loadpenjualanpelanggantop30").html("<tr class='text-center'><td colspan='8'>Loading...</td></tr>");
            $.ajax({
                type: 'POST',
                url: '/pencairanprogram/getpelanggantop30',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_pencairan: kode_pencairan
                },
                cache: false,
                success: function(data) {
                    $("#loadpenjualanpelanggantop30").html(data);
                }
            })
        }

        loadpenjualanpelanggan();
        loadpenjualanpelanggantop30();


    });
</script>

<form action="{{ route('pencairanprogramikatan2026.storepelanggan', Crypt::encrypt($kode_pencairan)) }}"
    id="formprosesPelanggan" method="POST">

    @csrf
    <div class="row" id="loadpenjualanpelanggan">

    </div>

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
            $("#loadpenjualanpelanggan").html("<div class='col-12 text-center'>Loading...</div>");
            $.ajax({
                type: 'POST',
                url: '/pencairanprogramikatan2026/getpelanggan',
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

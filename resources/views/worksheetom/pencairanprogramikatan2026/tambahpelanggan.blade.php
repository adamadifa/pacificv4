<form action="{{ route('pencairanprogramikatan2026.storepelanggan', Crypt::encrypt($kode_pencairan)) }}"
    id="formprosesPelanggan" method="POST">

    @csrf
    <div class="row" id="loadpenjualanpelanggan">

    </div>

    <!-- Floating Action Button for Submit -->
    <div style="position: fixed; bottom: 30px; right: 30px; z-index: 1100; min-width: 180px;">
        <button type="submit" class="btn btn-primary btn-lg shadow-lg w-100" id="btnSimpan" style="border-radius: 50px; padding: 12px 24px; font-weight: bold; border: 2px solid #fff;">
            <i class="ti ti-send me-2"></i>Proses
        </button>
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

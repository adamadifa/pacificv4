<form action="{{ route('ajuanprogramenambulan.storepelanggan', Crypt::encrypt($ajuanprogramikatan->no_pengajuan)) }}"
    method="POST" id="formEditpelanggan" enctype="multipart/form-data">
    @csrf
    <div class="input-group mb-3">
        <input type="hidden" name="kode_pelanggan" id="kode_pelanggan" readonly>
        <input type="hidden" name="no_pengajuan_programikatan" id="no_pengajuan_programikatan" readonly>
        <input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan" readonly
            placeholder="Cari Pelanggan" aria-label="Cari Pelanggan" aria-describedby="nama_pelanggan">
        <a class="btn btn-primary waves-effect" id="kode_pelanggan_search"><i class="ti ti-search text-white"></i></a>
    </div>
    <div class="row mb-3">
        <div class="col" id="gettargetpelanggan"></div>
    </div>

    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        let grandTotaltarget = 0;

        function convertToRupiah(number) {
            if (number) {
                var rupiah = "";
                var numberrev = number
                    .toString()
                    .split("")
                    .reverse()
                    .join("");
                for (var i = 0; i < numberrev.length; i++)
                    if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
                return (
                    rupiah
                    .split("", rupiah.length - 1)
                    .reverse()
                    .join("")
                );
            } else {
                return number;
            }
        }
        const select2Kodepelanggan = $('.select2Kodepelanggan');
        if (select2Kodepelanggan.length) {
            select2Kodepelanggan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Pelanggan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function calculateTargetPerBulan() {
            let totalBulan = $('.targetbulanan').length; // Menghitung jumlah bulan
            let totalTargetString = $('#target').val(); // Mengambil nilai target
            let totalTarget = totalTargetString == "" ? 0 : totalTargetString.replace(/\./g, '');
            let targetPerBulan = Math.floor(parseInt(totalTarget) / parseInt(
                totalBulan)); // Menghitung target per bulan
            console.log(grandTotaltarget);
            let sisa = parseInt(totalTarget) - parseInt(grandTotaltarget);
            // if (sisa > 0) {
            //     $('input[name="target_perbulan[]"]:last').val(convertToRupiah(targetPerBulan + sisa));
            // }
            $('input[name="target_perbulan[]"]').val(convertToRupiah(
                targetPerBulan)); // Mengisi otomatis target per bulan
        }

        function calculateTotalTarget() {
            let total = 0;
            $('input[name="target_perbulan[]"]').each(function() {
                let value = $(this).val().replace(/\./g, '');
                if (!isNaN(value) && value.length != 0) {
                    total += parseFloat(value);
                }
            });
            grandTotaltarget = total;
            $('#gradTotaltarget').text(convertToRupiah(total));
        }

        $('#target').on('keyup keydown change', function() {
            calculateTargetPerBulan();
            calculateTotalTarget();
        });





        $('input[name="target_perbulan[]"]').on('keyup', function() {
            calculateTotalTarget();
        });

        calculateTotalTarget(); // Menjalankan fungsi saat halaman di-load
        calculateTargetPerBulan(); // Menjalankan fungsi saat halaman di-load

        $("#target,#reward,#budget_smm,#budget_rsm,#budget_gm").maskMoney();

        function calculateReward() {
            let budget_smm = $("#budget_smm").val();
            let budget_rsm = $("#budget_rsm").val();
            let budget_gm = $("#budget_gm").val();


            let smm = budget_smm == "" ? 0 : budget_smm.replace(/\./g, '');
            let rsm = budget_rsm == "" ? 0 : budget_rsm.replace(/\./g, '');
            let gm = budget_gm == "" ? 0 : budget_gm.replace(/\./g, '');
            let totalReward = parseInt(smm) + parseInt(rsm) + parseInt(gm);
            $("#reward").val(totalReward);
        }

        $("#budget_smm, #budget_rsm, #budget_gm").on('keyup', function() {
            calculateReward();
        });
    });
</script>

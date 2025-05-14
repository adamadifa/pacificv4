<form action="{{ route('ajuanprogramikatan.storepelanggan', Crypt::encrypt($ajuanprogramikatan->no_pengajuan)) }}"
    method="POST" id="formEditpelanggan" enctype="multipart/form-data">
    @csrf
    {{-- <div class="form-group">
        <select name="kode_pelanggan" id="kode_pelanggan" class="form-select select2Kodepelanggan">
            <option value="">Pilih Pelanggan</option>
            @foreach ($pelanggan as $d)
                <option value="{{ $d->kode_pelanggan }}">{{ $d->kode_pelanggan }} - {{ $d->nama_pelanggan }}</option>
            @endforeach
        </select>
    </div> --}}
    <div class="input-group mb-3">
        <input type="hidden" name="kode_pelanggan" id="kode_pelanggan" readonly>
        <input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan" readonly
            placeholder="Cari Pelanggan" aria-label="Cari Pelanggan" aria-describedby="nama_pelanggan">
        <a class="btn btn-primary waves-effect" id="kode_pelanggan_search"><i class="ti ti-search text-white"></i></a>
    </div>
    <x-input-with-icon label="Qty Rata - rata 3 Bulan Terakhir" name="qty_avg" icon="ti ti-file-description"
        placeholder="Qty Rata - rata 3 Bulan Terakhir" align="right" readonly />
    <div class="row">
        <div class="col" id="gethistoripelangganprogram"></div>
    </div>
    <x-input-with-icon label="Total Target" name="target" icon="ti ti-file-description" placeholder="Target / Bulan"
        align="right" />
    <table class="table table-bordered mb-2" id="targetperbulantable">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Target</th>
            </tr>
        </thead>
        <tbody>
            @php
                $start_date = date('Y-m-d', strtotime($ajuanprogramikatan->periode_dari));
                $end_date = date('Y-m-d', strtotime($ajuanprogramikatan->periode_sampai));
                $current_date = $start_date;
            @endphp
            @while (strtotime($current_date) <= strtotime($end_date))
                <tr class="targetbulanan">
                    <td>
                        <input type="hidden" name="bulan[]" value="{{ date('m', strtotime($current_date)) }}"
                            class="noborder-form">
                        {{ getMonthName(date('m', strtotime($current_date))) }}
                    </td>
                    <td>
                        <input type="hidden" name="tahun[]" value="{{ date('Y', strtotime($current_date)) }}"
                            class="noborder-form">
                        {{ date('Y', strtotime($current_date)) }}
                    </td>
                    <td>
                        <input type="text" name="target_perbulan[]" value="" style="text-align: right"
                            class="noborder-form number-separator">
                    </td>
                </tr>
                @php
                    $current_date = date('Y-m-d', strtotime('+1 month', strtotime($current_date)));
                @endphp
            @endwhile
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">TOTAL</td>
                <td class="text-end" id="gradTotaltarget"></td>
            </tr>
        </tfoot>
    </table>




    <hr class="my-4">
    <div class="form-group">
        <select name="top" id="top" class="form-select">
            <option value="">TOP</option>
            <option value="14">14 Hari</option>
            <option value="30">30 Hari</option>
        </select>
    </div>
    <div class="form-group">
        <select name="periode_pencairan" id="periode_pencairan" class="form-select">
            <option value="">Periode Pencairan</option>
            <option value="1">1 Bulan</option>
            <option value="3">3 Bulan</option>
            <option value="6">6 Bulan</option>
            <option value="12">12 Bulan</option>
        </select>
    </div>
    <div class="form-group">
        <select name="tipe_reward" id="tipe_reward" class="form-select">
            <option value="">Type Reward</option>
            <option value="1">Quantity</option>
            <option value="2">Flat</option>
        </select>
    </div>
    <x-input-with-icon label="Budget SMM" name="budget_smm" icon="ti ti-file-description" placeholder="Budget SMM"
        align="right" />
    <x-input-with-icon label="Budget RSM" name="budget_rsm" icon="ti ti-file-description" placeholder="Budget RSM"
        align="right" />
    <x-input-with-icon label="Budget GM" name="budget_gm" icon="ti ti-file-description" placeholder="Budget GM"
        align="right" />
    <x-input-with-icon label="Reward" name="reward" icon="ti ti-file-description" placeholder="Reward" align="right"
        readonly />
    <div class="form-group mb-3">
        <select name="metode_pembayaran" id="metode_pembayaran" class="form-select">
            <option value="">Metode Pembayaran</option>
            <option value="TN">Tunai</option>
            <option value="TF">Transfer</option>
            {{-- <option value="VC">Voucher</option> --}}
        </select>
    </div>
    <x-input-file name="file_doc" label="Dokumen Kesepakatan" />
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

<form action="{{ route('ajuanprogramikatan.storepelanggan', Crypt::encrypt($ajuanprogramikatan->no_pengajuan)) }}" method="POST" id="formEditpelanggan"
    enctype="multipart/form-data">
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
        <input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan" readonly placeholder="Cari Pelanggan"
            aria-label="Cari Pelanggan" aria-describedby="nama_pelanggan">
        <a class="btn btn-primary waves-effect" id="kode_pelanggan_search"><i class="ti ti-search text-white"></i></a>
    </div>
    <x-input-with-icon label="Qty Rata - rata 3 Bulan Terakhir" name="qty_avg" icon="ti ti-file-description"
        placeholder="Qty Rata - rata 3 Bulan Terakhir" align="right" readonly />
    <div class="row">
        <div class="col" id="gethistoripelangganprogram"></div>
    </div>
    <x-input-with-icon label="Target / Bulan" name="target" icon="ti ti-file-description" placeholder="Target / Bulan" align="right" />
    <x-input-with-icon label="Reward" name="reward" icon="ti ti-file-description" placeholder="Reward" align="right" readonly />
    <hr class="my-4">
    <div class="form-group">
        <select name="top" id="top" class="form-select">
            <option value="">TOP</option>
            <option value="14">14 Hari</option>
            <option value="30">30 Hari</option>
        </select>
    </div>
    <x-input-with-icon label="Budget SMM" name="budget_smm" icon="ti ti-file-description" placeholder="Budget SMM" align="right" />
    <x-input-with-icon label="Budget RSM" name="budget_rsm" icon="ti ti-file-description" placeholder="Budget RSM" align="right" />
    <x-input-with-icon label="Budget GM" name="budget_gm" icon="ti ti-file-description" placeholder="Budget GM" align="right" />
    <div class="form-group mb-3">
        <select name="metode_pembayaran" id="metode_pembayaran" class="form-select">
            <option value="">Metode Pembayaran</option>
            <option value="TN">Tunai</option>
            <option value="TF">Transfer</option>
            <option value="VC">Voucher</option>
        </select>
    </div>
    <x-input-file name="file_doc" label="Dokumen Kesepakatan" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>

<script>
    $(document).ready(function() {
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

        $("#target, #reward,#budget_smm,#budget_rsm,#budget_gm").maskMoney();

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

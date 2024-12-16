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
    <x-input-with-icon label="Target / Bulan" name="target" icon="ti ti-file-description" placeholder="Target / Bulan" align="right" />
    <x-input-with-icon label="Reward" name="reward" icon="ti ti-file-description" placeholder="Reward" align="right" />
    <div class="form-group mb-3">
        <select name="budget" id="budget" class="form-select">
            <option value="">Budget</option>
            <option value="SMM">SMM</option>
            <option value="RSM">RSM</option>
            <option value="GM">GM</option>
        </select>
    </div>
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

        $("#target, #reward").maskMoney();
    });
</script>

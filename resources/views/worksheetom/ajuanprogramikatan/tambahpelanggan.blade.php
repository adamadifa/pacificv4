<form action="{{ route('ajuanprogramikatan.storepelanggan') }}" method="POST" id="formAjuanprogram">
    @csrf
    <div class="form-group">
        <select name="kode_pelanggan" id="kode_pelanggan" class="form-select select2Kodepelanggan">
            <option value="">Pilih Pelanggan</option>
            @foreach ($pelanggan as $d)
                <option value="{{ $d->kode_pelanggan }}">{{ $d->kode_pelanggan }} - {{ $d->nama_pelanggan }}</option>
            @endforeach
        </select>
    </div>
    <x-input-with-icon label="Qty Rata - rata 3 Bulan Terakhir" name="qty_avg" icon="ti ti-file-description"
        placeholder="Qty Rata - rata 3 Bulan Terakhir" align="right" disabled />
    <x-input-with-icon label="Target / Bulan" name="target" icon="ti ti-file-description" placeholder="Target / Bulan" align="right" />
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
    });
</script>

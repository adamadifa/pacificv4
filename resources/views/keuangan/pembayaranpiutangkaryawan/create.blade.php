<form action="{{ route('pembayaranpiutangkaryawan.store') }}" method="POST" id="formPembayaranpituangkaryawan">
    <div class="form-group mb-3">
        <select name="jenis_bayar" id="jenis_bayar" class="form-select">
            <option value="">Jenis Pembayaran</option>
            <option value="1">Potong Gaji</option>
            <option value="2">Potong Komisi</option>
            <option value="3">Titipan Pelanggan</option>
            <option value="4">Lainnya</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="bulan" id="bulan" class="form-select">
            <option value="">Bulan</option>
            @foreach ($list_bulan as $d)
                <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }} value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="tahun" id="tahun" class="form-select">
            <option value="">Tahun</option>
            @for ($t = $start_year; $t <= date('Y'); $t++)
                <option
                    @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                    @else
                    {{ date('Y') == $t ? 'selected' : '' }} @endif
                    value="{{ $t }}">{{ $t }}</option>
            @endfor
        </select>
    </div>
    <x-input-with-icon label="Jumlah" name="jumlah" icon="ti ti-moneybag" align="right" money="true" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        $(".money").maskMoney();
    });
</script>

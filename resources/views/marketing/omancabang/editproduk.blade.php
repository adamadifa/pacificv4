<input type="hidden" name="minggu_ke_edit_omancabang" value="{{ $minggu_ke }}">
<div class="row">
    <div class="col-12">
        <table class="table">
            <tr>
                <th>Kode Produk</th>
                <td>{{ $produk->kode_produk }}</td>
            </tr>
            <tr>
                <th>Nama Produk</th>
                <td>{{ $produk->nama_produk }}</td>
            </tr>
            <tr>
                <th>Minggu Ke</th>
                <td>{{ $minggu_ke }}</td>
            </tr>
            <tr>
                <th>Bulan</th>
                <td>{{ $namabulan[$bulan] }}</td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td>{{ $tahun }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Cabang</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                    <tr>
                        <td>
                            {{ $d->nama_cabang }}
                            <input type="hidden" name="kode_oman_edit_omancabang" value="{{ $d->kode_oman }}">
                            <input type="hidden" name="kode_produk_edit_omancabang" value="{{ $d->kode_produk }}">
                        </td>
                        <td class="text-end">
                            <input type="text" id="jumlah" name="jumlah_edit_omancabang"
                                class="jumlah text-end form-oman number-separator" placeholder="0" autocomplete="false"
                                aria-autocomplete="list" value="{{ formatAngka($d->jumlah) }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<div class="row mt-2">
    <div class="col-12">
        <button class="btn btn-primary w-100" id="updateomanCabang"><i class="ti ti-send me-1"></i>Update</button>
    </div>
</div>
<script>
    $(function() {
        easyNumberSeparator({
            selector: '.number-separator',
            separator: '.',
            decimalSeparator: ',',
        });
    });
</script>

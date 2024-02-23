@foreach ($detailbufferstok as $d)
    <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
    <tr>
        <td>{{ $d->kode_produk }}</td>
        <td>{{ $d->nama_produk }}</td>
        <td style="width: 20%">
            <input type="text" name="jumlah_buffer[]" value="{{ $d->jumlah_buffer }}" style="text-align: right"
                class="form-control">
        </td>
        <td style="width: 20%">
            <input type="text" name="jumlah_max[]" value="{{ $d->jumlah_max }}" style="text-align: right"
                class="form-control">
        </td>
    </tr>
@endforeach

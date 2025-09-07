<div class="row mb-2">
    <div class="col">
        <a href="#" id="checkall" class="btn btn-primary">Check All</a>
        <a href="#" id="uncheckall" class="btn btn-danger">Uncheck All</a>
    </div>
</div>
<div class="row">
    <div class="col">
        <form action="{{ route('pelanggan.updatenonaktifpelanggan') }}" method="post">
            @csrf
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>No</th>
                        <th>Kode Pelanggan</th>
                        <th>Nama Pelanggan</th>
                        <th>Penjualan Terakhir</th>
                        <th>Lama</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pelanggan as $key => $d)
                        <tr>
                            <td><input type="checkbox" class="checkpelanggan" name="kode_pelanggan[]" value="{{ $d->kode_pelanggan }}"></td>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ $d->nama_pelanggan }}</td>
                            <td>{{ $d->tanggal }}</td>
                            <td>{{ konversiHariKeBulan($d->jmlhari) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-danger w-100">Nonaktifkan</button>
        </form>

    </div>
</div>
<script>
    $(function() {
        $("#checkall").click(function() {
            $(".checkpelanggan").prop("checked", true);
        });
        $("#uncheckall").click(function() {
            $(".checkpelanggan").prop("checked", false);
        });
    });
</script>

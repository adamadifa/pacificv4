<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>No. Permintaan</th>
                <td>{{ $pk->no_permintaan }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ DateToIndo($pk->tanggal) }}</td>
            </tr>
            <tr>
                <th>Cabang</th>
                <td>{{ textUpperCase($pk->nama_cabang) }}</td>
            </tr>
            @if (!empty($pk->kode_salesman))
                <tr>
                    <th>Salesman</th>
                    <td>{{ $pk->nama_salesman }}</td>
                </tr>
            @endif
            <tr>
                <th>Keterangan</th>
                <td>{{ $pk->keterangan }}</td>
            </tr>
        </table>

    </div>
</div>
<div class="row mt-2">
    <div class="col">
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $d)
                    <tr>
                        <td>{{ $d->kode_produk }}</td>
                        <td>{{ $d->nama_produk }}</td>
                        <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<hr>
<h4>Data Surat Jalan</h4>
@if ($suratjalan != null)
    <table class="table">
        <tr>
            <th>No. Surat Jalan</th>
            <td>{{ $suratjalan->no_mutasi }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>{{ DateToIndo($suratjalan->tanggal) }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @if ($d->status_surat_jalan == 0)
                    <span class="badge bg-danger">Belum Diterima Cabang</span>
                @elseif($d->status_surat_jalan == 1)
                    <span class="badge bg-success">Sudah Diterima Cabang</span>
                @elseif($d->status_surat_jalan == 2)
                    <span class="badge bg-info">Transit Out</span>
                @endif
            </td>
        </tr>
    </table>
    <table class="table table-hover table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detailsuratjalan as $d)
                <tr>
                    <td>{{ $d->kode_produk }}</td>
                    <td>{{ $d->nama_produk }}</td>
                    <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <span class="alert-icon text-warning me-2">
            <i class="ti ti-info-circle ti-xs"></i>
        </span>
        Data Surat Jalan Belum Tersdia !
    </div>
@endif

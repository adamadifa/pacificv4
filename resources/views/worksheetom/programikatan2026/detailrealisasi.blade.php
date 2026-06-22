<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>No.</th>
            <th>No. Faktur</th>
            <th>Tanggal</th>
            <th>Nama Produk</th>
            <th class="text-end">Qty (Pcs)</th>
            <th class="text-end">Isi/Dus</th>
            <th class="text-end">Qty (Dus)</th>
            <th>Jenis Transaksi</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_pcs = 0;
            $total_dus_equivalent = 0;
        @endphp
        @forelse ($detailpenjualan as $d)
            @php
                $total_pcs += $d->jumlah;
                $dus_equivalent = $d->jumlah / $d->isi_pcs_dus;
                $total_dus_equivalent += $dus_equivalent;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $d->no_faktur }}</td>
                <td>{{ formatIndo($d->tanggal) }}</td>
                <td>{{ $d->nama_produk }}</td>
                <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                <td class="text-end">{{ formatAngka($d->isi_pcs_dus) }}</td>
                <td class="text-end">{{ round($dus_equivalent, 2) }}</td>
                <td class="text-center">
                    @if ($d->jenis_transaksi == 'T')
                        <span class="badge bg-success">Tunai</span>
                    @else
                        <span class="badge bg-danger">Kredit</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data realisasi.</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot class="table-dark">
        <tr>
            <td colspan="4">TOTAL</td>
            <td class="text-end">{{ formatAngka($total_pcs) }}</td>
            <td></td>
            <td class="text-end">{{ round($total_dus_equivalent, 2) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>

@php
    $recap = [];
    foreach ($detailpenjualan as $d) {
        $key = $d->kode_produk;
        if (!isset($recap[$key])) {
            $recap[$key] = [
                'nama_produk' => $d->nama_produk,
                'isi_pcs_dus' => $d->isi_pcs_dus,
                'total_pcs' => 0,
            ];
        }
        $recap[$key]['total_pcs'] += $d->jumlah;
    }
@endphp

<div class="card mt-4">
    <div class="card-header bg-primary text-white py-2">
        <h5 class="card-title mb-0" style="font-size: 1.1rem; font-weight: 600;">Rekapitulasi Per Produk (Akumulasi Dus)</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">No.</th>
                    <th>Nama Produk</th>
                    <th class="text-end" style="width: 150px;">Total Pcs</th>
                    <th class="text-end" style="width: 120px;">Isi / Dus</th>
                    <th class="text-end" style="width: 150px;">Total Dus</th>
                    <th class="text-end" style="width: 150px;">Sisa Pcs</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grand_total_dus = 0;
                    $grand_total_pcs = 0;
                @endphp
                @forelse ($recap as $key => $r)
                    @php
                        $dus = floor($r['total_pcs'] / $r['isi_pcs_dus']);
                        $sisa_pcs = $r['total_pcs'] % $r['isi_pcs_dus'];
                        $grand_total_dus += $dus;
                        $grand_total_pcs += $r['total_pcs'];
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $r['nama_produk'] }}</td>
                        <td class="text-end font-weight-bold">{{ formatAngka($r['total_pcs']) }} Pcs</td>
                        <td class="text-end">{{ formatAngka($r['isi_pcs_dus']) }}</td>
                        <td class="text-end text-success font-weight-bold" style="font-weight: 600;">{{ formatAngka($dus) }} Dus</td>
                        <td class="text-end text-muted">{{ formatAngka($sisa_pcs) }} Pcs</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-3">Tidak ada data rekapitulasi.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="table-dark">
                <tr>
                    <td colspan="2">TOTAL REALISASI DUS (DIBULATKAN)</td>
                    <td class="text-end">{{ formatAngka($grand_total_pcs) }} Pcs</td>
                    <td></td>
                    <td class="text-end text-warning" style="font-size: 1.1rem; font-weight: bold;">{{ formatAngka($grand_total_dus) }} Dus</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

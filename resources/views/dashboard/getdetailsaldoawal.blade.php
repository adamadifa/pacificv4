<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="text-white" style="background-color: #002e65;">
            <tr>
                <th class="text-center text-white" style="width: 5%">No</th>
                <th class="text-white">Bank</th>
                <th class="text-end text-white">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse ($details as $d)
                @php $total += $d->jumlah; @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <div class="fw-bold">{{ $d->nama_bank }}</div>
                        <small class="text-muted">{{ $d->no_rekening }}</small>
                    </td>
                    <td class="text-end fw-bold">{{ formatAngka($d->jumlah) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot class="table-light">
            <tr>
                <th colspan="2" class="text-center">TOTAL</th>
                <th class="text-end fw-bold">{{ formatAngka($total) }}</th>
            </tr>
        </tfoot>
    </table>
</div>

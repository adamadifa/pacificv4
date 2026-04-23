@if ($piutang->isEmpty())
    <tr>
        <td colspan="4" class="text-center text-danger">Tidak ada data piutang untuk periode ini.</td>
    </tr>
@else
    @foreach ($piutang as $d)
        <tr>
            <td>
                <input type="hidden" class="no_faktur" value="{{ $d->no_faktur }}">
                {{ $d->no_faktur }}
            </td>
            <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
            <td>{{ $d->nama_pelanggan }}</td>
            <td class="text-end">
                <input type="hidden" class="jumlah-piutang" value="{{ $d->saldo_akhir }}">
                {{ number_format($d->saldo_akhir, 0, ',', '.') }}
            </td>
        </tr>
    @endforeach
@endif

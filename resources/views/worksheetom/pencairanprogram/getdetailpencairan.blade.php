@php
    $total_cashback = 0;
    $metode_pembayaran = [
        'TN' => 'Tunai',
        'TF' => 'Transfer',
        'VC' => 'Voucher',
    ];
@endphp
@foreach ($detailpencairan as $d)
    @php
        $cashback = $d->diskon_kumulatif - $d->diskon_reguler;
        $total_cashback += $cashback;
    @endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->kode_pelanggan }}</td>
        <td>{{ $d->nama_pelanggan }}</td>
        <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
        <td class="text-end">{{ formatAngka($d->diskon_reguler) }}</td>
        <td class="text-end">{{ formatAngka($d->diskon_kumulatif) }}</td>
        <td class="text-end">{{ formatAngka($cashback) }}</td>
        <td>{{ !empty($d->metode_bayar) ? $metode_pembayaran[$d->metode_bayar] : '-' }}</td>
        <td>{{ $d->no_rekening }}</td>
        <td>{{ $d->pemilik_rekening }}</td>
        <td>{{ $d->bank }}</td>
        <td>
            <div class="d-flex">
                <a href="#" class="btnDetailfaktur me-1" kode_pelanggan="{{ $d['kode_pelanggan'] }}">
                    <i class="ti ti-file-description"></i>
                </a>
                <a href="#" kode_pelanggan = "{{ $d->kode_pelanggan }}" class="deletedetailpencairan">
                    <i class="ti ti-trash text-danger"></i>
                </a>
            </div>

        </td>
    </tr>
@endforeach
<tr class="table-dark">
    <td colspan="6" class="text-end">GRAND TOTAL CASHBACK</td>
    <td class="text-end">{{ formatAngka($total_cashback) }}</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>

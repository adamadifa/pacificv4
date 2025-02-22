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
    <tr class="{{ $d->top == 30 ? 'bg-warning text-dark' : '' }}">
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
                {{-- @can('pencairanprogramikt.upload')
                    <a href="#" kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}" kode_pelanggan="{{ Crypt::encrypt($d->kode_pelanggan) }}"
                        class="btnUpload">
                        <i class="ti ti-upload text-primary"></i>
                    </a>
                @endcan --}}
                <a href="#" class="btnDetailfaktur me-1" kode_pelanggan="{{ $d['kode_pelanggan'] }}" top="{{ $d->top }}">
                    <i class="ti ti-file-description"></i>
                </a>
                @if ($user->hasRole(['operation manager', 'sales marketing manager']) && $d->rsm == null)
                    <a href="#" kode_pelanggan = "{{ $d->kode_pelanggan }}" class="deletedetailpencairan">
                        <i class="ti ti-trash text-danger"></i>
                    </a>
                @elseif ($user->hasRole('regional sales manager') && $d->gm == null)
                    <a href="#" kode_pelanggan = "{{ $d->kode_pelanggan }}" class="deletedetailpencairan">
                        <i class="ti ti-trash text-danger"></i>
                    </a>
                @elseif($user->hasRole('gm marketing') && $d->direktur == null)
                    <a href="#" kode_pelanggan = "{{ $d->kode_pelanggan }}" class="deletedetailpencairan">
                        <i class="ti ti-trash text-danger"></i>
                    </a>
                @elseif($user->hasRole(['super admin', 'direktur']))
                    <a href="#" kode_pelanggan = "{{ $d->kode_pelanggan }}" class="deletedetailpencairan">
                        <i class="ti ti-trash text-danger"></i>
                    </a>
                @endif
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
    <td></td>
</tr>

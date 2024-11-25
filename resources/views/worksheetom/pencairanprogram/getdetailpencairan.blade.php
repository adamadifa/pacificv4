@foreach ($detailpencairan as $d)
    @php
        $cashback = $d->diskon_kumulatif - $d->diskon_reguler;
    @endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->kode_pelanggan }}</td>
        <td>{{ $d->nama_pelanggan }}</td>
        <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
        <td class="text-end">{{ formatAngka($d->diskon_reguler) }}</td>
        <td class="text-end">{{ formatAngka($d->diskon_kumulatif) }}</td>
        <td class="text-end">{{ formatAngka($cashback) }}</td>
        <td>
            <a href="#" kode_pelanggan = "{{ $d->kode_pelanggan }}" class="deletedetailpencairan">
                <i class="ti ti-trash text-danger"></i>
            </a>
        </td>
    </tr>
@endforeach

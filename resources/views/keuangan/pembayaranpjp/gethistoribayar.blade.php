@foreach ($historibayar as $d)
    <tr>
        <td>{{ $d->no_bukti }}</td>
        <td>{{ formatIndo($d->tanggal) }}</td>
        <td class="text-end fw-bold">{{ formatAngka($d->jumlah) }}</td>
        <td>
            @can('pembayaranpjp.delete')
                @if (empty($d->kode_potongan))
                    <a href="#" class="btnDeletebayar" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                        <i class="ti ti-trash text-danger"></i>
                    </a>
                @endif
            @endcan


        </td>
    </tr>
@endforeach

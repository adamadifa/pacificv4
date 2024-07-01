@foreach ($historibayar as $d)
    <tr>
        <td>{{ $d->no_bukti }}</td>
        <td>{{ formatIndo($d->tanggal) }}</td>
        <td class="text-end fw-bold">{{ formatAngka($d->jumlah) }}</td>
        <td>
            @if ($d->jenis_bayar == '1')
                Potong Gaji
            @elseif ($d->jenis_bayar == '2')
                Potong Komisi
            @elseif ($d->jenis_bayar == '3')
                Titipan Pelanggan
            @else
                Lainnya
            @endif
        </td>
        <td>
            <a href="#" class="btnDeletebayar" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                <i class="ti ti-trash text-danger"></i>
            </a>
        </td>
    </tr>
@endforeach

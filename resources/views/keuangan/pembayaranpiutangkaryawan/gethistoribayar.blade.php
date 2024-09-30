@foreach ($historibayar as $d)
    <tr>
        <td>{{ $d->no_bukti }}</td>
        @php
            $tgl = explode('-', $d->tanggal);
            $bulan = $tgl[1];
            $tahun = $tgl[0];

            if ($bulan == 1) {
                $bln = 12;
                $thn = $tahun - 1;
            } else {
                $bln = $bulan - 1;
                $thn = $tahun;
            }
            $tanggal = $thn . '-' . $bln . '-01';
        @endphp
        <td>{{ date('d-m-Y', strtotime($tanggal)) }}</td>
        {{-- <td>{{ formatIndo($tanggal) }}</td> --}}
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

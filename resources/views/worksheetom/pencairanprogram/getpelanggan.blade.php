@foreach ($detail as $d)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d['kode_pelanggan'] }}</td>
        <td>{{ $d['nama_pelanggan'] }}</td>
        <td class="text-end">{{ formatAngka($d['jml_dus']) }}</td>
        <td class="text-end">{{ formatAngka($d['diskon_reguler']) }}</td>
        <td class="text-end">{{ formatAngka($d['diskon_kumulatif']) }}</td>
        <td class="text-end">{{ formatAngka($d['cashback']) }}</td>
        <td>
            <a href="#" class="btnDetailfaktur" kode_pelanggan="{{ $d['kode_pelanggan'] }}" kategori_diskon="{{ $kategori_diskon }}">
                <i class="ti ti-file-description"></i>
            </a>
        </td>

    </tr>
@endforeach

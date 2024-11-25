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
            <div class="d-flex">
                <a href="#" class="btnDetailfaktur me-2" kode_pelanggan="{{ $d['kode_pelanggan'] }}">
                    <i class="ti ti-file-description"></i>
                </a>
                <a href="#" class="btnTambahpelanggan" kode_pelanggan="{{ $d['kode_pelanggan'] }}">
                    <i class="ti ti-plus"></i>
                </a>
            </div>
        </td>

    </tr>
@endforeach

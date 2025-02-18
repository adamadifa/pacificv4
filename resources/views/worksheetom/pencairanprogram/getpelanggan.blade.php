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

                <a href="#" class="btnDetailfaktur me-2" kode_pelanggan="{{ $d['kode_pelanggan'] }}" top="{{ $top }}">
                    <i class="ti ti-file-description"></i>
                </a>
                <form action="#" class="formAddpelanggan" method="POST">
                    @csrf
                    <input type="hidden" name="kode_pelanggan" value="{{ $d['kode_pelanggan'] }}">
                    <input type="hidden" name="jml_dus" value="{{ $d['jml_dus'] }}">
                    <input type="hidden" name="diskon_reguler" value="{{ $d['diskon_reguler'] }}">
                    <input type="hidden" name="diskon_kumulatif" value="{{ $d['diskon_kumulatif'] }}">
                    <input type="hidden" name="kode_pencairan" value="{{ $kode_pencairan }}">
                    <input type="hidden" name="top" value="{{ $top }}">
                    <button class="btnTambahfaktur" style="border: none; background-color: transparent"><i
                            class="ti ti-plus text-success"></i></button>
                </form>
            </div>
        </td>

    </tr>
@endforeach

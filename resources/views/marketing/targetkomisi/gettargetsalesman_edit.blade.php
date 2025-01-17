@foreach ($detail as $d)
    <tr>
        <td>
            <input type="hidden" name="kode_salesman[]" value="{{ $d->kode_salesman }}">
            {{ $d->kode_salesman }}
        </td>
        <td>{{ $d->nik }}</td>
        <td>
            @php
                $end_date = $targetkomisi->tahun . '-' . $targetkomisi->bulan . '-01';
                $masakerja = hitungMasakerja($d->tanggal_masuk, $end_date);
            @endphp
            @if (!empty($d->tanggal_masuk))
                {{ $masakerja['tahun'] }} Tahun {{ $masakerja['bulan'] }} Bulan
            @endif
        </td>
        <td style="width: 30%">{{ $d->nama_salesman }}</td>
        @foreach ($produk as $p)
            @php
                $rata_rata_penjualan = $d->{"penjualan_$p->kode_produk"} / $p->isi_pcs_dus / 3;
                $jml_penjualan_tigabulan = $d->{"penjualan_tiga_bulan_$p->kode_produk"} / $p->isi_pcs_dus;
                $jml_penjualan_duabulan = $d->{"penjualan_dua_bulan_$p->kode_produk"} / $p->isi_pcs_dus;
                $jml_penjualan_lastbulan = $d->{"penjualan_last_bulan_$p->kode_produk"} / $p->isi_pcs_dus;
            @endphp
            <td class="text-end bg-success text-white"> {{ formatAngka($rata_rata_penjualan) }}</td>
            <td class="text-end bg-info text-white">{{ formatAngka($jml_penjualan_tigabulan) }}</td>
            <td class="text-end bg-info text-white">{{ formatAngka($jml_penjualan_duabulan) }}</td>
            <td class="text-end bg-info text-white">{{ formatAngka($jml_penjualan_lastbulan) }}</td>
            <td>
                <input type="text" class="noborder-form text-end money" value="{{ formatAngka($d->{"target_$p->kode_produk"}) }}"
                    name="{{ $p->kode_produk }}[]">
            </td>
        @endforeach
    </tr>
@endforeach
<script>
    $(".money").maskMoney();
</script>

@foreach ($detail as $d)
    <tr>
        <td>
            <input type="hidden" name="kode_salesman[]" value="{{ $d->kode_salesman }}">
            {{ $d->kode_salesman }}
        </td>
        <td>{{ $d->nik }}</td>
        <td>{{ $d->nama_salesman }}</td>
        <td>
            @php
                $end_date = $targetkomisi->tahun . '-' . $targetkomisi->bulan . '-01';
                $masakerja = hitungMasakerja($d->tanggal_masuk, $end_date);
            @endphp
            @if (!empty($d->tanggal_masuk))
                {{ $masakerja['tahun'] }} Tahun {{ $masakerja['bulan'] }} Bulan
            @endif
        </td>

        @foreach ($produk as $p)
            @php
                $rata_rata_penjualan = $d->{"penjualan_$p->kode_produk"} / $p->isi_pcs_dus / 3;
                $jml_penjualan_tigabulan = $d->{"penjualan_tiga_bulan_$p->kode_produk"} / $p->isi_pcs_dus;
                $jml_penjualan_duabulan = $d->{"penjualan_dua_bulan_$p->kode_produk"} / $p->isi_pcs_dus;
                $jml_penjualan_lastbulan = $d->{"penjualan_last_bulan_$p->kode_produk"} / $p->isi_pcs_dus;
                $jml_last_target = $d->{"target_last_$p->kode_produk"};
            @endphp
            <td class="text-end bg-success text-white"> {{ formatAngka($rata_rata_penjualan) }}</td>
            <td class="text-end bg-info text-white">{{ formatAngka($jml_penjualan_tigabulan) }}</td>
            <td class="text-end bg-info text-white">{{ formatAngka($jml_penjualan_duabulan) }}</td>
            <td class="text-end bg-info text-white">{{ formatAngka($jml_penjualan_lastbulan) }}</td>
            <td class="text-end bg-primary text-white">{{ formatAngka($jml_last_target) }}</td>
            <td class="text-end">
                {{ formatAngka($d->{"target_$p->kode_produk"}) }}
                <input type="hidden" class="noborder-form text-end money target_awal" value="{{ formatAngka($d->{"target_awal_$p->kode_produk"}) }}"
                    name="target_awal_{{ $p->kode_produk }}[]">
            </td>
            <td>
                <input type="text" class="noborder-form text-end money target_rsm" value="{{ formatAngka($d->{"target_rsm_$p->kode_produk"}) }}"
                    name="rsm_{{ $p->kode_produk }}[]"
                    {{ $level_user == 'regional sales manager' || $level_user == 'super admin' ? '' : 'disabled' }}>
            </td>
            <td>
                <input type="text" class="noborder-form text-end money target_gm" value="{{ formatAngka($d->{"target_gm_$p->kode_produk"}) }}"
                    name="gm_{{ $p->kode_produk }}[]" {{ $level_user == 'gm marketing' || $level_user == 'super admin' ? '' : 'disabled' }}>
            </td>
            <td>
                <input type="text" class="noborder-form text-end money target_dirut"
                    value="{{ formatAngka($d->{"target_dirut_$p->kode_produk"}) }}" name="dirut_{{ $p->kode_produk }}[]"
                    {{ $level_user == 'direktur' || $level_user == 'super admin' ? '' : 'disabled' }}>
            </td>
            <td class="text-end">
                {{-- {{ formatAngka($d->{"target_$p->kode_produk"}) }} --}}
                <input type="text" class="noborder-form text-end target_akhir" value="{{ formatAngka($d->{"target_$p->kode_produk"}) }}"
                    name="{{ $p->kode_produk }}[]" readonly>
            </td>
        @endforeach
    </tr>
@endforeach
<script>
    $(function() {
        $(".money").maskMoney();
        $(".target_rsm, .target_gm, .target_dirut").on("keyup keydown", function() {
            var target_rsm = $(this).closest("tr").find(".target_rsm").val();
            var target_gm = $(this).closest("tr").find(".target_gm").val();
            var target_dirut = $(this).closest("tr").find(".target_dirut").val();
            var target_awal = $(this).closest("tr").find(".target_awal").val();

            var target_akhir = !target_rsm ? target_awal : !target_gm ? target_rsm : !target_dirut ? target_gm : target_dirut;
            $(this).closest("tr").find(".target_akhir").val(target_akhir);
        });
    });
</script>
{{-- <script>
    $(".table-modal").freezeTable({
        'scrollable': true,
        'columnNum': 3,
        'shadow': true,
    });
</script> --}}

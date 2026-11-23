@foreach ($detail as $d)
    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
        <!-- 1. Kode Salesman (Sticky/Frozen Left-0) -->
        <td class="sticky left-0 z-10 w-[80px] min-w-[80px] max-w-[80px] bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800 py-1.5 px-3 text-center border-b border-r border-slate-100 dark:border-slate-800">
            <input type="hidden" name="kode_salesman[]" value="{{ $d->kode_salesman }}">
            <span class="inline-flex items-center px-2 py-0.5 rounded-md font-mono-num font-bold text-[11px] bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                {{ $d->kode_salesman }}
            </span>
        </td>

        <!-- 2. Nama Salesman (Sticky/Frozen Left-80px with Elevation Shadow) -->
        <td class="sticky left-[80px] z-10 w-[180px] min-w-[180px] max-w-[180px] bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800 py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 border-r-2 border-slate-200 dark:border-slate-700 shadow-[3px_0_6px_-2px_rgba(0,0,0,0.12)] truncate" title="{{ $d->nama_salesman }}">
            {{ $d->nama_salesman }}
        </td>

        <!-- 3. NIK -->
        <td class="py-1.5 px-3 text-center font-mono-num text-slate-500 text-[11px] border-b border-r border-slate-100 dark:border-slate-800 w-24 min-w-[90px]">
            {{ $d->nik ?? '-' }}
        </td>

        <!-- 4. Masa Kerja -->
        <td class="py-1.5 px-3 text-center text-[10.5px] text-slate-500 font-mono-num border-b border-r border-slate-100 dark:border-slate-800 w-28 min-w-[100px]">
            @php
                $end_date = $targetkomisi->tahun . '-' . $targetkomisi->bulan . '-01';
                $masakerja = hitungMasakerja($d->tanggal_masuk, $end_date);
            @endphp
            @if (!empty($d->tanggal_masuk))
                {{ $masakerja['tahun'] }} Thn {{ $masakerja['bulan'] }} Bln
            @else
                -
            @endif
        </td>

        <!-- Matrix Produk Data & Inputs -->
        @foreach ($produk as $p)
            @php
                $isiDus = $p->isi_pcs_dus > 0 ? $p->isi_pcs_dus : 1;
                $rata_rata_penjualan = $d->{"penjualan_$p->kode_produk"} / $isiDus / 3;
                $jml_penjualan_tigabulan = $d->{"penjualan_tiga_bulan_$p->kode_produk"} / $isiDus;
                $jml_penjualan_dua_bulan = $d->{"penjualan_dua_bulan_$p->kode_produk"} / $isiDus;
                $jml_penjualan_lastbulan = $d->{"penjualan_last_bulan_$p->kode_produk"} / $isiDus;
                $jml_last_target = $d->{"target_last_$p->kode_produk"};
            @endphp

            <!-- 1. AVG -->
            <td class="py-1.5 px-2 text-right font-mono-num font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50/50 dark:bg-emerald-950/20 border-b border-slate-100 dark:border-slate-800">
                {{ formatAngka($rata_rata_penjualan) }}
            </td>

            <!-- 2. Realisasi 3 Bulan Lalu -->
            <td class="py-1.5 px-2 text-right font-mono-num text-sky-700 dark:text-sky-400 bg-sky-50/40 dark:bg-sky-950/20 border-b border-slate-100 dark:border-slate-800">
                {{ formatAngka($jml_penjualan_tigabulan) }}
            </td>

            <!-- 3. Realisasi 2 Bulan Lalu -->
            <td class="py-1.5 px-2 text-right font-mono-num text-sky-700 dark:text-sky-400 bg-sky-50/40 dark:bg-sky-950/20 border-b border-slate-100 dark:border-slate-800">
                {{ formatAngka($jml_penjualan_dua_bulan) }}
            </td>

            <!-- 4. Realisasi Bulan Lalu -->
            <td class="py-1.5 px-2 text-right font-mono-num text-sky-700 dark:text-sky-400 bg-sky-50/40 dark:bg-sky-950/20 border-b border-slate-100 dark:border-slate-800">
                {{ formatAngka($jml_penjualan_lastbulan) }}
            </td>

            <!-- 5. Last Target -->
            <td class="py-1.5 px-2 text-right font-mono-num font-semibold text-indigo-700 dark:text-indigo-400 bg-indigo-50/40 dark:bg-indigo-950/20 border-b border-slate-100 dark:border-slate-800">
                {{ formatAngka($jml_last_target) }}
            </td>

            <!-- 6. Target Awal -->
            <td class="py-1.5 px-2 text-right font-mono-num font-bold text-slate-700 dark:text-slate-300 border-b border-slate-100 dark:border-slate-800">
                {{ formatAngka($d->{"target_awal_$p->kode_produk"}) }}
                <input type="hidden" class="noborder-form text-right money target_awal t_awal_{{ $p->kode_produk }}"
                    value="{{ formatAngka($d->{"target_awal_$p->kode_produk"}) }}" name="target_awal_{{ $p->kode_produk }}[]"
                    kode_produk="{{ $p->kode_produk }}">
            </td>

            <!-- 7. Target RSM -->
            <td class="py-1.5 px-1.5 text-center border-b border-slate-100 dark:border-slate-800">
                @php $canEditRsm = ($level_user == 'regional sales manager' || $level_user == 'super admin'); @endphp
                <input type="text"
                    class="target_rsm t_rsm_{{ $p->kode_produk }} w-20 h-[28px] px-2 text-right font-mono-num font-bold text-xs rounded border transition {{ $canEditRsm ? 'money border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500' : 'border-transparent bg-slate-100 dark:bg-slate-800/60 text-slate-500 cursor-not-allowed' }}"
                    value="{{ formatAngka($d->{"target_rsm_$p->kode_produk"}) }}" name="rsm_{{ $p->kode_produk }}[]"
                    {{ $canEditRsm ? '' : 'readonly' }} kode_produk="{{ $p->kode_produk }}" placeholder="0">
            </td>

            <!-- 8. Target GM -->
            <td class="py-1.5 px-1.5 text-center border-b border-slate-100 dark:border-slate-800">
                @php $canEditGm = ($level_user == 'gm marketing' || $level_user == 'super admin'); @endphp
                <input type="text"
                    class="target_gm t_gm_{{ $p->kode_produk }} w-20 h-[28px] px-2 text-right font-mono-num font-bold text-xs rounded border transition {{ $canEditGm ? 'money border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500' : 'border-transparent bg-slate-100 dark:bg-slate-800/60 text-slate-500 cursor-not-allowed' }}"
                    value="{{ formatAngka($d->{"target_gm_$p->kode_produk"}) }}" name="gm_{{ $p->kode_produk }}[]"
                    {{ $canEditGm ? '' : 'readonly' }} kode_produk="{{ $p->kode_produk }}" placeholder="0">
            </td>

            <!-- 9. Target DIRUT -->
            <td class="py-1.5 px-1.5 text-center border-b border-slate-100 dark:border-slate-800">
                @php $canEditDirut = ($level_user == 'direktur' || $level_user == 'super admin'); @endphp
                <input type="text"
                    class="target_dirut t_dirut_{{ $p->kode_produk }} w-20 h-[28px] px-2 text-right font-mono-num font-bold text-xs rounded border transition {{ $canEditDirut ? 'money border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500' : 'border-transparent bg-slate-100 dark:bg-slate-800/60 text-slate-500 cursor-not-allowed' }}"
                    value="{{ formatAngka($d->{"target_dirut_$p->kode_produk"}) }}" name="dirut_{{ $p->kode_produk }}[]"
                    {{ $canEditDirut ? '' : 'readonly' }} kode_produk="{{ $p->kode_produk }}" placeholder="0">
            </td>

            <!-- 10. Target Akhir -->
            <td class="py-1.5 px-1.5 text-center bg-amber-50/50 dark:bg-amber-950/20 border-b border-slate-100 dark:border-slate-800">
                <input type="text"
                    class="target_akhir_{{ $p->kode_produk }} w-20 h-[28px] px-2 text-right font-mono-num font-black text-xs rounded border-0 bg-transparent text-amber-600 dark:text-amber-400 cursor-default"
                    value="{{ formatAngka($d->{"target_$p->kode_produk"}) }}" name="{{ $p->kode_produk }}[]" readonly>
            </td>
        @endforeach
    </tr>
@endforeach

<script>
    $(function() {
        if (typeof $.fn.maskMoney !== 'undefined') {
            $(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0,
                allowZero: true
            });
        }

        $(".target_rsm, .target_gm, .target_dirut").on("keyup keydown change", function() {
            var kode_produk = $(this).attr("kode_produk");
            var $row = $(this).closest("tr");

            var target_rsm = $row.find(".t_rsm_" + kode_produk).val();
            var target_gm = $row.find(".t_gm_" + kode_produk).val();
            var target_dirut = $row.find(".t_dirut_" + kode_produk).val();
            var target_awal = $row.find(".t_awal_" + kode_produk).val();

            var target_akhir = target_dirut ? target_dirut : (target_gm ? target_gm : (target_rsm ? target_rsm : target_awal));

            $row.find(".target_akhir_" + kode_produk).val(target_akhir);
        });
    });
</script>

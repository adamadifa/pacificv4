@php
    $level_user = auth()->user()->roles->pluck('name')[0] ?? '';
    $namaBulan = !empty($list_bulan) ? (collect($list_bulan)->firstWhere('kode_bulan', $targetkomisi->bulan)['nama_bulan'] ?? $targetkomisi->bulan) : $targetkomisi->bulan;
@endphp

<form action="{{ route('targetkomisi.update', Crypt::encrypt($targetkomisi->kode_target)) }}" method="POST" id="formTargetkomisi" autocomplete="off" class="space-y-3.5">
    @method('PUT')
    @csrf

    <!-- SECTION 1: EXECUTIVE SUMMARY STRIP -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i data-lucide="crosshair" class="w-3.5 h-3.5"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block leading-none">Dokumen Target</span>
                    <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400 text-xs sm:text-sm">
                        {{ $targetkomisi->kode_target }}
                    </span>
                </div>
            </div>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50">
                <i data-lucide="edit-3" class="w-3 h-3"></i>
                <span>Mode Penyesuaian</span>
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <!-- 1. Cabang -->
            <div class="flex flex-col justify-center">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Cabang</span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 font-bold text-xs border border-blue-200/60 dark:border-blue-900/50 w-fit">
                    <i data-lucide="building-2" class="w-3 h-3"></i>
                    <span>{{ textUpperCase($targetkomisi->cabang->nama_cabang ?? $targetkomisi->nama_cabang) }}</span>
                </span>
            </div>

            <!-- 2. Periode Bulan -->
            <div class="flex flex-col justify-center sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Bulan</span>
                <span class="font-bold text-slate-800 dark:text-slate-100 text-xs">
                    {{ $namaBulan }}
                </span>
            </div>

            <!-- 3. Periode Tahun -->
            <div class="flex flex-col justify-center sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Tahun</span>
                <span class="font-bold font-mono-num text-slate-800 dark:text-slate-100 text-xs">
                    {{ $targetkomisi->tahun }}
                </span>
            </div>

            <!-- 4. Total SKU Produk -->
            <div class="flex flex-col justify-center sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Item Produk</span>
                <span class="font-bold text-slate-800 dark:text-slate-100 text-xs">
                    {{ count($produk) }} SKU Terdaftar
                </span>
            </div>
        </div>
    </div>

    <!-- SECTION 2: TABEL MATRIKS TARGET DETAIL (STICKY SALESMAN COLUMNS) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[460px] scrollbar-thin">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap" id="tableTargetDetailEdit">
                <thead class="sticky top-0 z-20">
                    <!-- Baris 1: Header Top (Frozen Kode & Salesman) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th rowspan="4" class="sticky left-0 z-40 w-[80px] min-w-[80px] max-w-[80px] py-2 px-3 text-center font-bold uppercase tracking-wider text-white bg-[#284c9a] border-r border-white/10 align-middle">KODE</th>
                        <th rowspan="4" class="sticky left-[80px] z-40 w-[180px] min-w-[180px] max-w-[180px] py-2 px-3 font-bold uppercase tracking-wider text-white bg-[#284c9a] border-r-2 border-[#193267] shadow-[3px_0_6px_-2px_rgba(0,0,0,0.35)] align-middle">SALESMAN</th>
                        <th rowspan="4" class="py-2 px-3 text-center w-24 min-w-[90px] font-bold uppercase tracking-wider text-white border-r border-white/10 align-middle bg-[#284c9a]">NIK</th>
                        <th rowspan="4" class="py-2 px-3 text-center w-28 min-w-[100px] font-bold uppercase tracking-wider text-white border-r border-white/10 align-middle bg-[#284c9a]">MASA KERJA</th>
                        <th colspan="{{ count($produk) * 10 }}" class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white">
                            RINCIAN TARGET PRODUK
                        </th>
                    </tr>

                    <!-- Baris 2: Nama/Kode Produk Header -->
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        @foreach ($produk as $p)
                            <th colspan="10" class="py-1.5 px-2 text-center font-bold uppercase tracking-wider text-amber-300 border-x border-white/15">
                                {{ $p->kode_produk }} - {{ $p->nama_produk }}
                            </th>
                        @endforeach
                    </tr>

                    <!-- Baris 3: Kategori Metrik (AVG, Realisasi, Last Target, Target) -->
                    <tr class="bg-[#193267] text-white text-[10.5px]">
                        @foreach ($produk as $p)
                            <th rowspan="2" class="py-1 px-2 text-center font-bold uppercase tracking-wider bg-emerald-950/70 text-emerald-300 border-l border-white/10">AVG</th>
                            <th colspan="3" class="py-1 px-2 text-center font-bold uppercase tracking-wider bg-sky-950/70 text-sky-300 border-x border-white/10">Realisasi Penjualan</th>
                            <th rowspan="2" class="py-1 px-2 text-center font-bold uppercase tracking-wider bg-indigo-950/70 text-indigo-300 border-r border-white/10">Last</th>
                            <th colspan="5" class="py-1 px-2 text-center font-bold uppercase tracking-wider bg-[#132752] text-amber-200 border-r border-white/10">Target Penyesuaian</th>
                        @endforeach
                    </tr>

                    <!-- Baris 4: Kolom Riwayat Realisasi & Tingkatan Target -->
                    <tr class="bg-[#152a56] text-white text-[10px]">
                        @foreach ($produk as $p)
                            <th class="py-1 px-2 text-center font-semibold bg-sky-950/60 text-sky-200">{{ getMonthName2($lasttigabulan) }}</th>
                            <th class="py-1 px-2 text-center font-semibold bg-sky-950/60 text-sky-200">{{ getMonthName2($lastduabulan) }}</th>
                            <th class="py-1 px-2 text-center font-semibold bg-sky-950/60 text-sky-200">{{ getMonthName2($lastbulan) }}</th>
                            <th class="py-1 px-2 text-center font-bold text-white/80">AWAL</th>
                            <th class="py-1 px-2 text-center font-bold {{ $level_user == 'regional sales manager' ? 'text-amber-300 underline font-black' : 'text-white/80' }}">RSM</th>
                            <th class="py-1 px-2 text-center font-bold {{ $level_user == 'gm marketing' ? 'text-amber-300 underline font-black' : 'text-white/80' }}">GM</th>
                            <th class="py-1 px-2 text-center font-bold {{ $level_user == 'direktur' ? 'text-amber-300 underline font-black' : 'text-white/80' }}">DIRUT</th>
                            <th class="py-1 px-2 text-center font-black bg-amber-950/70 text-amber-300 border-r border-white/10">AKHIR</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="gettargetsalesman" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    <tr>
                        <td colspan="{{ (count($produk) * 10) + 4 }}" class="py-14 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div class="w-7 h-7 border-2 border-[#284c9a] border-t-transparent rounded-full animate-spin"></div>
                                <span class="text-xs font-medium">Memuat data matriks target salesman...</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECTION 3: KETERANGAN WARNA METRIK -->
    <div class="flex items-center gap-4 flex-wrap text-[11px] text-slate-500 dark:text-slate-400 px-1">
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-xs bg-emerald-100 dark:bg-emerald-950/80 border border-emerald-300 dark:border-emerald-700"></span>
            <span>Rata-Rata Penjualan 3 Bulan (AVG)</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-xs bg-sky-100 dark:bg-sky-950/80 border border-sky-300 dark:border-sky-700"></span>
            <span>Realisasi Penjualan Historis</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-xs bg-indigo-100 dark:bg-indigo-950/80 border border-indigo-300 dark:border-indigo-700"></span>
            <span>Target Terakhir (Last Target)</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-xs bg-amber-100 dark:bg-amber-950/80 border border-amber-300 dark:border-amber-700"></span>
            <span>Target Akhir Terkalkulasi</span>
        </div>
    </div>

    <!-- SECTION 4: KONFIRMASI & SUBMIT -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <input type="checkbox" id="agreement_check" name="agreement"
                class="w-4 h-4 rounded-sm border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 cursor-pointer">
            <label for="agreement_check" class="text-xs font-medium text-slate-600 dark:text-slate-300 cursor-pointer select-none">
                Konfirmasi penyesuaian target komisi salesman sudah benar
            </label>
        </div>

        <div class="flex items-center justify-end gap-2 shrink-0">
            <button type="button" class="btn-close-modal h-[36px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                Batal
            </button>
            <button type="submit" id="btnSimpan" disabled
                class="inline-flex items-center justify-center gap-1.5 h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer opacity-50 pointer-events-none">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $("#formTargetkomisi");
        const $btnSimpan = $form.find("#btnSimpan");
        const $agreement = $form.find("#agreement_check");

        // Agreement toggle
        $agreement.on('change', function() {
            if (this.checked) {
                $btnSimpan.prop('disabled', false).removeClass('opacity-50 pointer-events-none');
            } else {
                $btnSimpan.prop('disabled', true).addClass('opacity-50 pointer-events-none');
            }
        });

        // Load Edit Matrix AJAX
        function loadSalesmanEdit() {
            $.ajax({
                type: 'POST',
                url: "{{ route('targetkomisi.gettargetsalesmanedit') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_target: "{{ $targetkomisi->kode_target }}",
                },
                success: function(response) {
                    $form.find("#gettargetsalesman").html(response);
                    if (typeof $.fn.maskMoney !== 'undefined') {
                        $form.find(".money").maskMoney({
                            thousands: '.',
                            decimal: ',',
                            precision: 0,
                            allowZero: true
                        });
                    }
                },
                error: function() {
                    $form.find("#gettargetsalesman").html(`
                        <tr>
                            <td colspan="{{ (count($produk) * 10) + 4 }}" class="py-8 text-center text-rose-500">
                                <span class="text-xs font-bold">Gagal memuat matriks target komisi salesman.</span>
                            </td>
                        </tr>
                    `);
                }
            });
        }

        loadSalesmanEdit();

        // Submit Form Handling with JSON packaging to avoid max_input_vars limit
        $form.on('submit', function(e) {
            if (!$agreement.is(':checked')) {
                e.preventDefault();
                alert('Silakan centang konfirmasi penyesuaian target komisi.');
                return false;
            }

            // Build JSON of target data
            var targetData = [];
            $("#tableTargetDetailEdit > tbody > tr").each(function() {
                var row = $(this);
                var kode_salesman = row.find("input[name='kode_salesman[]']").val();
                if (!kode_salesman) return;
                
                var salesmanData = {
                    kode_salesman: kode_salesman,
                    products: {}
                };
                
                @foreach($produk as $p)
                    salesmanData.products['{{ $p->kode_produk }}'] = {
                        target_awal: row.find(".t_awal_{{ $p->kode_produk }}").val(),
                        rsm: row.find(".t_rsm_{{ $p->kode_produk }}").val(),
                        gm: row.find(".t_gm_{{ $p->kode_produk }}").val(),
                        dirut: row.find(".t_dirut_{{ $p->kode_produk }}").val(),
                        akhir: row.find(".target_akhir_{{ $p->kode_produk }}").val()
                    };
                @endforeach
                
                targetData.push(salesmanData);
            });

            // Append hidden field target_data
            $("<input>").attr({
                type: "hidden",
                name: "target_data",
                value: JSON.stringify(targetData)
            }).appendTo($form);

            // Strip names from other raw inputs to avoid max_input_vars limit
            $form.find("input").not("[name='target_data'], [name='_token'], [name='_method'], [name='agreement']").removeAttr("name");

            // Anti Double Submit
            $btnSimpan.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSimpan.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        });

    });
</script>

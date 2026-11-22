@php
    $numFormat = function($val) {
        return number_format((float)($val ?? 0), 0, ',', '.');
    };
@endphp

<form action="{{ route('transitin.store', Crypt::encrypt($surat_jalan->no_mutasi)) }}" id="formTransitin" method="POST" class="space-y-4" autocomplete="off">
    @csrf

    <!-- INFO SUMMARY STRIP -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-4">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">No. Surat Jalan</span>
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $surat_jalan->no_mutasi }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">No. Dokumen</span>
                <span class="font-bold font-mono-num text-slate-800 dark:text-slate-100">{{ $surat_jalan->no_dok }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Tanggal SJ</span>
                <span class="font-medium text-slate-800 dark:text-slate-100">{{ DateToIndo($surat_jalan->tanggal) }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Cabang</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">{{ textUpperCase($surat_jalan->nama_cabang) }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">No. Permintaan</span>
                <span class="font-mono-num text-slate-700 dark:text-slate-300">{{ $surat_jalan->no_permintaan ?? '-' }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Tgl Permintaan</span>
                <span class="text-slate-700 dark:text-slate-300">{{ !empty($surat_jalan->tanggal_permintaan) ? DateToIndo($surat_jalan->tanggal_permintaan) : '-' }}</span>
            </div>
            <div class="sm:col-span-2">
                <span class="block text-[11px] font-semibold text-slate-400">Status Saat Ini</span>
                <div class="mt-0.5">
                    @if ($surat_jalan->status_surat_jalan == 0)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200/60 dark:border-rose-800/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            Belum Diterima Cabang
                        </span>
                    @elseif ($surat_jalan->status_surat_jalan == 1)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Sudah Diterima Cabang
                        </span>
                    @elseif ($surat_jalan->status_surat_jalan == 2)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200/60 dark:border-blue-800/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            Transit Out
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @if (!empty($surat_jalan->keterangan))
            <div class="mt-2.5 pt-2.5 border-t border-slate-200/60 dark:border-slate-800/60 text-xs">
                <span class="text-[11px] font-semibold text-slate-400">Keterangan:</span>
                <span class="text-slate-700 dark:text-slate-300 ml-1">{{ $surat_jalan->keterangan }}</span>
            </div>
        @endif
    </div>

    <!-- DETAIL ITEMS TABLE -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[300px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <!-- Row 1: Title Strip (colspan=4) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="4" class="px-4 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="boxes" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Barang Transit</span>
                                </div>
                                <span class="text-[11px] text-white/70 font-mono-num">{{ count($detail) }} item</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Headers -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">JUMLAH</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @php $grandTotal = 0; @endphp
                    @forelse ($detail as $d)
                        @php $grandTotal += (float)($d->jumlah ?? 0); @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3 text-center text-slate-400 font-mono-num">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 text-center font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">{{ $d->nama_produk }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-white">
                                {{ $numFormat($d->jumlah) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-400">Tidak ada detail barang</td>
                        </tr>
                    @endforelse
                </tbody>
                @if (count($detail) > 0)
                    <tfoot class="border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/70 font-bold text-xs">
                        <tr>
                            <td colspan="3" class="py-2 px-3 text-right uppercase tracking-wider text-slate-600 dark:text-slate-300">
                                Total Kuantitas:
                            </td>
                            <td class="py-2 px-3 text-right font-mono-num text-[#284c9a] dark:text-blue-400 font-black">
                                {{ $numFormat($grandTotal) }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- TANGGAL PENERIMAAN TRANSIT IN FIELD -->
    <div class="field-group rounded-lg border border-slate-200/80 dark:border-slate-800 p-3.5">
        <label for="tanggal_transitin" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Tanggal Penerimaan Transit IN <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" name="tanggal" id="tanggal_transitin" placeholder="Pilih Tanggal Penerimaan" readonly
                class="flatpickr-date-transitin w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="flex items-center justify-end gap-2.5 pt-2">
        <button type="button" class="btn-close-modal h-[36px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanTransitin"
            class="inline-flex items-center justify-center gap-2 h-[36px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            <span>Konfirmasi Terima Barang</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Initialize Flatpickr with range constraint
        $(".flatpickr-date-transitin").flatpickr({
            dateFormat: "Y-m-d",
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }]
        });

        const $form = $('#formTransitin');
        const $btnSimpan = $('#btnSimpanTransitin');

        function setFieldError($input, message) {
            const $fieldGroup = $input.closest('.field-group');
            $fieldGroup.addClass('has-error');
            $input.addClass('!border-rose-500 !ring-1 !ring-rose-500');
            $fieldGroup.find('.input-prefix-icon').addClass('!text-rose-500');
            $fieldGroup.find('.field-error-msg').remove();
            $fieldGroup.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" stroke-width="2"/>
                        <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/>
                        <circle cx="12" cy="16" r="1" fill="currentColor"/>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearFieldError($input) {
            const $fieldGroup = $input.closest('.field-group');
            $fieldGroup.removeClass('has-error');
            $input.removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $fieldGroup.find('.input-prefix-icon').removeClass('!text-rose-500');
            $fieldGroup.find('.field-error-msg').remove();
        }

        $form.find('#tanggal_transitin').on('input change', function() {
            clearFieldError($(this));
        });

        // Form Submit
        $form.on('submit', function(e) {
            const $tanggal = $form.find('#tanggal_transitin');
            if (!$tanggal.val()) {
                e.preventDefault();
                setFieldError($tanggal, 'Tanggal penerimaan transit in wajib diisi');
                $tanggal.focus();
                return false;
            }

            $btnSimpan.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSimpan.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        });
    });
</script>

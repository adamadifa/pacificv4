@php
    $numFormat = function($val) {
        return number_format((float)($val ?? 0), 2, ',', '.');
    };
    $grandtotaltarif = 0;
    foreach ($detail as $d) {
        $grandtotaltarif += (float)($d->tarif ?? 0) + (float)($d->tepung ?? 0) + (float)($d->bs ?? 0);
    }
@endphp

<form action="{{ route('kontrabonangkutan.storeproses', Crypt::encrypt($kontrabon->no_kontrabon)) }}" method="POST" id="formProseskontrabon" autocomplete="off" class="space-y-4">
    @csrf

    <!-- STRIP INFORMASI KONTRABON -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 p-3 sm:p-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">No. Kontrabon</span>
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400 text-sm">
                    {{ $kontrabon->no_kontrabon }}
                </span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Tanggal Kontrabon</span>
                <span class="font-bold font-mono-num text-slate-800 dark:text-slate-100">
                    {{ DateToIndo($kontrabon->tanggal) }}
                </span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Armada Angkutan</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">
                    {{ textUpperCase($kontrabon->nama_angkutan) }}
                </span>
            </div>
        </div>
    </div>

    <!-- TABEL RINCIAN DOKUMEN SURAT JALAN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="8" class="py-2 px-3.5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider">
                                    <i data-lucide="list" class="w-4 h-4 text-white/80"></i>
                                    <span>Rincian Tagihan Surat Jalan</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">{{ count($detail) }} Dokumen</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-1.5 px-3">No. Dok</th>
                        <th class="py-1.5 px-3">Tanggal</th>
                        <th class="py-1.5 px-3">No. Polisi</th>
                        <th class="py-1.5 px-3">Tujuan</th>
                        <th class="py-1.5 px-3 text-right">Tarif</th>
                        <th class="py-1.5 px-3 text-right">Tepung</th>
                        <th class="py-1.5 px-3 text-right">BS</th>
                        <th class="py-1.5 px-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-300">
                    @foreach ($detail as $d)
                        @php
                            $totaltarif = (float)($d->tarif ?? 0) + (float)($d->tepung ?? 0) + (float)($d->bs ?? 0);
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->no_dok }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num">
                                {{ formatIndo($d->tanggal) }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num">
                                {{ $d->no_polisi ?? '-' }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-100">
                                {{ $d->tujuan }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num">
                                {{ $numFormat($d->tarif) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-500">
                                {{ $numFormat($d->tepung) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-500">
                                {{ $numFormat($d->bs) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-white">
                                {{ $numFormat($totaltarif) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200 dark:border-slate-700 text-xs font-bold">
                    <tr>
                        <td colspan="7" class="py-2 px-3 text-slate-700 dark:text-slate-300 uppercase tracking-wider">TOTAL PEMBAYARAN</td>
                        <td class="py-2 px-3 text-right font-mono-num text-slate-900 dark:text-white text-sm">{{ $numFormat($grandtotaltarif) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- FORM INPUT PEMBAYARAN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/30 p-3 sm:p-4 space-y-3">
        <div class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-200 mb-1">
            Realisasi Pembayaran Kas/Bank
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
            <!-- Tanggal Bayar -->
            <div class="field-group">
                <label for="tanggal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tanggal Pembayaran <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="tanggal" id="tanggal" placeholder="Pilih Tanggal Bayar" readonly
                        class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>
            </div>

            <!-- Akun Kas / Bank -->
            <div class="field-group">
                <label for="kode_bank" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Sumber Kas / Bank <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="landmark" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_bank" id="kode_bank" class="select2Kodebank w-full">
                        <option value="">Pilih Sumber Bank</option>
                        @foreach ($bank as $d)
                            <option value="{{ $d->kode_bank }}">
                                {{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Keterangan -->
        <div class="field-group">
            <label for="keterangan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Keterangan
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="keterangan" id="keterangan" placeholder="Keterangan pembayaran (opsional)"
                    class="w-full h-[36px] pl-8 pr-3 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>
    </div>

    <!-- TOMBOL SUBMIT -->
    <div class="pt-1">
        <button type="submit" id="btnSimpan"
            class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Proses Pembayaran</span>
        </button>
    </div>
</form>

<script>
$(function() {
    const $form = $("#formProseskontrabon");

    $form.find(".flatpickr-date").flatpickr({
        dateFormat: "Y-m-d"
    });

    if ($form.find('.select2Kodebank').length) {
        $form.find('.select2Kodebank').select2({
            placeholder: 'Pilih Sumber Bank',
            allowClear: true,
            width: '100%'
        });
    }

    // Validasi Interaktif Helpers V2
    function setFieldError($field, message) {
        const $group = $field.closest('.field-group');
        $group.addClass('has-error');
        $field.addClass('is-invalid !border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.select2-selection').addClass('is-invalid !border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.select2-container').addClass('is-invalid');
        $group.find('.input-prefix-icon').addClass('!text-rose-500');
        $group.find('.field-error-msg').remove();
        $group.append(`
            <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>${message}</span>
            </p>
        `);
    }

    function clearFieldError($field) {
        const $group = $field.closest('.field-group');
        $group.removeClass('has-error');
        $field.removeClass('is-invalid !border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.select2-selection').removeClass('is-invalid !border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.select2-container').removeClass('is-invalid');
        $group.find('.input-prefix-icon').removeClass('!text-rose-500');
        $group.find('.field-error-msg').remove();
    }

    $form.find("input, select").on('input change select2:select select2:clear', function() {
        clearFieldError($(this));
    });

    $form.on('submit', function(e) {
        let hasError = false;
        let $firstError = null;

        const tanggal = $form.find("#tanggal").val();
        const kode_bank = $form.find("#kode_bank").val();

        if (!tanggal) {
            setFieldError($form.find("#tanggal"), "Tanggal pembayaran wajib diisi");
            hasError = true;
            $firstError = $form.find("#tanggal");
        }

        if (!kode_bank) {
            setFieldError($form.find("#kode_bank"), "Sumber Kas/Bank wajib dipilih");
            hasError = true;
            if (!$firstError) $firstError = $form.find("#kode_bank");
        }

        if (hasError) {
            e.preventDefault();
            if ($firstError) $firstError.focus();
            return false;
        }

        const $btn = $form.find("#btnSimpan");
        $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
        $btn.html(`
            <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Memproses...</span>
        `);
    });

    if (window.lucide) lucide.createIcons();
});
</script>

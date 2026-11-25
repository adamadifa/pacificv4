@php
    $total = 0;
    foreach ($detail as $d) {
        $total += $d->jumlah;
    }
@endphp

<form action="{{ route('pembayarangiro.approvestore', Crypt::encrypt($giro->kode_giro)) }}" method="POST" id="formApprovegiro" class="space-y-3.5">
    @csrf

    <!-- 1. DOKUMEN RINGKASAN TERPADU (EXECUTIVE SUMMARY STRIP V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 shadow-2xs space-y-3">
        <!-- Header Strip -->
        <div class="flex flex-wrap items-center justify-between gap-2 pb-2.5 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i data-lucide="receipt" class="w-4 h-4"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider">Informasi Pembayaran Giro</h5>
            </div>
            <div class="flex items-center gap-1.5 flex-wrap">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold font-mono-num bg-blue-50 text-[#284c9a] dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200/80 dark:border-blue-900/60">
                    {{ $giro->kode_giro }}
                </span>
                @if (!empty($giro->no_giro))
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold font-mono-num bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                        No. Giro: {{ $giro->no_giro }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Tier 1: Pelanggan & Total Pembayaran Giro -->
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <!-- Kolom Kiri: Pelanggan (Lebar & Bebas Truncate) -->
            <div class="sm:col-span-7">
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-1">Pelanggan</span>
                <div class="flex items-start gap-2.5">
                    <div class="p-1.5 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 shrink-0 mt-0.5">
                        <i data-lucide="store" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-100 leading-snug">
                            {{ textUpperCase($giro->nama_pelanggan) }}
                        </div>
                        <div class="mt-1 flex items-center gap-1.5 flex-wrap">
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono-num font-bold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">
                                {{ $giro->kode_pelanggan }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Total Pembayaran Giro -->
            <div class="sm:col-span-5 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80 p-2.5 flex items-center justify-between sm:flex-col sm:items-end sm:justify-center">
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Total Giro</span>
                <span class="text-base sm:text-lg font-black font-mono-num text-[#284c9a] dark:text-blue-400 tracking-tight block">
                    Rp {{ formatAngka($total) }}
                </span>
                <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 block mt-0.5">
                    {{ count($detail) }} Faktur Terbayar
                </span>
            </div>
        </div>

        <!-- Tier 2: Metadata Grid (Tanggal Bayar, Bank Pengirim, Jatuh Tempo, Dokumen Fisik) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-2.5 border-t border-slate-100 dark:border-slate-800">
            <!-- Tanggal Bayar -->
            <div class="flex items-center gap-2">
                <div class="p-1.5 rounded-md bg-slate-50 dark:bg-slate-800 text-slate-400 shrink-0">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500 block leading-none">Tanggal Bayar</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200 font-mono-num text-xs mt-1 block truncate">
                        {{ DateToIndo($giro->tanggal) }}
                    </span>
                </div>
            </div>

            <!-- Bank Pengirim -->
            <div class="flex items-center gap-2">
                <div class="p-1.5 rounded-md bg-slate-50 dark:bg-slate-800 text-slate-400 shrink-0">
                    <i data-lucide="landmark" class="w-3.5 h-3.5"></i>
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500 block leading-none">Bank Pengirim</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200 text-xs mt-1 block truncate" title="{{ textUpperCase($giro->bank_pengirim) }}">
                        {{ textUpperCase($giro->bank_pengirim) }}
                    </span>
                </div>
            </div>

            <!-- Tanggal Jatuh Tempo -->
            <div class="flex items-center gap-2">
                <div class="p-1.5 rounded-md bg-slate-50 dark:bg-slate-800 text-slate-400 shrink-0">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500 block leading-none">Jatuh Tempo</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200 font-mono-num text-xs mt-1 block truncate">
                        {{ DateToIndo($giro->jatuh_tempo) }}
                    </span>
                </div>
            </div>

            <!-- Dokumen Fisik Giro -->
            <div class="flex items-center gap-2">
                @if (!empty($giro->foto) && Storage::disk('public')->exists('/giro/' . $giro->foto))
                    <div class="p-1.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 shrink-0">
                        <i data-lucide="image" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500 block leading-none">Dokumen Fisik</span>
                        <a href="{{ getfotoGiro($giro->foto) }}" target="_blank"
                            class="font-bold text-[#284c9a] dark:text-blue-400 hover:underline text-xs mt-1 inline-flex items-center gap-1">
                            <span>Lihat Foto</span>
                            <i data-lucide="external-link" class="w-3 h-3"></i>
                        </a>
                    </div>
                @else
                    <div class="p-1.5 rounded-md bg-slate-50 dark:bg-slate-800 text-slate-300 dark:text-slate-600 shrink-0">
                        <i data-lucide="image-off" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500 block leading-none">Dokumen Fisik</span>
                        <span class="text-xs text-slate-400 mt-1 block">Tanpa Dokumen</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 2. TABEL RINCIAN FAKTUR -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="layers" class="w-3.5 h-3.5 text-blue-200"></i>
                <h6 class="text-xs font-bold text-white uppercase tracking-wider">Rincian Faktur Terbayar</h6>
            </div>
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-white/10 text-white border border-white/20">
                {{ count($detail) }} Faktur
            </span>
        </div>

        <div class="overflow-x-auto max-h-48 overflow-y-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 sticky top-0 z-10">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider w-12 text-center">No</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">No. Faktur</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    @forelse ($detail as $index => $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <td class="py-1.5 px-3 text-center text-slate-400 font-mono-num">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 font-semibold text-brand-600 dark:text-brand-400 font-mono-num">
                                <a href="{{ route('penjualan.show', Crypt::encrypt($d->no_faktur)) }}" target="_blank" class="hover:underline inline-flex items-center gap-1">
                                    <span>{{ $d->no_faktur }}</span>
                                    <i data-lucide="external-link" class="w-3 h-3 text-slate-400"></i>
                                </a>
                            </td>
                            <td class="py-1.5 px-3 text-right font-bold font-mono-num text-slate-900 dark:text-white">
                                {{ formatAngka($d->jumlah) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-3 text-center text-slate-400">Tidak ada rincian faktur</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50/90 dark:bg-slate-800/60 font-bold border-t border-slate-200/80 dark:border-slate-700">
                    <tr>
                        <td colspan="2" class="py-2 px-3 text-right text-xs uppercase tracking-wider text-slate-600 dark:text-slate-300">Total</td>
                        <td class="py-2 px-3 text-right font-mono-num text-sm text-[#284c9a] dark:text-blue-400">
                            {{ formatAngka($total) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- 3. FORM PERSETUJUAN (APPROVE STATE) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 space-y-3">
        <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
            <i data-lucide="check-square" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
            <h6 class="text-xs font-bold text-slate-800 dark:text-slate-200">Form Verifikasi Pembayaran Giro</h6>
        </div>

        <!-- Status -->
        <div class="space-y-1">
            <label for="status" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Status Verifikasi <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                </div>
                <select name="status" id="status"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none">
                    <option value="">Pilih Status</option>
                    <option value="0">Pending</option>
                    <option value="1">Diterima</option>
                    <option value="2">Ditolak</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
            <p id="error-status" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Status verifikasi wajib dipilih</span>
            </p>
        </div>

        <!-- Tanggal Diterima / Ditolak -->
        <div class="space-y-1" id="tgl_container">
            <label for="tanggal_approve_giro" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tanggal Diterima / Ditolak <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal_approve_giro" readonly placeholder="Pilih Tanggal"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-tanggal" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Tanggal wajib diisi</span>
            </p>
        </div>

        <!-- Bank Penerima -->
        <div class="space-y-1" id="bank_container">
            <label for="kode_bank_approve_giro" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Bank Penerima <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <select name="kode_bank" id="kode_bank_approve_giro" class="select2Kodebank w-full">
                    <option value="">Pilih Bank Penerima</option>
                    @foreach ($bank as $d)
                        <option value="{{ $d->kode_bank }}">
                            {{ !empty($d->nama_bank_alias) ? $d->nama_bank_alias : $d->nama_bank }}
                            {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <p id="error-kode_bank" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Bank penerima wajib dipilih</span>
            </p>
        </div>

        <!-- Omset Bulan & Tahun -->
        <div id="omset_container" class="space-y-2 pt-1 border-t border-slate-100 dark:border-slate-800">
            <span class="block text-xs font-bold text-slate-700 dark:text-slate-200">Periode Omset Penjualan <span class="text-rose-500">*</span></span>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <div class="space-y-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar-days" class="w-4 h-4"></i>
                        </div>
                        <select name="omset_bulan" id="omset_bulan_giro"
                            class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none">
                            <option value="">Omset Bulan</option>
                            @foreach ($list_bulan as $d)
                                <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <p id="error-omset_bulan" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>Omset bulan wajib dipilih</span>
                    </p>
                </div>
                <div class="space-y-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <select name="omset_tahun" id="omset_tahun_giro"
                            class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none">
                            <option value="">Omset Tahun</option>
                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endfor
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <p id="error-omset_tahun" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>Omset tahun wajib dipilih</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. ACTION BUTTONS (RIGHT-ALIGNED) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelApproveGiro"
            class="h-[34px] px-4 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanApproveGiro"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer flex items-center gap-1.5">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Keputusan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formApprovegiro");

        if (typeof lucide !== 'undefined') lucide.createIcons();

        // Flatpickr
        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        // Select2
        $form.find('.select2Kodebank').select2({
            placeholder: 'Pilih Bank Penerima',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        function updateFormVisibility() {
            const status = $form.find("#status").val();
            if (status === '1') {
                $form.find("#omset_container").slideDown(150);
                $form.find("#bank_container").slideDown(150);
                $form.find("#tgl_container").slideDown(150);
            } else if (status === '2') {
                $form.find("#bank_container").slideUp(150);
                $form.find("#tgl_container").slideDown(150);
                $form.find("#omset_container").slideUp(150);
            } else {
                $form.find("#bank_container").slideUp(150);
                $form.find("#tgl_container").slideUp(150);
                $form.find("#omset_container").slideUp(150);
            }
        }

        updateFormVisibility();

        $form.find("#status").on('change', function() {
            updateFormVisibility();
            clearError($form.find("#status"), $form.find("#error-status"));
        });

        function clearError($input, $error) {
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').removeClass('text-rose-500');
            $error.addClass('hidden').removeClass('flex');
        }

        function showError($input, $error) {
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').addClass('text-rose-500');
            $error.removeClass('hidden').addClass('flex');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        $form.find("#tanggal_approve_giro").on('input change', function() {
            clearError($(this), $form.find("#error-tanggal"));
        });
        $form.find("#kode_bank_approve_giro").on('change', function() {
            clearError($form.find('.select2-container-v2'), $form.find("#error-kode_bank"));
        });
        $form.find("#omset_bulan_giro").on('change', function() {
            clearError($(this), $form.find("#error-omset_bulan"));
        });
        $form.find("#omset_tahun_giro").on('change', function() {
            clearError($(this), $form.find("#error-omset_tahun"));
        });

        $form.find("#btnCancelApproveGiro").on('click', function() {
            if (typeof modalApproveGiro !== 'undefined') {
                modalApproveGiro.close();
            } else {
                $('#modalApproveGiro').addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            let $firstError = null;

            const status = $form.find("#status").val();
            const tanggal = $form.find("#tanggal_approve_giro").val();
            const kode_bank = $form.find("#kode_bank_approve_giro").val();
            const omset_bulan = $form.find("#omset_bulan_giro").val();
            const omset_tahun = $form.find("#omset_tahun_giro").val();

            if (!status) {
                showError($form.find("#status"), $form.find("#error-status"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#status");
            }

            if ((status === '1' || status === '2') && !tanggal) {
                showError($form.find("#tanggal_approve_giro"), $form.find("#error-tanggal"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_approve_giro");
            }

            if (status === '1') {
                if (!kode_bank) {
                    showError($form.find('.select2-container-v2'), $form.find("#error-kode_bank"));
                    hasError = true;
                    if (!$firstError) $firstError = $form.find("#kode_bank_approve_giro");
                }
                if (!omset_bulan) {
                    showError($form.find("#omset_bulan_giro"), $form.find("#error-omset_bulan"));
                    hasError = true;
                    if (!$firstError) $firstError = $form.find("#omset_bulan_giro");
                }
                if (!omset_tahun) {
                    showError($form.find("#omset_tahun_giro"), $form.find("#error-omset_tahun"));
                    hasError = true;
                    if (!$firstError) $firstError = $form.find("#omset_tahun_giro");
                }
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            // Disable & Loading spinner
            const $btn = $form.find("#btnSimpanApproveGiro");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);

            this.submit();
        });
    });
</script>

@php
    $total = $setoranpusat->setoran_kertas + $setoranpusat->setoran_logam;
@endphp

<form action="{{ route('setoranpusat.approvestore', Crypt::encrypt($setoranpusat->kode_setoran)) }}" method="POST" id="formApprovesetoranpusat" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- Summary Box Setoran Pusat -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3 sm:p-3.5 shadow-2xs">
        <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-200/80 dark:border-slate-800">
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Kode: {{ $setoranpusat->kode_setoran }}</span>
            <span class="text-xs font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ DateToIndo($setoranpusat->tanggal) }}</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
            <div>
                <span class="text-[10px] text-slate-400 font-medium block">Cabang</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 block truncate">{{ textUpperCase($setoranpusat->nama_cabang) }}</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-medium block">Uang Kertas</span>
                <span class="font-bold font-mono-num text-slate-800 dark:text-slate-200 block">{{ formatAngka($setoranpusat->setoran_kertas) }}</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-medium block">Uang Logam</span>
                <span class="font-bold font-mono-num text-slate-800 dark:text-slate-200 block">{{ formatAngka($setoranpusat->setoran_logam) }}</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-medium block">Total Setoran</span>
                <span class="text-sm font-black font-mono-num text-[#284c9a] dark:text-blue-400 block">Rp {{ formatAngka($total) }}</span>
            </div>
        </div>
        @if (!empty($setoranpusat->keterangan))
            <div class="mt-2 pt-2 border-t border-slate-200/60 dark:border-slate-800 text-xs">
                <span class="text-slate-400 font-medium">Keterangan:</span>
                <span class="text-slate-700 dark:text-slate-300 font-semibold">{{ $setoranpusat->keterangan }}</span>
            </div>
        @endif
    </div>

    <!-- Form Verifikasi Penerimaan -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 space-y-3">
        <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
            <i data-lucide="check-square" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
            <h6 class="text-xs font-bold text-slate-800 dark:text-slate-200">Form Verifikasi Penerimaan Bank</h6>
        </div>

        <!-- Tanggal Penerimaan -->
        <div class="space-y-1">
            <label for="tanggal_approve_pusat" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tanggal Penerimaan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal_approve_pusat" readonly placeholder="Pilih Tanggal Penerimaan"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-tanggal-approve-pusat" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Tanggal penerimaan wajib diisi</span>
            </p>
        </div>

        <!-- Bank Penerima -->
        <div class="space-y-1">
            <label for="kode_bank_approve_pusat" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Bank Penerima <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <select name="kode_bank" id="kode_bank_approve_pusat" class="select2Kodebank w-full">
                    <option value="">Pilih Bank Penerima</option>
                    @foreach ($bank as $d)
                        <option value="{{ $d->kode_bank }}">{{ $d->nama_bank }} ({{ $d->no_rekening }})</option>
                    @endforeach
                </select>
            </div>
            <p id="error-kode_bank-approve-pusat" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Bank penerima wajib dipilih</span>
            </p>
        </div>

        <!-- Omset Bulan & Tahun -->
        <div class="space-y-2 pt-1 border-t border-slate-100 dark:border-slate-800">
            <span class="block text-xs font-bold text-slate-700 dark:text-slate-200">Periode Omset Penjualan <span class="text-rose-500">*</span></span>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <div class="space-y-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar-days" class="w-4 h-4"></i>
                        </div>
                        <select name="omset_bulan" id="omset_bulan_pusat"
                            class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none">
                            <option value="">Omset Bulan</option>
                            @foreach ($list_bulan as $d)
                                <option value="{{ $d['kode_bulan'] }}" {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }}>{{ $d['nama_bulan'] }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <select name="omset_tahun" id="omset_tahun_pusat"
                            class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none">
                            <option value="">Omset Tahun</option>
                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                <option value="{{ $t }}" {{ $t == date('Y') ? 'selected' : '' }}>{{ $t }}</option>
                            @endfor
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelApprovePusat"
            class="h-[34px] px-4 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanApprovePusat"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer flex items-center gap-1.5">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Verifikasi Terima</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formApprovesetoranpusat");

        if (typeof lucide !== 'undefined') lucide.createIcons();

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        $form.find('.select2Kodebank').select2({
            placeholder: 'Pilih Bank Penerima',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
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

        $form.find("#tanggal_approve_pusat").on('input change', function() {
            clearError($(this), $form.find("#error-tanggal-approve-pusat"));
        });
        $form.find("#kode_bank_approve_pusat").on('change', function() {
            clearError($form.find('.select2-container-v2'), $form.find("#error-kode_bank-approve-pusat"));
        });

        $form.find("#btnCancelApprovePusat").on('click', function() {
            if (typeof modalApproveSetoranPusat !== 'undefined') {
                modalApproveSetoranPusat.close();
            } else {
                $("#modalApproveSetoranPusat").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            let $firstError = null;

            const tanggal = $form.find("#tanggal_approve_pusat").val();
            const kode_bank = $form.find("#kode_bank_approve_pusat").val();

            if (!tanggal) {
                showError($form.find("#tanggal_approve_pusat"), $form.find("#error-tanggal-approve-pusat"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_approve_pusat");
            }

            if (!kode_bank) {
                showError($form.find('.select2-container-v2'), $form.find("#error-kode_bank-approve-pusat"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_bank_approve_pusat");
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanApprovePusat");
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

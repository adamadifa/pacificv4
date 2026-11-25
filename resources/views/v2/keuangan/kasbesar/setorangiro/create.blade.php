<form action="{{ route('setorangiro.store', Crypt::encrypt($giro->kode_giro)) }}" method="POST" id="formSetorangiro" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- Summary Box Transaksi Giro -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3 sm:p-3.5 shadow-2xs">
        <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-200/80 dark:border-slate-800">
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100">No. Giro: {{ $giro->no_giro ?: $giro->kode_giro }}</span>
            <span class="text-xs font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ DateToIndo($giro->tanggal) }}</span>
        </div>
        <div class="grid grid-cols-2 gap-2 text-xs">
            <div>
                <span class="text-[10px] text-slate-400 font-medium block">Pelanggan</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 block truncate" title="[{{ $giro->kode_pelanggan }}] {{ textUpperCase($giro->nama_pelanggan) }}">
                    {{ textUpperCase($giro->nama_pelanggan) }}
                </span>
                <span class="text-[10px] text-slate-400 font-mono-num block">{{ $giro->kode_pelanggan }}</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-medium block">Bank Pengirim</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ textUpperCase($giro->bank_pengirim) }}</span>
                <span class="text-[10px] text-slate-400 font-mono-num block">Tempo: {{ DateToIndo($giro->jatuh_tempo) }}</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-medium block">Jumlah Giro</span>
                <span class="text-sm font-black font-mono-num text-[#284c9a] dark:text-blue-400 block">Rp {{ formatAngka($giro->total) }}</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-medium block">Status Verifikasi</span>
                <div class="mt-0.5">
                    @if ($giro->status == '1')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                            <i data-lucide="check-circle-2" class="w-3 h-3 text-emerald-600"></i> Diterima
                        </span>
                    @elseif ($giro->status == '2')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60">
                            <i data-lucide="x-circle" class="w-3 h-3 text-rose-600"></i> Ditolak
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">
                            <i data-lucide="clock" class="w-3 h-3 text-amber-600"></i> Pending
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tanggal Disetorkan Input -->
    <div class="space-y-1">
        <label for="tanggal_disetorkan_giro" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Tanggal Disetorkan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" id="tanggal_disetorkan_giro" name="tanggal" readonly placeholder="Pilih Tanggal Disetorkan"
                class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-tanggal-setor-giro" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Tanggal disetorkan wajib diisi</span>
        </p>
    </div>

    <!-- Action Buttons (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelSetorGiro"
            class="h-[34px] px-4 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanSetorGiro"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer flex items-center gap-1.5">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Setoran</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formSetorangiro");

        if (typeof lucide !== 'undefined') lucide.createIcons();

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
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

        $form.find("#tanggal_disetorkan_giro").on('input change', function() {
            clearError($(this), $form.find("#error-tanggal-setor-giro"));
        });

        $form.find("#btnCancelSetorGiro").on('click', function() {
            if (typeof modalSetoranGiro !== 'undefined') {
                modalSetoranGiro.close();
            } else {
                $("#modalSetoranGiro").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            const tanggal = $form.find("#tanggal_disetorkan_giro").val();

            if (!tanggal) {
                showError($form.find("#tanggal_disetorkan_giro"), $form.find("#error-tanggal-setor-giro"));
                $form.find("#tanggal_disetorkan_giro").focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanSetorGiro");
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

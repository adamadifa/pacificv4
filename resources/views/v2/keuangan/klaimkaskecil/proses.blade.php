<form action="{{ route('klaimkaskecil.storeproses', Crypt::encrypt($klaim->kode_klaim)) }}" id="formProsesklaimkaskecil" method="POST" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- SUMMARY INFORMASI KLAIM -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3 sm:p-3.5 shadow-2xs space-y-2.5">
        <div class="flex items-center justify-between pb-2 border-b border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-1.5">
                <i data-lucide="clipboard-check" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $klaim->kode_klaim }}</span>
            </div>
            <span class="text-xs font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ DateToIndo($klaim->tanggal) }}</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
            <div>
                <span class="text-[10px] text-slate-400 font-medium block">Cabang</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ textUpperCase($klaim->nama_cabang) }}</span>
            </div>
            <div class="sm:col-span-2">
                <span class="text-[10px] text-slate-400 font-medium block">Keterangan</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 block truncate" title="{{ $klaim->keterangan }}">{{ $klaim->keterangan }}</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-medium block">Status</span>
                @if (!empty($klaim->no_bukti))
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                        <i data-lucide="check" class="w-3 h-3"></i>
                        <span>Sudah Diproses</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        <span>Belum Diproses</span>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- DETAIL RINCIAN TRANSAKSI KAS KECIL -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-1.5">
                <i data-lucide="list-checks" class="w-3.5 h-3.5 text-blue-200"></i>
                <h6 class="text-xs font-bold text-white uppercase tracking-wider">Rincian Transaksi Klaim Kas Kecil</h6>
            </div>
            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-white/10 text-white border border-white/20 font-mono-num">
                {{ count($detail) }} Item
            </span>
        </div>

        <div class="overflow-x-auto max-h-[300px] overflow-y-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 sticky top-0 z-20">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">TANGGAL</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">NO. BUKTI</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider min-w-[180px]">KETERANGAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider min-w-[160px]">AKUN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">PENERIMAAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">PENGELUARAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">SALDO</th>
                    </tr>
                    <tr class="bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-100 font-bold border-t border-slate-200 dark:border-slate-700">
                        <td colspan="6" class="py-1.5 px-3 uppercase text-[11px]">SALDO AWAL</td>
                        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                            @php
                                $saldo_awal = $saldoawal ? $saldoawal->saldo_akhir : 0;
                            @endphp
                            {{ formatAngka($saldo_awal) }}
                        </td>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    @php
                        $saldo = $saldo_awal;
                        $totalpenerimaan = 0;
                        $totalpengeluaran = 0;
                        $totalpenerimaannonpusat = 0;
                    @endphp
                    @foreach ($detail as $d)
                        @php
                            $penerimaan = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                            $pengeluaran = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                            $totalpenerimaan += $penerimaan;
                            $totalpengeluaran += $pengeluaran;
                            $saldo += $penerimaan - $pengeluaran;
                            if ($d->keterangan != 'Penerimaan Kas Kecil') {
                                $totalpenerimaannonpusat += $penerimaan;
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">
                                {{ formatIndo2($d->tanggal) }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-bold font-mono-num text-slate-800 dark:text-slate-200">
                                {{ $d->no_bukti }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-800 dark:text-slate-200 max-w-[220px] truncate" title="{{ $d->keterangan }}">
                                {{ textCamelCase($d->keterangan) }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                                <span class="font-bold font-mono-num text-slate-700 dark:text-slate-300">{{ $d->kode_akun }}</span>
                                <span class="text-[11px]">- {{ textCamelCase($d->nama_akun) }}</span>
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $penerimaan > 0 ? formatAngka($penerimaan) : '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-rose-600 dark:text-rose-400">
                                {{ $pengeluaran > 0 ? formatAngka($pengeluaran) : '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ formatAngka($saldo) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-100 dark:bg-slate-800/80 font-bold border-t-2 border-slate-300 dark:border-slate-700 text-xs">
                    <tr class="bg-[#1e3a78] text-white">
                        <td colspan="4" class="py-2 px-3 text-center uppercase text-[11px]">TOTAL MUTASI</td>
                        <td class="py-2 px-3 text-right font-mono-num text-emerald-300">{{ formatAngka($totalpenerimaan) }}</td>
                        <td class="py-2 px-3 text-right font-mono-num text-rose-300">{{ formatAngka($totalpengeluaran) }}</td>
                        <td class="py-2 px-3 text-right font-mono-num text-white font-black">{{ formatAngka($saldo) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- REKAPITULASI & PENGGANTIAN KAS KECIL -->
    @php
        $penggantian = $totalpengeluaran - $totalpenerimaannonpusat;
        $total = $saldo_awal + $totalpenerimaan - $totalpenerimaannonpusat;
    @endphp
    <input type="hidden" name="jml_penggantian" value="{{ $penggantian }}">
    <input type="hidden" name="saldo_akhir" value="{{ $saldo }}">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <!-- Card Penggantian -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-emerald-50/50 dark:bg-emerald-950/20 p-3 sm:p-3.5 space-y-1.5">
            <span class="text-[11px] font-bold text-emerald-700 dark:text-emerald-300 uppercase tracking-wider block">Penggantian Kas Kecil</span>
            <div class="text-lg font-black font-mono-num text-emerald-700 dark:text-emerald-300">
                Rp {{ formatAngka($penggantian) }}
            </div>
            <p class="text-[11px] text-slate-500 italic">
                Terbilang: {{ textCamelCase(terbilang($penggantian)) }} Rupiah
            </p>
        </div>

        <!-- Card Rincian Saldo -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3 sm:p-3.5 space-y-1 text-xs">
            <div class="flex justify-between items-center py-0.5 border-b border-slate-200/60 dark:border-slate-800">
                <span class="text-slate-500">Saldo Awal:</span>
                <span class="font-bold font-mono-num text-slate-800 dark:text-slate-200">Rp {{ formatAngka($saldo_awal) }}</span>
            </div>
            <div class="flex justify-between items-center py-0.5 border-b border-slate-200/60 dark:border-slate-800">
                <span class="text-slate-500">Penerimaan Pusat:</span>
                <span class="font-bold font-mono-num text-emerald-600">Rp {{ formatAngka($totalpenerimaan) }}</span>
            </div>
            <div class="flex justify-between items-center py-0.5 border-b border-slate-200/60 dark:border-slate-800">
                <span class="text-slate-500">Pengeluaran Kas Kecil:</span>
                <span class="font-bold font-mono-num text-rose-600">Rp {{ formatAngka($totalpengeluaran) }}</span>
            </div>
            <div class="flex justify-between items-center pt-1 font-black">
                <span class="text-[#284c9a] dark:text-blue-400">Saldo Akhir:</span>
                <span class="font-mono-num text-[#284c9a] dark:text-blue-400">Rp {{ formatAngka($saldo) }}</span>
            </div>
        </div>
    </div>

    <!-- FORM INPUT PROSES KLAIM -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 sm:p-3.5 space-y-2.5">
        <div class="flex items-center gap-1.5 pb-2 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="settings" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h6 class="text-xs font-bold text-slate-700 dark:text-slate-200">Parameter Proses Penggantian Kas Kecil</h6>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5 items-start">
            <!-- Tanggal Proses -->
            <div class="space-y-1 lg:col-span-3">
                <label for="tanggal_proses_klaim" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Tanggal Proses <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="tanggal_proses_klaim" name="tanggal" readonly placeholder="Pilih Tanggal"
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
                <p id="error-tanggal-proses" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Tanggal proses wajib diisi</span>
                </p>
            </div>

            <!-- Keterangan -->
            <div class="space-y-1 lg:col-span-5">
                <label for="keterangan_proses_klaim" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Keterangan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="keterangan_proses_klaim" name="keterangan" value="Penggantian Kas Kecil {{ textUpperCase($klaim->nama_cabang) }}" placeholder="Keterangan proses transfer..."
                        class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
                <p id="error-keterangan-proses" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Keterangan proses wajib diisi</span>
                </p>
            </div>

            <!-- Bank Penerima -->
            <div class="space-y-1 lg:col-span-4">
                <label for="kode_bank_proses" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Bank Penerima <span class="text-rose-500">*</span>
                </label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="landmark" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_bank" id="kode_bank_proses" class="select2Kodebankproses w-full">
                        <option value="">Pilih Bank</option>
                        @foreach ($bank as $d)
                            <option value="{{ $d->kode_bank }}" {{ $d->kode_bank == 'BK054' ? 'selected' : '' }}>
                                {{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <p id="error-bank-proses" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Bank penerima wajib dipilih</span>
                </p>
            </div>
        </div>
    </div>

    <!-- TOMBOL AKSI SUBMIT (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelProsesKlaim"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitProsesKlaim"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer inline-flex items-center gap-1.5">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Proses Klaim</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formProsesklaimkaskecil");

        if (typeof lucide !== 'undefined') lucide.createIcons();

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        const $select2Bank = $form.find('.select2Kodebankproses');
        if ($select2Bank.length) {
            $select2Bank.select2({
                placeholder: 'Pilih Bank Penerima',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        function clearError($input, $error) {
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').removeClass('text-rose-500');
            if ($input.hasClass('select2-hidden-accessible')) {
                $input.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            $error.addClass('hidden').removeClass('flex');
        }

        function showError($input, $error) {
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').addClass('text-rose-500');
            if ($input.hasClass('select2-hidden-accessible')) {
                $input.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            $error.removeClass('hidden').addClass('flex');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        $form.find("#tanggal_proses_klaim").on('input change', function() {
            clearError($(this), $form.find("#error-tanggal-proses"));
        });
        $form.find("#keterangan_proses_klaim").on('input change', function() {
            clearError($(this), $form.find("#error-keterangan-proses"));
        });
        $form.find("#kode_bank_proses").on('change select2:select', function() {
            clearError($(this), $form.find("#error-bank-proses"));
        });

        $form.find("#btnCancelProsesKlaim").on('click', function() {
            if (typeof modalProsesKlaimHelper !== 'undefined') {
                modalProsesKlaimHelper.close();
            } else {
                $("#modalProsesKlaim").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            const tanggal = $form.find("#tanggal_proses_klaim").val().trim();
            const keterangan = $form.find("#keterangan_proses_klaim").val().trim();
            const kode_bank = $form.find("#kode_bank_proses").val();

            let hasError = false;
            let $firstError = null;

            if (!tanggal) {
                showError($form.find("#tanggal_proses_klaim"), $form.find("#error-tanggal-proses"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_proses_klaim");
            }

            if (!keterangan) {
                showError($form.find("#keterangan_proses_klaim"), $form.find("#error-keterangan-proses"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#keterangan_proses_klaim");
            }

            if (!kode_bank) {
                showError($form.find("#kode_bank_proses"), $form.find("#error-bank-proses"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_bank_proses");
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnSubmitProsesKlaim");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses...</span>
            `);

            this.submit();
        });
    });
</script>

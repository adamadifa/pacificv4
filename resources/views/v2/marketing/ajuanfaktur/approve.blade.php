<form action="{{ route('ajuanfaktur.approvestore', Crypt::encrypt($ajuanfaktur->no_pengajuan)) }}" id="formApprovefaktur" method="POST">
    @csrf

    <div class="space-y-3">
        <!-- RINGKASAN PENGAJUAN DOKUMEN -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/60 dark:bg-slate-900/60 space-y-2.5">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $ajuanfaktur->no_pengajuan }}</span>
                        <span class="text-[10.5px] text-slate-400 block">{{ DateToIndo($ajuanfaktur->tanggal) }}</span>
                    </div>
                </div>
                <div>
                    @if ($ajuanfaktur->siklus_pembayaran == '1')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/60">
                            <i data-lucide="check-circle" class="w-3 h-3"></i>
                            COD (Turun Barang)
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                            Reguler
                        </span>
                    @endif
                </div>
            </div>

            <!-- DETAIL GRID -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-xs">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Pelanggan</span>
                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ $ajuanfaktur->nama_pelanggan }}</span>
                    <span class="text-[11px] font-mono-num text-slate-500 block">{{ $ajuanfaktur->kode_pelanggan }}</span>
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Limit Kredit Saat Ini</span>
                    <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                        Rp {{ number_format($ajuanfaktur->limit_pelanggan, 0, ',', '.') }}
                    </span>
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Salesman & Cabang</span>
                    <span class="font-medium text-slate-700 dark:text-slate-300">
                        {{ $ajuanfaktur->nama_salesman }} ({{ $ajuanfaktur->nama_cabang }})
                    </span>
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">No. HP</span>
                    <span class="font-mono-num text-slate-700 dark:text-slate-300">{{ $ajuanfaktur->no_hp_pelanggan ?: '-' }}</span>
                </div>
                <div class="sm:col-span-2">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Alamat</span>
                    <span class="text-slate-600 dark:text-slate-400">{{ $ajuanfaktur->alamat_pelanggan ?: '-' }}</span>
                </div>
            </div>
        </div>

        <!-- METRIC FAKTUR CARD -->
        <div class="rounded-xl overflow-hidden bg-[#284c9a] text-white p-3.5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10.5px] font-bold uppercase tracking-wider text-white/70 block">Jumlah Faktur yang Diajukan</span>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-3xl font-black font-mono-num leading-none">{{ $ajuanfaktur->jumlah_faktur }}</span>
                    <span class="text-xs font-bold text-white/80">Faktur Tambahan</span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center text-white/80 shrink-0">
                <i data-lucide="copy" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- KETERANGAN AJUAN -->
        @if (!empty($ajuanfaktur->keterangan))
            <div class="rounded-lg border border-slate-200 dark:border-slate-800 p-2.5 bg-slate-50/50 dark:bg-slate-900/50">
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Catatan Pengajuan:</span>
                <p class="text-xs text-slate-700 dark:text-slate-300 italic">"{{ $ajuanfaktur->keterangan }}"</p>
            </div>
        @endif
    </div>

    <!-- TOMBOL AKSI PERSETUJUAN -->
    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 pt-3 mt-3 border-t border-slate-100 dark:border-slate-800 items-center">
        <div class="sm:col-span-3">
            <button type="button" class="btn-close-modal w-full h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                Tutup
            </button>
        </div>
        <div class="sm:col-span-4">
            <button type="button" id="btnDeclineSubmit"
                class="w-full h-[34px] px-3 rounded-lg bg-rose-50 hover:bg-rose-100 active:bg-rose-200 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/50 dark:text-rose-400 text-xs font-bold transition flex items-center justify-center gap-1.5 border border-rose-200 dark:border-rose-900/60 cursor-pointer">
                <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
                <span>Tolak Ajuan</span>
            </button>
        </div>
        <div class="sm:col-span-5">
            <button type="submit" id="btnApproveSubmit"
                class="w-full h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span id="btnApproveText">Setujui / Teruskan</span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $("#formApprovefaktur");

        // Decline Button Confirmation
        $('#btnDeclineSubmit').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Tolak Pengajuan Faktur?',
                text: 'Status ajuan akan ditolak dan tidak dapat dilanjutkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $form.append('<input type="hidden" name="decline" value="decline">');
                    $form.submit();
                }
            });
        });

        // Double submit prevention
        $form.on('submit', function() {
            const $btn = $form.find('#btnApproveSubmit');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $form.find('#btnDeclineSubmit').prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $form.find('#btnApproveText').html(`
                <svg class="animate-spin -ml-1 mr-2 h-3.5 w-3.5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            `);
        });
    });
</script>

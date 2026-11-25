<form action="{{ route('kirimlpc.storeapprove', Crypt::encrypt($kirim_lpc->kode_kirim_lpc)) }}" id="formApprove" method="POST" class="space-y-4">
    @csrf

    <!-- EXECUTIVE SUMMARY STRIP -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 p-3.5 space-y-2.5 text-xs">
        <div class="flex items-center justify-between border-b border-slate-200/80 dark:border-slate-700 pb-2">
            <span class="text-slate-400 font-semibold">Kode Pengiriman</span>
            <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $kirim_lpc->kode_kirim_lpc }}</span>
        </div>
        <div class="flex items-center justify-between border-b border-slate-200/80 dark:border-slate-700 pb-2">
            <span class="text-slate-400 font-semibold">Cabang</span>
            <span class="font-bold text-slate-900 dark:text-slate-100 uppercase">{{ textUpperCase($kirim_lpc->nama_cabang) }}</span>
        </div>
        <div class="flex items-center justify-between border-b border-slate-200/80 dark:border-slate-700 pb-2">
            <span class="text-slate-400 font-semibold">Periode</span>
            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $namabulan[$kirim_lpc->bulan] ?? $kirim_lpc->bulan }} {{ $kirim_lpc->tahun }}</span>
        </div>
        <div class="flex items-center justify-between border-b border-slate-200/80 dark:border-slate-700 pb-2">
            <span class="text-slate-400 font-semibold">Tanggal & Jam Kirim</span>
            <span class="font-mono-num font-semibold text-slate-800 dark:text-slate-200">{{ formatIndo($kirim_lpc->tanggal) }} {{ $kirim_lpc->jam }}</span>
        </div>
        <div class="flex items-center justify-between border-b border-slate-200/80 dark:border-slate-700 pb-2">
            <span class="text-slate-400 font-semibold">Status</span>
            @if ($kirim_lpc->status == '1')
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                    <i data-lucide="check-check" class="w-3 h-3"></i> Diterima
                </span>
            @else
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">
                    <i data-lucide="clock" class="w-3 h-3"></i> Pending
                </span>
            @endif
        </div>
        <div class="flex items-center justify-between">
            <span class="text-slate-400 font-semibold">Lampiran</span>
            @if (!empty($kirim_lpc->foto))
                <a href="{{ url(Storage::url('lpc/' . $kirim_lpc->foto)) }}" target="_blank"
                    class="inline-flex items-center gap-1 font-bold text-[#284c9a] dark:text-blue-400 hover:underline">
                    <i data-lucide="paperclip" class="w-3.5 h-3.5"></i>
                    <span>Lihat Dokumen</span>
                </a>
            @else
                <span class="text-slate-400">-</span>
            @endif
        </div>
    </div>

    <!-- Modal Footer Actions -->
    <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" data-bs-dismiss="modal"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnApproveSubmit"
            class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="thumbs-up" class="w-3.5 h-3.5"></i>
            <span>Approve LPC</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $('#formApprove').submit(function() {
            const $btn = $('#btnApproveSubmit');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Memproses...</span>
            `);
        });
    });
</script>

<form action="{{ route('ticketupdate.storeapprove', Crypt::encrypt($ticket->kode_pengajuan)) }}" method="POST" id="formApproveTu" class="space-y-3">
    @csrf
    @php
        $kategoriOptions = [
            '1' => 'Penjualan',
            '2' => 'Pembayaran',
            '3' => 'Retur',
            '4' => 'DPB',
            '5' => 'Mutasi Persediaan',
        ];
    @endphp

    <div class="rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 space-y-2.5">
        <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-1.5">
                <i data-lucide="file-diff" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Ringkasan Ticket Perubahan Data</h4>
            </div>
            <span class="font-mono-num font-bold text-xs text-[#284c9a] dark:text-blue-400">{{ $ticket->kode_pengajuan }}</span>
        </div>

        <div class="grid grid-cols-2 gap-2.5 text-xs">
            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">Tanggal</span>
                <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $ticket->tanggal }}</span>
            </div>
            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">User Pengaju</span>
                <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $ticket->name }}</span>
            </div>
            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">Kategori</span>
                <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $kategoriOptions[$ticket->kategori] ?? '-' }}</span>
            </div>
            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">No. Bukti</span>
                <span class="font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $ticket->no_bukti }}</span>
            </div>
        </div>

        <div>
            <span class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Keterangan</span>
            <div class="p-2.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-700 dark:text-slate-300 leading-relaxed">
                {{ $ticket->keterangan }}
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="submit" name="decline" value="1" class="h-[36px] px-4 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold flex items-center gap-1.5 transition cursor-pointer">
            <i data-lucide="thumbs-down" class="w-3.5 h-3.5"></i>
            <span>Tolak</span>
        </button>
        <button type="submit" class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold flex items-center gap-1.5 transition cursor-pointer">
            <i data-lucide="thumbs-up" class="w-3.5 h-3.5"></i>
            <span>Approve</span>
        </button>
    </div>
</form>

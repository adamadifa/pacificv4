<!-- INFORMASI FAKTUR & PELANGGAN STRIP -->
<div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 p-3 sm:p-3.5 space-y-2.5 mb-3.5">
    <div class="flex items-center justify-between pb-2 mb-1 border-b border-slate-200/80 dark:border-slate-800">
        <div class="flex items-center gap-1.5">
            <i data-lucide="file-text" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Faktur & Pelanggan</span>
        </div>
        <span class="font-mono-num font-bold text-xs text-[#284c9a] dark:text-blue-400">
            {{ $faktur->no_faktur }}
        </span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
        <div class="flex items-start justify-between sm:justify-start sm:gap-3">
            <span class="text-slate-400 w-24 shrink-0">Tanggal</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ DateToIndo($faktur->tanggal) }}</span>
        </div>
        <div class="flex items-start justify-between sm:justify-start sm:gap-3">
            <span class="text-slate-400 w-24 shrink-0">Jenis Transaksi</span>
            @if ($faktur->jenis_transaksi == 'T')
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                    Tunai
                </span>
            @else
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10.5px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">
                    Kredit
                </span>
            @endif
        </div>
        <div class="flex items-start justify-between sm:justify-start sm:gap-3 sm:col-span-2">
            <span class="text-slate-400 w-24 shrink-0">Pelanggan</span>
            <span class="font-bold text-slate-900 dark:text-white">
                <span class="font-mono-num text-[#284c9a] dark:text-blue-400">{{ $faktur->kode_pelanggan }}</span> - {{ textUpperCase($faktur->nama_pelanggan) }}
            </span>
        </div>
        <div class="flex items-start justify-between sm:justify-start sm:gap-3 sm:col-span-2">
            <span class="text-slate-400 w-24 shrink-0">Alamat</span>
            <span class="text-slate-600 dark:text-slate-300 leading-relaxed text-[11.5px]">{{ $faktur->alamat_pelanggan ?: '-' }}</span>
        </div>
    </div>
</div>

<!-- RINCIAN VISIT PELANGGAN -->
<div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 sm:p-3.5 space-y-3">
    <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-1.5">
            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Catatan Kunjungan</span>
        </div>
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold {{ $visit->jenis_kunjungan == 'OTS' ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">
            {{ $visit->jenis_kunjungan == 'OTD' ? 'OTD (On The Desk)' : 'OTS (On the Spot)' }}
        </span>
    </div>

    <div class="space-y-3 text-xs">
        <div>
            <span class="text-slate-400 font-semibold block mb-0.5">Tanggal Visit</span>
            <p class="font-semibold text-slate-800 dark:text-slate-200">{{ DateToIndo($visit->tanggal) }}</p>
        </div>

        <div>
            <span class="text-slate-400 font-semibold block mb-0.5">Hasil Konfirmasi</span>
            <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 text-slate-700 dark:text-slate-300 leading-relaxed">
                {{ $visit->hasil_konfirmasi ?: '-' }}
            </div>
        </div>

        <div>
            <span class="text-slate-400 font-semibold block mb-0.5">Note</span>
            <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 text-slate-700 dark:text-slate-300 leading-relaxed">
                {{ $visit->note ?: '-' }}
            </div>
        </div>

        <div>
            <span class="text-slate-400 font-semibold block mb-0.5">Saran / Keluhan</span>
            <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 text-slate-700 dark:text-slate-300 leading-relaxed">
                {{ $visit->saran ?: '-' }}
            </div>
        </div>

        <div>
            <span class="text-slate-400 font-semibold block mb-0.5">Action OM</span>
            <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 text-slate-700 dark:text-slate-300 leading-relaxed">
                {{ $visit->act_om ?: '-' }}
            </div>
        </div>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<div class="p-3 sm:p-3.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40 h-full">
    <div class="flex items-center gap-1.5 pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
        <i data-lucide="wallet" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
        <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Ringkasan Tagihan & Kategori</h6>
    </div>

    <dl class="space-y-2 text-xs">
        <div class="flex justify-between items-center">
            <dt class="text-slate-500 dark:text-slate-400">Jumlah Pinjaman</dt>
            <dd class="font-mono-num font-bold text-slate-800 dark:text-slate-100 text-right">{{ formatAngka($piutangkaryawan->jumlah) }}</dd>
        </div>

        <div class="flex justify-between items-center">
            <dt class="text-slate-500 dark:text-slate-400">Jumlah Pembayaran</dt>
            <dd class="font-mono-num font-bold text-emerald-600 dark:text-emerald-400 text-right">{{ formatAngka($piutangkaryawan->totalpembayaran) }}</dd>
        </div>

        <div class="flex justify-between items-center pt-1.5 border-t border-slate-200/60 dark:border-slate-800">
            <dt class="font-bold text-slate-700 dark:text-slate-200">Sisa Tagihan</dt>
            <dd class="font-mono-num font-black text-sm {{ ($piutangkaryawan->jumlah - $piutangkaryawan->totalpembayaran) > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }} text-right">
                {{ formatAngka($piutangkaryawan->jumlah - $piutangkaryawan->totalpembayaran) }}
            </dd>
        </div>

        <div class="flex justify-between items-center pt-1.5 border-t border-slate-200/60 dark:border-slate-800">
            <dt class="text-slate-500 dark:text-slate-400">Kategori Piutang</dt>
            <dd class="flex items-center justify-end gap-2">
                <span id="badge-kategori" class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $piutangkaryawan->kategori == 'EK' ? 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' }}">
                    {{ $piutangkaryawan->kategori == 'EK' ? 'Eks Karyawan' : 'Karyawan' }}
                </span>
                @can('piutangkaryawan.edit')
                    <label class="relative inline-flex items-center cursor-pointer" title="Aktifkan untuk Eks Karyawan, matikan untuk Karyawan">
                        <input type="checkbox" id="switchKategori" class="sr-only peer"
                            data-kategori="{{ $piutangkaryawan->kategori }}"
                            {{ $piutangkaryawan->kategori == 'EK' ? 'checked' : '' }}>
                        <div class="w-9 h-5 bg-slate-300 peer-focus:outline-hidden rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#284c9a]"></div>
                    </label>
                @endcan
            </dd>
        </div>

        <div class="flex justify-between items-center">
            <dt class="text-slate-500 dark:text-slate-400">Status Akses</dt>
            <dd class="text-right">
                @if ($piutangkaryawan->status == 1)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-sky-100 text-sky-800 dark:bg-sky-950/60 dark:text-sky-300">
                        <i data-lucide="lock" class="w-2.5 h-2.5"></i>
                        <span>Hanya Bisa Dilihat Keuangan</span>
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                        Normal
                    </span>
                @endif
            </dd>
        </div>
    </dl>
</div>

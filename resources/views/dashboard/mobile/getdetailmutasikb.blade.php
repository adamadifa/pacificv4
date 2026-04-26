<div class="space-y-4">
    @php $total = 0; @endphp
    @forelse ($details as $d)
        @php $total += $d->jumlah; @endphp
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-50">
            <div class="flex justify-between items-start gap-4">
                <div class="flex-1">
                    <p class="text-xs font-bold text-slate-800 leading-tight mb-2">{{ $d->keterangan }}</p>
                    <div class="flex items-center">
                        <span class="text-[10px] px-2 py-0.5 bg-blue-50 rounded-lg text-blue-500 font-bold uppercase tracking-tighter">Kas Besar</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-sm font-black whitespace-nowrap {{ $jenis == 'K' ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $jenis == 'K' ? '+' : '-' }} Rp {{ formatAngka($d->jumlah) }}
                    </span>
                    <p class="text-[9px] text-slate-300 font-medium mt-1">{{ date('H:i', strtotime($d->created_at ?? now())) }} WIB</p>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-10">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ti ti-receipt-off text-slate-300 text-2xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400">Data tidak ditemukan</p>
        </div>
    @endforelse

    @if(count($details) > 0)
    <div class="mt-8 p-6 {{ $jenis == 'K' ? 'bg-emerald-600 shadow-emerald-200' : 'bg-rose-600 shadow-rose-200' }} rounded-[32px] shadow-lg text-white">
        <p class="text-[10px] font-bold uppercase tracking-[2px] opacity-70 mb-1">Total {{ $jenis == 'K' ? 'Penerimaan' : 'Pengeluaran' }} KB</p>
        <h3 class="text-2xl font-black mb-0">Rp {{ formatAngka($total) }}</h3>
    </div>
    @endif
</div>

<div class="space-y-4">
    @php $total = 0; @endphp
    @forelse ($details as $d)
        @php $total += $d->jumlah; @endphp
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-50">
            <div class="flex justify-between items-center mb-1">
                <span class="text-xs font-bold text-slate-800">{{ $d->nama_cabang }}</span>
                <span class="text-sm font-black text-blue-600">Rp {{ formatAngka($d->jumlah) }}</span>
            </div>
            <p class="text-[10px] text-slate-400 font-medium">{{ $d->keterangan }}</p>
        </div>
    @empty
        <div class="text-center py-10">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ti ti-database-off text-slate-300 text-2xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400">Data tidak ditemukan</p>
        </div>
    @endforelse
    
    @if(count($details) > 0)
    <div class="mt-8 p-6 bg-blue-600 rounded-[32px] shadow-lg shadow-blue-200 text-white">
        <p class="text-[10px] font-bold uppercase tracking-[2px] opacity-70 mb-1">Total Saldo Kas Besar</p>
        <h3 class="text-2xl font-black mb-0">Rp {{ formatAngka($total) }}</h3>
    </div>
    @endif
</div>

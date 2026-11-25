@extends('layouts.app')
@section('titlepage', 'Customer Service WA AI')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-[#284c9a] transition-colors">Home</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">CS AI</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-[#284c9a] dark:text-blue-400 font-bold">Komplain Pelanggan WA</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">
    <!-- COMPACT NO-LABEL FILTER BAR -->
    <form action="{{ route('wa-komplain.index') }}" method="GET">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
            <div class="lg:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="w-full h-[36px] pl-8 pr-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="Cari komplain, nama, nomor WA...">
            </div>

            <div class="lg:col-span-3 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                </div>
                <select name="status" class="w-full h-[36px] pl-8 pr-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
                    <option value="">Semua Status</option>
                    <option value="baru" {{ request('status') == 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="lg:col-span-3 relative select2-icon-wrapper">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none z-20 text-slate-400">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_wakomplain" class="select2CabangWa w-full" data-placeholder="Semua Cabang">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabangs as $c)
                        <option value="{{ $c->kode_cabang }}" {{ request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>{{ $c->nama_cabang }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <button type="submit" class="w-full h-[36px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold flex items-center justify-center gap-1.5 transition cursor-pointer">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Filter</span>
                </button>
            </div>
        </div>
    </form>

    <!-- DATA TABLE CARD -->
    @php $totalKomplain = method_exists($komplains, 'total') ? $komplains->total() : $komplains->count(); @endphp
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        <div class="px-3.5 py-2 bg-[#284c9a] text-white flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <i data-lucide="message-circle" class="w-3.5 h-3.5 text-blue-200"></i>
                <h2 class="text-xs font-bold text-white uppercase tracking-wider">Komplain Pelanggan WhatsApp AI</h2>
            </div>
            <span class="px-2 py-0.5 rounded-md bg-white/15 text-white text-[11px] font-bold font-mono-num">{{ $totalKomplain }} Data</span>
        </div>

        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-left border-collapse text-xs font-sans whitespace-nowrap">
                <thead>
                    <tr class="bg-[#1e3a78] text-white/90">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">No. Komplain</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">Tanggal</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">Kode Pelanggan</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">Nama Pelanggan</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">No. WhatsApp</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">Cabang</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center">Kategori (AI)</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center">Status</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($komplains as $k)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $k->no_komplain }}</td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">{{ $k->tanggal_komplain->format('d-m-Y') }}</td>
                            <td class="py-1.5 px-3">
                                @if ($k->kode_pelanggan)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-400 font-mono-num font-bold text-[10px] border border-sky-200 dark:border-sky-900">{{ $k->kode_pelanggan }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 font-semibold text-[10px] border border-amber-200 dark:border-amber-900">Belum Terdaftar</span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">{{ $k->nama_pelanggan }}</td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">{{ $k->wa_number }}</td>
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-200 font-medium">{{ $k->cabang ? $k->cabang->nama_cabang : '-' }}</td>
                            <td class="py-1.5 px-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-[10px] uppercase">{{ $k->kategori_ai ?? '-' }}</span>
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                @if ($k->status == 'baru')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 font-bold text-[10px] border border-rose-200 dark:border-rose-900">Baru</span>
                                @elseif ($k->status == 'diproses')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 font-bold text-[10px] border border-amber-200 dark:border-amber-900">Diproses</span>
                                @elseif ($k->status == 'selesai')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 font-bold text-[10px] border border-emerald-200 dark:border-emerald-900">Selesai</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 font-bold text-[10px]">Ditolak</span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('wa-komplain.show', $k->id) }}" class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 hover:bg-[#284c9a] hover:text-white border border-blue-200/80 dark:border-blue-900 flex items-center justify-center transition" title="Lihat Detail">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center gap-1.5">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-300 dark:text-slate-700"></i>
                                    <span>Tidak ada data komplain ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($komplains->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $komplains->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        $('.select2CabangWa').select2({
            placeholder: 'Semua Cabang',
            allowClear: true
        });
    });
</script>
@endpush

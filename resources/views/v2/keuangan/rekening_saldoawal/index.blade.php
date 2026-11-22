@extends('layouts.app')
@section('titlepage', 'Saldo Awal Rekening')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Saldo Awal Rekening</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Saldo Awal Rekening</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Manajemen dan pemantauan saldo awal harian seluruh rekening bank.</p>
        </div>

        @can('sarekening.create')
            <a href="{{ route('sarekening.create') }}"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Buat Saldo Awal</span>
            </a>
        @endcan
    </div>

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG, FLATPICKR) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ URL::current() }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
            <!-- Tanggal Filter with Flatpickr -->
            <div class="relative flex-1 w-full sm:w-auto">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal" value="{{ Request('tanggal') }}"
                    class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                    placeholder="Filter tanggal saldo awal..." readonly>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <!-- Submit Button -->
                <button type="submit"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>

                <!-- Reset Button (if filter applied) -->
                @if (Request('tanggal'))
                    <a href="{{ route('sarekening.index') }}"
                        class="inline-flex items-center justify-center gap-1 h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- MAIN DATA TABLE CARD WITH SEAMLESS UNIFIED HEADER -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        
        <!-- Table with Unified Pacific Navy Header (Card Header & Thead Unified) -->
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-xs text-left border-collapse font-sans">
                <!-- Seamless Unified Header -->
                <thead>
                    <!-- Row 1: Integrated Title Bar -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="4" class="px-4 py-3.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="landmark" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Saldo Awal Rekening</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                    Total {{ method_exists($saldo_awal, 'total') ? $saldo_awal->total() : $saldo_awal->count() }} Data
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles Seamlessly Flowing Below -->
                    <tr class="bg-[#284c9a] text-white text-[11px] border-none">
                        <th class="py-3 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-3 px-4 font-bold uppercase tracking-wider text-white/90 border-none">KODE SALDO AWAL</th>
                        <th class="py-3 px-4 font-bold uppercase tracking-wider text-white/90 border-none">TANGGAL BERLAKU</th>
                        <th class="py-3 px-4 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($saldo_awal as $d)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100">
                            <!-- No -->
                            <td class="py-2 px-3 text-center font-mono-num text-slate-400 border-r border-slate-100 dark:border-slate-800/60 text-[11px]">
                                {{ $loop->iteration + $saldo_awal->firstItem() - 1 }}
                            </td>

                            <!-- Kode Saldo Awal -->
                            <td class="py-2 px-3.5 border-r border-slate-100 dark:border-slate-800/60">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-500 shrink-0"></span>
                                    <span class="font-mono-num font-bold text-slate-900 dark:text-white">{{ $d->kode_saldo_awal }}</span>
                                </div>
                            </td>

                            <!-- Tanggal -->
                            <td class="py-2 px-3.5 border-r border-slate-100 dark:border-slate-800/60">
                                <div class="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                                    <span>{{ formatIndo($d->tanggal) }}</span>
                                </div>
                            </td>

                            <!-- Aksi -->
                            <td class="py-2 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('sarekening.index')
                                        <a href="{{ route('sarekening.show', Crypt::encrypt($d->kode_saldo_awal)) }}"
                                            class="w-6.5 h-6.5 rounded-md bg-sky-50 dark:bg-sky-950/60 hover:bg-sky-100 text-sky-600 dark:text-sky-400 flex items-center justify-center transition border border-sky-100 dark:border-sky-900"
                                            title="Lihat Rincian">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </a>
                                    @endcan

                                    @can('sarekening.edit')
                                        <a href="{{ route('sarekening.edit', Crypt::encrypt($d->kode_saldo_awal)) }}"
                                            class="w-6.5 h-6.5 rounded-md bg-amber-50 dark:bg-amber-950/60 hover:bg-amber-100 text-amber-600 dark:text-amber-400 flex items-center justify-center transition border border-amber-100 dark:border-amber-900"
                                            title="Edit Saldo Awal">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                        </a>
                                    @endcan

                                    @can('sarekening.delete')
                                        <form method="POST" action="{{ route('sarekening.delete', Crypt::encrypt($d->kode_saldo_awal)) }}" class="inline deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="w-6.5 h-6.5 rounded-md bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 text-rose-600 dark:text-rose-400 flex items-center justify-center transition border border-rose-100 dark:border-rose-900 btn-delete cursor-pointer"
                                                title="Hapus Saldo Awal"
                                                data-name="{{ $d->kode_saldo_awal }}">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="inbox" class="w-7 h-7 text-slate-300 dark:text-slate-600 mb-1.5"></i>
                                    <span class="text-xs font-semibold">Belum ada data saldo awal rekening.</span>
                                    <span class="text-[11px] text-slate-400 mt-0.5">Silakan klik "Buat Saldo Awal" untuk mulai menginput data.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-3.5 py-2.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40">
            {{ $saldo_awal->links('vendor.pagination.tailwind') }}
        </div>

    </div>

</div>
@endsection

@push('myscript')
<script>
$(function() {
    // Re-initialize Lucide Icons
    if (window.lucide) {
        lucide.createIcons();
    }

    // Flatpickr datepicker
    if ($('.flatpickr-date').length && typeof flatpickr !== 'undefined') {
        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });
    }

    // SweetAlert2 Delete Confirmation
    $(".btn-delete").on("click", function(e) {
        e.preventDefault();
        const form = $(this).closest("form");
        const kode = $(this).data("name") || "";
        
        window.confirmDelete(form, {
            title: "Hapus Saldo Awal?",
            text: "Data saldo awal rekening yang dihapus tidak dapat dipulihkan kembali.",
            itemName: kode ? `Kode: ${kode}` : "",
            confirmText: "Ya, Hapus Data"
        });
    });
});
</script>
@endpush

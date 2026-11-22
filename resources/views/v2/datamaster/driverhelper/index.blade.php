@extends('layouts.app')
@section('titlepage', 'Driver & Helper')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Data Master</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Driver & Helper</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 max-w-5xl">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Driver & Helper</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola daftar driver dan helper operasional pengiriman cabang.</p>
        </div>

        @can('driverhelper.create')
            <button type="button" id="btnCreateDriverhelper"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Driver / Helper</span>
            </button>
        @endcan
    </div>

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('driverhelper.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
            
            <!-- Input Cari Nama -->
            <div class="relative flex-1 w-full sm:w-auto">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="nama_driver_helper" id="nama_driver_helper_search" value="{{ request('nama_driver_helper') }}"
                    class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Cari nama driver atau helper...">
            </div>

            <!-- Filter Cabang -->
            @hasanyrole($roles_show_cabang)
                <div class="relative w-full sm:w-48">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang_search" id="kode_cabang_search"
                        class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                                {{ $c->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            @endhasanyrole

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <!-- Submit Button -->
                <button type="submit"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>

                <!-- Reset Button -->
                @if (request('nama_driver_helper') || request('kode_cabang_search'))
                    <a href="{{ route('driverhelper.index') }}"
                        class="inline-flex items-center justify-center gap-1 h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- MAIN DATA TABLE CARD WITH PACIFIC NAVY HEADER -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-xs text-left border-collapse font-sans whitespace-nowrap">
                <!-- Unified Header -->
                <thead>
                    <!-- Row 1: Title Strip -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="5" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="users" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Driver & Helper</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                    Total {{ method_exists($driverhelper, 'total') ? $driverhelper->total() : $driverhelper->count() }} Data
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2.5 px-4 w-36 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">NAMA DRIVER / HELPER</th>
                        <th class="py-2.5 px-4 w-40 font-bold uppercase tracking-wider text-white/90 border-none">CABANG</th>
                        <th class="py-2.5 px-4 text-center w-20 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($driverhelper as $d)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100">
                            <!-- No -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-400 border-r border-slate-100 dark:border-slate-800/60 text-[11px]">
                                {{ $loop->iteration + $driverhelper->firstItem() - 1 }}
                            </td>
                            <!-- Kode -->
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800/60">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-black font-mono-num bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
                                    {{ $d->kode_driver_helper }}
                                </span>
                            </td>
                            <!-- Nama Driver Helper -->
                            <td class="py-1.5 px-3.5 font-semibold text-slate-800 dark:text-slate-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                                    <span>{{ $d->nama_driver_helper }}</span>
                                </div>
                            </td>
                            <!-- Cabang -->
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800/60">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                    {{ $d->nama_cabang ?? $d->kode_cabang }}
                                </span>
                            </td>
                            <!-- Aksi -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('driverhelper.edit')
                                        <button type="button" 
                                            class="editDriverhelper inline-flex items-center justify-center w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white transition shadow-2xs cursor-pointer"
                                            kode_driver_helper="{{ Crypt::encrypt($d->kode_driver_helper) }}"
                                            title="Edit Driver/Helper">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('driverhelper.delete')
                                        <form method="POST" action="{{ route('driverhelper.delete', Crypt::encrypt($d->kode_driver_helper)) }}" class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="delete-confirm inline-flex items-center justify-center w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition shadow-2xs cursor-pointer"
                                                title="Hapus Driver/Helper">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="users-round" class="w-8 h-8 text-slate-300 dark:text-slate-600 stroke-[1.5]"></i>
                                    <span class="text-xs font-semibold">Belum ada data driver / helper</span>
                                    <span class="text-[10px] text-slate-400">Silakan tambahkan data baru atau ubah kata kunci filter.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($driverhelper->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $driverhelper->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Container -->
<div id="modalDriverhelper" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all duration-200">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
                <h4 id="modalTitleDriverhelper" class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                    Form Driver & Helper
                </h4>
            </div>
            <button type="button" class="btn-close-modal text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <!-- Modal Body Content -->
        <div id="modalBodyDriverhelper" class="p-4">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        const $modal = $("#modalDriverhelper");
        const $modalTitle = $("#modalTitleDriverhelper");
        const $modalBody = $("#modalBodyDriverhelper");

        function openModal(title) {
            $modalTitle.text(title);
            $modalBody.html(`
                <div class="py-8 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-slate-400">Memuat formulir...</span>
                </div>
            `);
            $modal.removeClass("hidden").addClass("flex");
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function closeModal() {
            $modal.addClass("hidden").removeClass("flex");
            $modalBody.empty();
        }

        // Close on button click (Static Backdrop)
        $modal.on("click", ".btn-close-modal", closeModal);

        // Trigger Create
        $("#btnCreateDriverhelper").on("click", function(e) {
            e.preventDefault();
            openModal("Tambah Driver / Helper");
            $modalBody.load("{{ route('driverhelper.create') }}", function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Trigger Edit
        $(document).on("click", ".editDriverhelper", function(e) {
            e.preventDefault();
            const kode = $(this).attr("kode_driver_helper");
            openModal("Edit Driver / Helper");
            $modalBody.load(`/driverhelper/${kode}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush
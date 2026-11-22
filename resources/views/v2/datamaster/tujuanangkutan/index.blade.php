@extends('layouts.app')
@section('titlepage', 'Tujuan Angkutan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Data Master</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Tujuan Angkutan</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 max-w-5xl">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Tujuan Angkutan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola data rute tujuan pengiriman dan tarif ongkos angkutan.</p>
        </div>

        @can('tujuanangkutan.create')
            <button type="button" id="btnCreateTujuan"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Tujuan Angkutan</span>
            </button>
        @endcan
    </div>

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('tujuanangkutan.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
            
            <!-- Cari Nama Tujuan -->
            <div class="relative flex-1 w-full sm:w-auto">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tujuan_search" id="tujuan_search" value="{{ request('tujuan_search') }}"
                    class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Cari nama tujuan angkutan...">
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>

                @if (request('tujuan_search'))
                    <a href="{{ route('tujuanangkutan.index') }}"
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
                    <!-- Row 1: Title Strip (colspan=5) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="5" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Tujuan & Tarif Angkutan</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                    Total {{ method_exists($tujuanangkutan, 'total') ? $tujuanangkutan->total() : $tujuanangkutan->count() }} Data
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2.5 px-4 w-32 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">TUJUAN</th>
                        <th class="py-2.5 px-4 text-right w-40 font-bold uppercase tracking-wider text-white/90 border-none">TARIF (RP)</th>
                        <th class="py-2.5 px-3 text-center w-20 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($tujuanangkutan as $d)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100">
                            <!-- No -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-400 border-r border-slate-100 dark:border-slate-800/60 text-[11px]">
                                {{ $loop->iteration + $tujuanangkutan->firstItem() - 1 }}
                            </td>

                            <!-- Kode -->
                            <td class="py-1.5 px-4 font-mono-num font-bold text-brand-600 dark:text-brand-400 text-xs">
                                <span class="px-2 py-0.5 rounded-md bg-brand-50 dark:bg-brand-950/50 border border-brand-200/60 dark:border-brand-800/60">
                                    {{ $d->kode_tujuan }}
                                </span>
                            </td>

                            <!-- Tujuan -->
                            <td class="py-1.5 px-4 font-semibold text-slate-800 dark:text-slate-100 text-xs">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                                    <span>{{ $d->tujuan }}</span>
                                </div>
                            </td>

                            <!-- Tarif -->
                            <td class="py-1.5 px-4 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100 text-xs">
                                {{ formatAngka($d->tarif) }}
                            </td>

                            <!-- Aksi -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('tujuanangkutan.edit')
                                        <button type="button"
                                            class="btnEditTujuan w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/60 transition flex items-center justify-center cursor-pointer shadow-2xs"
                                            title="Edit Tujuan"
                                            data-kode="{{ Crypt::encrypt($d->kode_tujuan) }}">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('tujuanangkutan.delete')
                                        <form method="POST" action="{{ route('tujuanangkutan.delete', Crypt::encrypt($d->kode_tujuan)) }}"
                                            class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 transition flex items-center justify-center cursor-pointer shadow-2xs"
                                                title="Hapus Tujuan">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800/80 flex items-center justify-center text-slate-400">
                                        <i data-lucide="map-pin-off" class="w-6 h-6"></i>
                                    </div>
                                    <p class="text-xs font-semibold">Belum ada data tujuan angkutan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION (STANDARD V2 COMPONENT) -->
        @if ($tujuanangkutan->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $tujuanangkutan->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- TAILWIND MODAL FORM -->
<div id="modalTujuanangkutan" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xl overflow-hidden transition-all duration-200">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
            <div class="flex items-center gap-2">
                <i data-lucide="map-pin" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i>
                <h4 id="modalTitleTujuan" class="text-xs font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wide">
                    Form Tujuan Angkutan
                </h4>
            </div>
            <button type="button" id="btnCloseModal"
                class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Modal Body (AJAX Container) -->
        <div id="modalContentTujuan" class="p-4">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        const $modal = $("#modalTujuanangkutan");
        const $modalContent = $("#modalContentTujuan");
        const $modalTitle = $("#modalTitleTujuan");

        function openModal(title) {
            $modalTitle.text(title);
            $modalContent.html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin w-6 h-6 text-brand-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500">Memuat formulir...</span>
                </div>
            `);
            $modal.removeClass("hidden");
            $("body").addClass("overflow-hidden");
        }

        function closeModal() {
            $modal.addClass("hidden");
            $modalContent.html("");
            $("body").removeClass("overflow-hidden");
        }

        $("#btnCloseModal").on("click", closeModal);

        // Static Backdrop: Explicit close only

        // Create Button
        $("#btnCreateTujuan").on("click", function(e) {
            e.preventDefault();
            openModal("Tambah Tujuan Angkutan");
            $modalContent.load('/tujuanangkutan/create', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Edit Button
        $(document).on("click", ".btnEditTujuan", function(e) {
            e.preventDefault();
            const kode = $(this).data("kode");
            openModal("Edit Tujuan Angkutan");
            $modalContent.load('/tujuanangkutan/' + kode + '/edit', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush

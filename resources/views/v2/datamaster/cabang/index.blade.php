@extends('layouts.app')
@section('titlepage', 'Cabang')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Data Master</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Cabang</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Cabang</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola kantor cabang operasional, regional, entitas PT, radius absensi, dan titik GPS.</p>
        </div>

        @can('cabang.create')
            <button type="button" id="btnCreateCabang"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Cabang</span>
            </button>
        @endcan
    </div>

    <!-- STATISTIC & METRIC CARD (Standar V2 Pacific Navy Strip) -->
    <div class="relative overflow-hidden rounded-xl bg-[#284c9a] text-white shadow-xs hover:shadow-md transition">
        <!-- Ambient Ornament SVG -->
        <div class="absolute -right-8 -bottom-8 pointer-events-none text-white/[0.06]">
            <svg class="w-48 h-48" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="100" cy="100" r="80" stroke-dasharray="8 6" />
                <circle cx="100" cy="100" r="50" />
                <circle cx="100" cy="100" r="20" />
            </svg>
        </div>
        <div class="absolute left-1/4 -top-10 w-44 h-44 bg-white/[0.04] rounded-full blur-2xl pointer-events-none"></div>

        <div class="grid grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-white/15 relative z-10">
            <!-- 1. Total Kantor Cabang -->
            <div class="p-3.5 sm:p-4 flex items-center justify-between group">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Total Cabang</span>
                    <span class="text-base sm:text-lg font-black text-white font-mono-num block leading-tight">
                        {{ method_exists($cabang, 'total') ? $cabang->total() : $cabang->count() }}
                    </span>
                    <span class="text-[10px] text-white/55 block">Kantor cabang aktif</span>
                </div>
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white shrink-0 group-hover:scale-105 transition">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
            </div>

            <!-- 2. Wilayah Regional -->
            <div class="p-3.5 sm:p-4 flex items-center justify-between group">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Wilayah Regional</span>
                    <span class="text-base sm:text-lg font-black text-white font-mono-num block leading-tight">
                        {{ \App\Models\Regional::count() }}
                    </span>
                    <span class="text-[10px] text-white/55 block">Cakupan regional induk</span>
                </div>
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white shrink-0 group-hover:scale-105 transition">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                </div>
            </div>

            <!-- 3. Entitas PT / Legal -->
            <div class="p-3.5 sm:p-4 flex items-center justify-between group">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Entitas PT</span>
                    <span class="text-base sm:text-lg font-black text-white font-mono-num block leading-tight">
                        {{ \App\Models\Cabang::distinct('kode_pt')->count('kode_pt') }}
                    </span>
                    <span class="text-[10px] text-white/55 block">Badan hukum terdaftar</span>
                </div>
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white shrink-0 group-hover:scale-105 transition">
                    <i data-lucide="landmark" class="w-4 h-4"></i>
                </div>
            </div>

            <!-- 4. Rata-Rata Radius Absensi -->
            <div class="p-3.5 sm:p-4 flex items-center justify-between group">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Rata-rata Radius</span>
                    <span class="text-base sm:text-lg font-black text-white font-mono-num block leading-tight">
                        {{ round(\App\Models\Cabang::avg('radius_cabang')) ?? 50 }} <span class="text-xs font-normal text-white/80">Meter</span>
                    </span>
                    <span class="text-[10px] text-white/55 block">Jangkauan geofence GPS</span>
                </div>
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white shrink-0 group-hover:scale-105 transition">
                    <i data-lucide="radar" class="w-4 h-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('cabang.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
            <!-- Input Cari Nama Cabang -->
            <div class="relative flex-1 w-full sm:w-auto">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="nama_cabang" id="nama_cabang_search" value="{{ request('nama_cabang') }}"
                    class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition placeholder:text-slate-400"
                    placeholder="Cari nama atau kode cabang...">
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                @if (request('nama_cabang'))
                    <a href="{{ route('cabang.index') }}"
                        class="inline-flex items-center justify-center gap-1 h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition shadow-2xs">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>Reset</span>
                    </a>
                @endif

                <button type="submit"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>
            </div>
        </form>
    </div>

    <!-- MAIN DATA TABLE CARD WITH UNIFIED PACIFIC NAVY HEADER -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-xs text-left border-collapse font-sans whitespace-nowrap">
                <!-- Unified Header -->
                <thead>
                    <!-- Row 1: Integrated Title Bar -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="10" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="building" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Kantor Cabang</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                    Total {{ method_exists($cabang, 'total') ? $cabang->total() : $cabang->count() }} Data
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2.5 px-3 w-20 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-white/90 border-none">NAMA CABANG</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">PERUSAHAAN (PT)</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">REGIONAL</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">TELEPON</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none">RADIUS</th>
                        <th class="py-2.5 px-3.5 font-bold uppercase tracking-wider text-white/90 border-none">ALAMAT & GPS</th>
                        <th class="py-2.5 px-2.5 text-center w-16 font-bold uppercase tracking-wider text-white/90 border-none">URUTAN</th>
                        <th class="py-2.5 px-3 text-center w-20 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($cabang as $d)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100">
                            <!-- No -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-400 border-r border-slate-100 dark:border-slate-800/60 text-[11px]">
                                {{ $loop->iteration + ($cabang->currentPage() - 1) * $cabang->perPage() }}
                            </td>

                            <!-- Kode Cabang -->
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800/60">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-black font-mono-num bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
                                    {{ $d->kode_cabang }}
                                </span>
                            </td>

                            <!-- Nama Cabang with Marker Color Dot -->
                            <td class="py-1.5 px-3.5 font-semibold text-slate-800 dark:text-slate-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full shrink-0 shadow-2xs border border-white dark:border-slate-700"
                                        style="background-color: {{ $d->color_marker ?? '#284c9a' }}"
                                        title="Color Marker: {{ $d->color_marker }}"></span>
                                    <span class="font-bold text-slate-900 dark:text-white uppercase tracking-tight">
                                        {{ $d->nama_cabang }}
                                    </span>
                                </div>
                            </td>

                            <!-- PT / Perusahaan -->
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-semibold">{{ $d->nama_pt }}</span>
                                    <span class="text-[10px] font-bold font-mono-num text-slate-400">({{ $d->kode_pt }})</span>
                                </div>
                            </td>

                            <!-- Regional -->
                            <td class="py-1.5 px-3">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200/60 dark:border-blue-800/60">
                                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                                    <span>{{ $d->nama_regional }}</span>
                                </span>
                            </td>

                            <!-- Telepon -->
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">
                                @if (!empty($d->telepon_cabang))
                                    <div class="flex items-center gap-1">
                                        <i data-lucide="phone" class="w-3 h-3 text-slate-400"></i>
                                        <span>{{ $d->telepon_cabang }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- Radius -->
                            <td class="py-1.5 px-3 text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold font-mono-num bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/60">
                                    <i data-lucide="radar" class="w-3 h-3 text-amber-500"></i>
                                    <span>{{ $d->radius_cabang }}m</span>
                                </span>
                            </td>

                            <!-- Alamat & Lokasi GPS -->
                            <td class="py-1.5 px-3.5 text-slate-600 dark:text-slate-300 max-w-[220px]">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="truncate block" title="{{ $d->alamat_cabang }}">{{ $d->alamat_cabang }}</span>
                                    @if (!empty($d->lokasi_cabang))
                                        <a href="https://www.google.com/maps?q={{ $d->lokasi_cabang }}" target="_blank"
                                            class="inline-flex items-center gap-1 text-[11px] font-bold text-brand-600 dark:text-brand-400 hover:underline shrink-0"
                                            title="Buka Google Maps">
                                            <i data-lucide="map" class="w-3 h-3 text-rose-500"></i>
                                            <span>Maps</span>
                                        </a>
                                    @endif
                                </div>
                            </td>

                            <!-- Urutan -->
                            <td class="py-1.5 px-2.5 text-center font-mono-num font-bold text-slate-600 dark:text-slate-400">
                                {{ $d->urutan }}
                            </td>

                            <!-- Aksi -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('cabang.edit')
                                        <button type="button"
                                            data-kode="{{ Crypt::encrypt($d->kode_cabang) }}"
                                            class="btnEditCabang w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/60 transition flex items-center justify-center cursor-pointer shadow-2xs"
                                            title="Edit Data Cabang">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('cabang.delete')
                                        <form method="POST" action="{{ route('cabang.delete', Crypt::encrypt($d->kode_cabang)) }}"
                                            class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 transition flex items-center justify-center cursor-pointer shadow-2xs"
                                                title="Hapus Cabang">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                        <i data-lucide="building" class="w-5 h-5"></i>
                                    </div>
                                    <span class="text-xs font-semibold">Tidak ada data cabang yang ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION SECTION -->
        @if ($cabang->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $cabang->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- TAILWIND MODAL CABANG (CREATE & EDIT) -->
<div id="modalCabang" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xl overflow-hidden transition-all duration-200">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
            <div class="flex items-center gap-2">
                <i data-lucide="building-2" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i>
                <h4 id="modalTitleCabang" class="text-xs font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wide">
                    Form Cabang
                </h4>
            </div>
            <button type="button" class="btnCloseModalCabang w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Modal Body (AJAX Container) -->
        <div id="modalContentCabang" class="p-4 max-h-[85vh] overflow-y-auto scrollbar-thin">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $modal = $("#modalCabang");
        const $modalContent = $("#modalContentCabang");
        const $modalTitle = $("#modalTitleCabang");

        function openModal(title, url) {
            $modalTitle.text(title);
            $modalContent.html(`
                <div class="p-8 flex flex-col items-center justify-center gap-3 text-slate-500">
                    <svg class="animate-spin h-6 w-6 text-brand-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold">Memuat formulir...</span>
                </div>
            `);
            $modal.removeClass("hidden").addClass("flex");
            $("body").addClass("overflow-hidden");

            $modalContent.load(url, function(response, status) {
                if (status === "error") {
                    $modalContent.html(`
                        <div class="p-6 text-center text-rose-500 text-xs font-semibold">
                            Gagal memuat data. Silakan coba kembali.
                        </div>
                    `);
                } else {
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }

        function closeModal() {
            $modal.addClass("hidden").removeClass("flex");
            $modalContent.html("");
            $("body").removeClass("overflow-hidden");
            if ($.fn.select2) {
                $('.select2-hidden-accessible').select2('close');
            }
        }

        // Close on explicit X button only
        $(".btnCloseModalCabang").on("click", closeModal);
        $(document).on("click", ".btnCancelModal", closeModal);

        // Trigger Create
        $("#btnCreateCabang").on("click", function(e) {
            e.preventDefault();
            openModal("Tambah Cabang Baru", "{{ route('cabang.create') }}");
        });

        // Trigger Edit
        $(document).on("click", ".btnEditCabang", function(e) {
            e.preventDefault();
            const kode = $(this).data("kode");
            openModal("Edit Data Cabang", "/cabang/" + kode + "/edit");
        });
    });
</script>
@endpush

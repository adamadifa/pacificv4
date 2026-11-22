@extends('layouts.app')
@section('titlepage', 'Opname Gudang Logistik')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Gudang Logistik</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Opname</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-7xl">

    <!-- PAGE HEADER TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Opname Gudang Logistik</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Manajemen dan pencatatan stok fisik opname gudang logistik per periode.</p>
        </div>
    </div>

    <!-- MODERN NAVIGATION SUBMODULE BAR -->
    <div>
        @include('v2.layouts.navigation_mutasigudanglogistik')
    </div>

    <!-- FILTER SECTION (NO LABEL STANDAR FORM FILTER V2) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3.5 shadow-2xs">
        <form action="{{ route('opgudanglogistik.index') }}" method="GET" class="w-full">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-center">
                
                <!-- Bulan -->
                <div class="sm:col-span-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <select name="bulan" id="bulan_filter"
                            class="w-full h-[36px] pl-9 pr-7 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                            <option value="">Semua Bulan</option>
                            @foreach ($list_bulan as $d)
                                <option value="{{ $d['kode_bulan'] }}" {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}>
                                    {{ $d['nama_bulan'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <!-- Tahun -->
                <div class="sm:col-span-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar-days" class="w-4 h-4"></i>
                        </div>
                        <select name="tahun" id="tahun_filter"
                            class="w-full h-[36px] pl-9 pr-7 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                            <option value="">Semua Tahun</option>
                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                <option value="{{ $t }}" {{ Request('tahun') == $t || (empty(Request('tahun')) && date('Y') == $t) ? 'selected' : '' }}>
                                    {{ $t }}
                                </option>
                            @endfor
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <!-- Kategori -->
                <div class="sm:col-span-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="layers" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_kategori" id="kode_kategori_filter"
                            class="w-full h-[36px] pl-9 pr-7 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategori as $k)
                                <option value="{{ $k->kode_kategori }}" {{ Request('kode_kategori') == $k->kode_kategori ? 'selected' : '' }}>
                                    {{ strtoupper($k->nama_kategori) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit Cari -->
                <div class="sm:col-span-2">
                    <button type="submit"
                        class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 px-4 rounded-lg bg-[#284c9a] hover:bg-blue-800 active:bg-blue-900 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- DATA TABLE CONTAINER (STANDAR V2 THEAD DUA BARIS & KOMPAK ~36px) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead>
                    <!-- Row 1: Title Strip (colspan=6) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="clipboard-list" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">DATA OPNAME GUDANG LOGISTIK</span>
                                </div>
                                @can('opgudanglogistik.create')
                                    <a href="{{ route('opgudanglogistik.create') }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-white hover:bg-blue-50 text-[#284c9a] text-xs font-bold transition shadow-xs cursor-pointer">
                                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                        <span>Tambah Data</span>
                                    </a>
                                @endcan
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Headers -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">BULAN</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-20">TAHUN</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">KATEGORI</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-28">TANGGAL</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-24">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($opname as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <!-- Kode -->
                            <td class="py-1.5 px-3 text-center font-bold font-mono-num text-[#284c9a] dark:text-blue-400">
                                {{ $d->kode_opname }}
                            </td>
                            <!-- Bulan -->
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                                {{ $nama_bulan[$d->bulan] }}
                            </td>
                            <!-- Tahun -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-700 dark:text-slate-300">
                                {{ $d->tahun }}
                            </td>
                            <!-- Kategori -->
                            <td class="py-1.5 px-3 font-medium text-slate-700 dark:text-slate-300">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    {{ $d->nama_kategori }}
                                </span>
                            </td>
                            <!-- Tanggal -->
                            <td class="py-1.5 px-3 text-center text-slate-600 dark:text-slate-400 font-mono-num">
                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                            </td>
                            <!-- Aksi (Kompak w-6 h-6, icon w-3 h-3) -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('opgudanglogistik.edit')
                                        <a href="{{ route('opgudanglogistik.edit', Crypt::encrypt($d->kode_opname)) }}"
                                            class="w-6 h-6 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/40 dark:hover:bg-amber-900/60 dark:text-amber-400 flex items-center justify-center transition shadow-2xs"
                                            title="Edit Opname">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </a>
                                    @endcan

                                    @can('opgudanglogistik.show')
                                        <a href="#" class="btnShow w-6 h-6 rounded-md bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-950/40 dark:hover:bg-blue-900/60 dark:text-blue-400 flex items-center justify-center transition shadow-2xs"
                                            kode_opname="{{ Crypt::encrypt($d->kode_opname) }}"
                                            title="Lihat Detail">
                                            <i data-lucide="file-text" class="w-3 h-3"></i>
                                        </a>
                                    @endcan

                                    @can('opgudanglogistik.delete')
                                        <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                            action="{{ route('opgudanglogistik.delete', Crypt::encrypt($d->kode_opname)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 dark:text-rose-400 flex items-center justify-center transition shadow-2xs cursor-pointer border-0 p-0"
                                                title="Hapus Data">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="clipboard-x" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Data opname gudang logistik tidak ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL DETAIL DIALOG (STANDAR V2 STATIC BACKDROP) -->
<div id="modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
    <div id="modalDialog" class="relative w-full max-w-4xl max-h-[90vh] bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden transition-all duration-150 transform scale-100">
        <!-- Header -->
        <div class="px-5 py-3.5 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between shrink-0 bg-white dark:bg-slate-900">
            <div class="flex items-center gap-2">
                <i data-lucide="clipboard-list" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100">Detail Opname Gudang Logistik</h4>
            </div>
            <button type="button" id="btnModalClose" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <!-- Body -->
        <div class="p-5 overflow-y-auto" id="loadmodal">
            <!-- Dynamic Content -->
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $modal = $("#modal");
        const $modalDialog = $("#modalDialog");

        function openModal() {
            $modal.removeClass("hidden");
            $("body").addClass("overflow-hidden");
        }

        function closeModal() {
            $modal.addClass("hidden");
            $("body").removeClass("overflow-hidden");
            $("#loadmodal").html("");
        }

        $("#btnModalClose").on("click", function() {
            closeModal();
        });

        // Static Backdrop: animate bounce on backdrop click or Escape
        $modal.on("click", function(e) {
            if ($(e.target).is($modal)) {
                $modalDialog.addClass("scale-[1.015] shadow-2xl");
                setTimeout(() => {
                    $modalDialog.removeClass("scale-[1.015] shadow-2xl");
                }, 150);
            }
        });

        $(document).on("keydown", function(e) {
            if (e.key === "Escape" && !$modal.hasClass("hidden")) {
                $modalDialog.addClass("scale-[1.015] shadow-2xl");
                setTimeout(() => {
                    $modalDialog.removeClass("scale-[1.015] shadow-2xl");
                }, 150);
            }
        });

        $(".btnShow").on("click", function(e) {
            e.preventDefault();
            const kode_opname = $(this).attr("kode_opname");
            openModal();
            $("#loadmodal").html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2 text-slate-400">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold">Memuat rincian opname...</span>
                </div>
            `);
            $("#loadmodal").load('/opgudanglogistik/' + kode_opname + '/show');
        });
    });
</script>
@endpush

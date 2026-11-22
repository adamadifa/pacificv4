@extends('layouts.app')
@section('titlepage', 'Salesman')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Data Master</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Salesman</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 max-w-7xl">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Salesman</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola data tenaga penjualan, wilayah cabang, komisi, dan marker GPS.</p>
        </div>

        @can('salesman.create')
            <button type="button" id="btnCreateSalesman"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Salesman</span>
            </button>
        @endcan
    </div>

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('salesman.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
            
            <!-- Cari Nama Salesman -->
            <div class="relative flex-1 w-full sm:w-auto">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="nama_salesman" id="nama_salesman_search" value="{{ request('nama_salesman') }}"
                    class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Cari nama salesman...">
            </div>

            <!-- Filter Cabang (Jika Berhak) -->
            @hasanyrole(config('global.roles_show_cabang'))
                <div class="relative w-full sm:w-48">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_search"
                        class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>
                                {{ $c->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            @endhasanyrole

            <!-- Filter Status Aktif -->
            <div class="relative w-full sm:w-40">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                </div>
                <select name="status_aktif_salesman" id="status_aktif_salesman_search"
                    class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status_aktif_salesman') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status_aktif_salesman') === '0' ? 'selected' : '' }}>Non Aktif</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>

                @if (request('nama_salesman') || request('kode_cabang') || request('status_aktif_salesman') !== null)
                    <a href="{{ route('salesman.index') }}"
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
                    <!-- Row 1: Title Strip (colspan=11) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="11" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="user-check" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Tenaga Salesman</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                    Total {{ method_exists($salesman, 'total') ? $salesman->total() : $salesman->count() }} Data
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2.5 px-2 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">MARKER</th>
                        <th class="py-2.5 px-3 w-24 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">NAMA SALESMAN</th>
                        <th class="py-2.5 px-3 text-center w-20 font-bold uppercase tracking-wider text-white/90 border-none">CABANG</th>
                        <th class="py-2.5 px-3 w-28 font-bold uppercase tracking-wider text-white/90 border-none">KATEGORI</th>
                        <th class="py-2.5 px-3 w-32 font-bold uppercase tracking-wider text-white/90 border-none">NO. HP</th>
                        <th class="py-2.5 px-4 w-48 font-bold uppercase tracking-wider text-white/90 border-none">ALAMAT</th>
                        <th class="py-2.5 px-3 text-center w-20 font-bold uppercase tracking-wider text-white/90 border-none">KOMISI</th>
                        <th class="py-2.5 px-3 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">STATUS</th>
                        <th class="py-2.5 px-3 text-center w-20 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($salesman as $d)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100">
                            <!-- No -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-400 border-r border-slate-100 dark:border-slate-800/60 text-[11px]">
                                {{ $loop->iteration + $salesman->firstItem() - 1 }}
                            </td>

                            <!-- Marker / Foto -->
                            <td class="py-1.5 px-2 text-center">
                                @if (!empty($d->marker))
                                    <div class="w-6 h-6 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700 mx-auto shadow-2xs">
                                        <img src="{{ getdocMarker($d->marker) }}" alt="Marker" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto text-[10px]">
                                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                    </div>
                                @endif
                            </td>

                            <!-- Kode Salesman -->
                            <td class="py-1.5 px-3 font-mono-num font-bold text-brand-600 dark:text-brand-400 text-xs">
                                <span class="px-2 py-0.5 rounded-md bg-brand-50 dark:bg-brand-950/50 border border-brand-200/60 dark:border-brand-800/60">
                                    {{ $d->kode_salesman }}
                                </span>
                            </td>

                            <!-- Nama Salesman -->
                            <td class="py-1.5 px-4 font-semibold text-slate-800 dark:text-slate-100 text-xs">
                                {{ textCamelCase($d->nama_salesman) }}
                            </td>

                            <!-- Cabang -->
                            <td class="py-1.5 px-3 text-center">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    {{ $d->kode_cabang }}
                                </span>
                            </td>

                            <!-- Kategori -->
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300 text-xs font-medium">
                                {{ $d->nama_kategori_salesman }}
                            </td>

                            <!-- No HP -->
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300 text-xs">
                                <div class="flex items-center gap-1">
                                    <i data-lucide="phone" class="w-3 h-3 text-slate-400 shrink-0"></i>
                                    <span>{{ $d->no_hp_salesman }}</span>
                                </div>
                            </td>

                            <!-- Alamat -->
                            <td class="py-1.5 px-4 text-slate-600 dark:text-slate-300 text-xs truncate max-w-[200px]" title="{{ $d->alamat_salesman }}">
                                {{ textCamelCase($d->alamat_salesman) }}
                            </td>

                            <!-- Status Komisi -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status_komisi_salesman == '1')
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                        Ya
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-50 dark:bg-slate-800 text-slate-500 border border-slate-200 dark:border-slate-700">
                                        Tidak
                                    </span>
                                @endif
                            </td>

                            <!-- Status Aktif -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status_aktif_salesman == '1')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Non Aktif
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('salesman.edit')
                                        <button type="button"
                                            class="btnEditSalesman w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/60 transition flex items-center justify-center cursor-pointer shadow-2xs"
                                            title="Edit Salesman"
                                            data-kode="{{ Crypt::encrypt($d->kode_salesman) }}">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('salesman.delete')
                                        <form method="POST" action="{{ route('salesman.delete', Crypt::encrypt($d->kode_salesman)) }}"
                                            class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 transition flex items-center justify-center cursor-pointer shadow-2xs"
                                                title="Hapus Salesman">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800/80 flex items-center justify-center text-slate-400">
                                        <i data-lucide="users-round" class="w-6 h-6"></i>
                                    </div>
                                    <p class="text-xs font-semibold">Belum ada data salesman.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION (STANDARD V2 COMPONENT) -->
        @if ($salesman->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $salesman->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- TAILWIND MODAL FORM -->
<div id="modalSalesman" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xl overflow-hidden transition-all duration-200">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
            <div class="flex items-center gap-2">
                <i data-lucide="user-check" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i>
                <h4 id="modalTitleSalesman" class="text-xs font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wide">
                    Form Salesman
                </h4>
            </div>
            <button type="button" id="btnCloseModalSalesman"
                class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Modal Body (AJAX Container) -->
        <div id="modalContentSalesman" class="p-4 max-h-[85vh] overflow-y-auto">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        const $modal = $("#modalSalesman");
        const $modalContent = $("#modalContentSalesman");
        const $modalTitle = $("#modalTitleSalesman");

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
            if ($.fn.select2) {
                $('.select2-hidden-accessible').select2('close');
            }
        }

        $("#btnCloseModalSalesman").on("click", closeModal);

        // Static Backdrop: Explicit close only

        // Create Button
        $("#btnCreateSalesman").on("click", function(e) {
            e.preventDefault();
            openModal("Tambah Data Salesman");
            $modalContent.load('/salesman/create', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Edit Button
        $(document).on("click", ".btnEditSalesman", function(e) {
            e.preventDefault();
            const kode = $(this).data("kode");
            openModal("Edit Data Salesman");
            $modalContent.load('/salesman/' + kode + '/edit', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush

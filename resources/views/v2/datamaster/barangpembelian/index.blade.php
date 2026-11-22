@extends('layouts.app')
@section('titlepage', 'Barang Pembelian')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Data Master</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Barang Pembelian</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Barang Pembelian</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola master data barang pembelian, satuan, kategori, dan group operasional.</p>
        </div>

        @can('barangpembelian.create')
            <button type="button" id="btnCreateBarangpembelian"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Barang</span>
            </button>
        @endcan
    </div>

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('barangpembelian.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
            
            <!-- Input Cari Nama Barang -->
            <div class="relative flex-1 w-full sm:w-auto">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="nama_barang" id="nama_barang_search" value="{{ request('nama_barang') }}"
                    class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Cari nama barang pembelian...">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <!-- Submit Button -->
                <button type="submit"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>

                <!-- Reset Button -->
                @if (request('nama_barang'))
                    <a href="{{ route('barangpembelian.index') }}"
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
                        <th colspan="9" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="package" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Barang Pembelian</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                    Total {{ method_exists($barang, 'total') ? $barang->total() : $barang->count() }} Data
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2.5 px-3 w-32 font-bold uppercase tracking-wider text-white/90 border-none">KODE BARANG</th>
                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">NAMA BARANG</th>
                        <th class="py-2.5 px-3 w-20 text-center font-bold uppercase tracking-wider text-white/90 border-none">SATUAN</th>
                        <th class="py-2.5 px-3.5 w-40 font-bold uppercase tracking-wider text-white/90 border-none">JENIS BARANG</th>
                        <th class="py-2.5 px-3.5 w-44 font-bold uppercase tracking-wider text-white/90 border-none">KATEGORI</th>
                        <th class="py-2.5 px-3.5 w-44 font-bold uppercase tracking-wider text-white/90 border-none">GROUP</th>
                        <th class="py-2.5 px-3 w-24 text-center font-bold uppercase tracking-wider text-white/90 border-none">STATUS</th>
                        <th class="py-2.5 px-3 text-center w-20 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($barang as $d)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100">
                            <!-- No -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-400 border-r border-slate-100 dark:border-slate-800/60 text-[11px]">
                                {{ $loop->iteration + $barang->firstItem() - 1 }}
                            </td>
                            <!-- Kode Barang -->
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800/60">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-black font-mono-num bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
                                    {{ $d->kode_barang }}
                                </span>
                            </td>
                            <!-- Nama Barang -->
                            <td class="py-1.5 px-4 font-semibold text-slate-800 dark:text-slate-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></div>
                                    <span>{{ textUpperCase($d->nama_barang) }}</span>
                                </div>
                            </td>
                            <!-- Satuan -->
                            <td class="py-1.5 px-3 text-center border-r border-slate-100 dark:border-slate-800/60">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 uppercase">
                                    {{ $d->satuan }}
                                </span>
                            </td>
                            <!-- Jenis Barang -->
                            <td class="py-1.5 px-3.5 text-slate-700 dark:text-slate-300">
                                {{ textUpperCase($jenis_barang[$d->kode_jenis_barang] ?? '-') }}
                            </td>
                            <!-- Kategori -->
                            <td class="py-1.5 px-3.5 text-slate-700 dark:text-slate-300">
                                {{ textUpperCase($d->nama_kategori ?? '-') }}
                            </td>
                            <!-- Group -->
                            <td class="py-1.5 px-3.5 text-slate-700 dark:text-slate-300 font-semibold">
                                {{ textUpperCase($group[$d->kode_group] ?? '-') }}
                            </td>
                            <!-- Status -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status === '1')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                        Non Aktif
                                    </span>
                                @endif
                            </td>
                            <!-- Aksi -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('barangpembelian.edit')
                                        <button type="button" 
                                            class="editBarangpembelian inline-flex items-center justify-center w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white transition shadow-2xs cursor-pointer"
                                            kode_barang="{{ Crypt::encrypt($d->kode_barang) }}"
                                            title="Edit Barang">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('barangpembelian.delete')
                                        <form method="POST" action="{{ route('barangpembelian.delete', Crypt::encrypt($d->kode_barang)) }}" class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="delete-confirm inline-flex items-center justify-center w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition shadow-2xs cursor-pointer"
                                                title="Hapus Barang">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="package" class="w-8 h-8 text-slate-300 dark:text-slate-600 stroke-[1.5]"></i>
                                    <span class="text-xs font-semibold">Belum ada data barang pembelian</span>
                                    <span class="text-[10px] text-slate-400">Silakan tambahkan data baru atau sesuaikan filter pencarian.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($barang->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $barang->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Container -->
<div id="modalBarangpembelian" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all duration-200 my-8">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                    <i data-lucide="package" class="w-4 h-4"></i>
                </div>
                <h4 id="modalTitleBarangpembelian" class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                    Form Barang Pembelian
                </h4>
            </div>
            <button type="button" class="btn-close-modal text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <!-- Modal Body Content -->
        <div id="modalBodyBarangpembelian" class="p-4 max-h-[80vh] overflow-y-auto scrollbar-thin">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        const $modal = $("#modalBarangpembelian");
        const $modalTitle = $("#modalTitleBarangpembelian");
        const $modalBody = $("#modalBodyBarangpembelian");

        function openModal(title) {
            $modalTitle.text(title);
            $modalBody.html(`
                <div class="py-10 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-slate-400">Memuat formulir barang...</span>
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
        $("#btnCreateBarangpembelian").on("click", function(e) {
            e.preventDefault();
            openModal("Tambah Barang Pembelian");
            $modalBody.load("{{ route('barangpembelian.create') }}", function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Trigger Edit
        $(document).on("click", ".editBarangpembelian", function(e) {
            e.preventDefault();
            const kode = $(this).attr("kode_barang");
            openModal("Edit Barang Pembelian");
            $modalBody.load(`/barangpembelian/${kode}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush
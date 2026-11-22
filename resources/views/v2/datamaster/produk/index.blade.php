@extends('layouts.app')
@section('titlepage', 'Produk')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Data Master</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Produk</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Master Produk</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola daftar item produk, konversi kemasan (dus/pack/pcs), dan kategori diskon.</p>
        </div>

        @can('produk.create')
            <button type="button" id="btncreateProduk"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Produk</span>
            </button>
        @endcan
    </div>

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('produk.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
            
            <!-- Input Cari Nama Produk -->
            <div class="relative flex-1 w-full sm:w-auto">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="nama_produk" id="nama_produk_search" value="{{ request('nama_produk') }}"
                    class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Cari nama produk atau kode produk...">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <!-- Submit Button -->
                <button type="submit"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>

                <!-- Reset Button -->
                @if (request('nama_produk'))
                    <a href="{{ route('produk.index') }}"
                        class="inline-flex items-center justify-center gap-1 h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- MAIN DATA TABLE CARD WITH UNIFIED PACIFIC NAVY HEADER -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-xs text-left border-collapse font-sans whitespace-nowrap">
                <!-- Seamless Unified Header -->
                <thead>
                    <!-- Row 1: Integrated Title Bar -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="13" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="box" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Produk Perusahaan</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                    Total {{ count($produk) }} Item
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none sticky left-0 z-10 bg-[#1e3a78]">NO</th>
                        <th class="py-2.5 px-3 w-28 font-bold uppercase tracking-wider text-white/90 border-none sticky left-12 z-10 bg-[#1e3a78]">KODE</th>
                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none sticky left-40 z-10 bg-[#1e3a78]">NAMA PRODUK</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">SATUAN</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none">PCS / DUS</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none">PACK / DUS</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none">PCS / PACK</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">JENIS</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">KATEGORI</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">SKU</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">DISKON</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none">STATUS</th>
                        <th class="py-2.5 px-3 text-center w-20 font-bold uppercase tracking-wider text-white/90 border-none sticky right-0 z-10 bg-[#1e3a78]">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($produk as $d)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100 group">
                            <!-- No (Sticky) -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-400 border-r border-slate-100 dark:border-slate-800/60 text-[11px] sticky left-0 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/80 z-10">
                                {{ $loop->iteration }}
                            </td>
                            <!-- Kode (Sticky) -->
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800/60 sticky left-12 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/80 z-10">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-black font-mono-num bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
                                    {{ $d->kode_produk }}
                                </span>
                            </td>
                            <!-- Nama Produk (Sticky) -->
                            <td class="py-1.5 px-3.5 font-semibold text-slate-800 dark:text-slate-100 border-r border-slate-100 dark:border-slate-800/60 sticky left-40 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/80 z-10">
                                {{ $d->nama_produk }}
                            </td>
                            <!-- Satuan -->
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300 border-r border-slate-100 dark:border-slate-800/60">
                                {{ $d->satuan }}
                            </td>
                            <!-- Konversi Kemasan -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-700 dark:text-slate-300 border-r border-slate-100 dark:border-slate-800/60 font-semibold">
                                {{ number_format($d->isi_pcs_dus) }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-700 dark:text-slate-300 border-r border-slate-100 dark:border-slate-800/60 font-semibold">
                                {{ number_format($d->isi_pack_dus) }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-700 dark:text-slate-300 border-r border-slate-100 dark:border-slate-800/60 font-semibold">
                                {{ number_format($d->isi_pcs_pack) }}
                            </td>
                            <!-- Klasifikasi -->
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300 border-r border-slate-100 dark:border-slate-800/60">
                                {{ $d->nama_jenis_produk }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300 border-r border-slate-100 dark:border-slate-800/60">
                                {{ $d->nama_kategori_produk }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300 border-r border-slate-100 dark:border-slate-800/60 font-mono-num">
                                {{ $d->kode_sku }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300 border-r border-slate-100 dark:border-slate-800/60">
                                {{ $d->nama_kategori ?? '-' }}
                            </td>
                            <!-- Status -->
                            <td class="py-1.5 px-3 text-center border-r border-slate-100 dark:border-slate-800/60">
                                @if ($d->status_aktif_produk == 1)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200/60 dark:border-rose-800/60">
                                        Non Aktif
                                    </span>
                                @endif
                            </td>
                            <!-- Aksi (Sticky Right) -->
                            <td class="py-1.5 px-3 text-center sticky right-0 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/80 z-10 shadow-[-4px_0_6px_-2px_rgba(0,0,0,0.05)] dark:shadow-[-4px_0_6px_-2px_rgba(0,0,0,0.3)]">
                                <div class="flex items-center justify-center gap-1">
                                    @can('produk.edit')
                                        <button type="button" 
                                            class="editProduk inline-flex items-center justify-center w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white transition shadow-2xs cursor-pointer"
                                            kode_produk="{{ Crypt::encrypt($d->kode_produk) }}"
                                            title="Edit Produk">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('produk.delete')
                                        <form method="POST" action="{{ route('produk.delete', Crypt::encrypt($d->kode_produk)) }}" class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm inline-flex items-center justify-center w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition shadow-2xs cursor-pointer"
                                                data-name="{{ $d->nama_produk }}"
                                                title="Hapus Produk">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="py-12 text-center text-slate-400">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto text-slate-300 dark:text-slate-600 mb-2"></i>
                                <span class="text-xs font-semibold">Belum ada data produk</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

<!-- Modal Container -->
<div id="modalProduk" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all duration-200 my-8">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                    <i data-lucide="package" class="w-4 h-4"></i>
                </div>
                <h4 id="modalTitleProduk" class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                    Form Produk
                </h4>
            </div>
            <button type="button" class="btn-close-modal text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <!-- Modal Body Content -->
        <div id="modalBodyProduk" class="p-4 max-h-[80vh] overflow-y-auto scrollbar-thin">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        const $modal = $("#modalProduk");
        const $modalTitle = $("#modalTitleProduk");
        const $modalBody = $("#modalBodyProduk");

        function openModal(title) {
            $modalTitle.text(title);
            $modalBody.html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-slate-400">Memuat formulir produk...</span>
                </div>
            `);
            $modal.removeClass("hidden").addClass("flex");
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function closeModal() {
            if ($.fn.select2) {
                $('.select2-hidden-accessible').select2('close');
            }
            $modal.addClass("hidden").removeClass("flex");
            $modalBody.empty();
        }

        // Close on button click or outside click
        $modal.on("click", ".btn-close-modal", closeModal);
        // Static Backdrop: Explicit close only

        // Modal Tambah
        $("#btncreateProduk").click(function(e) {
            e.preventDefault();
            openModal("Tambah Produk");
            $modalBody.load('/produk/create', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Modal Edit
        $(document).on('click', '.editProduk', function(e) {
            e.preventDefault();
            var kode_produk = $(this).attr("kode_produk");
            openModal("Edit Produk");
            $modalBody.load('/produk/' + kode_produk + '/edit', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush

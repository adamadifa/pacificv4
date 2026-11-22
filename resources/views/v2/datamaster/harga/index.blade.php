@extends('layouts.app')
@section('titlepage', 'Harga')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Data Master</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Harga</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Master Harga</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola tarif harga produk per cabang, kategori salesman, dan status PPN / promo.</p>
        </div>

        @can('harga.create')
            <button type="button" id="btncreateHarga"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Harga</span>
            </button>
        @endcan
    </div>

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('harga.index') }}" method="GET" class="flex flex-col lg:flex-row items-center gap-2">
            
            <!-- Cari Nama Produk -->
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="nama_produk" id="nama_produk_search" value="{{ request('nama_produk') }}"
                    class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Cari nama produk / kode harga...">
            </div>

            <!-- Filter Cabang -->
            @hasanyrole($roles_show_cabang)
                <div class="relative w-full lg:w-44">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_search"
                        class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>
                                {{ $c->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            @endhasanyrole

            <!-- Filter Kategori Salesman -->
            <div class="relative w-full lg:w-44">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_kategori_salesman" id="kode_kategori_salesman_search"
                    class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategorisalesman as $ks)
                        <option value="{{ $ks->kode_kategori_salesman }}" {{ request('kode_kategori_salesman') == $ks->kode_kategori_salesman ? 'selected' : '' }}>
                            {{ $ks->nama_kategori_salesman }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>

            <!-- Filter Status -->
            <div class="relative w-full lg:w-36">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="toggle-left" class="w-3.5 h-3.5"></i>
                </div>
                <select name="status_aktif_harga" id="status_aktif_harga_search"
                    class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status_aktif_harga') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status_aktif_harga') === '0' ? 'selected' : '' }}>Non Aktif</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full lg:w-auto">
                <!-- Submit Button -->
                <button type="submit"
                    class="flex-1 lg:flex-initial inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>

                <!-- Reset Button -->
                @if (request('nama_produk') || request('kode_cabang') || request('kode_kategori_salesman') || request('status_aktif_harga') !== null)
                    <a href="{{ route('harga.index') }}"
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
                                    <i data-lucide="dollar-sign" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Harga Produk</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                    Total {{ method_exists($harga, 'total') ? $harga->total() : $harga->count() }} Data
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
                        <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none">HARGA / DUS</th>
                        <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none">HARGA / PACK</th>
                        <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none">HARGA / PCS</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none">KATEGORI</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none">PROMO</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none">PPN</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none">STATUS</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none">CABANG</th>
                        <th class="py-2.5 px-3 text-center w-20 font-bold uppercase tracking-wider text-white/90 border-none sticky right-0 z-10 bg-[#1e3a78]">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($harga as $d)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100 group">
                            <!-- No (Sticky) -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-400 border-r border-slate-100 dark:border-slate-800/60 text-[11px] sticky left-0 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/80 z-10">
                                {{ $loop->iteration + $harga->firstItem() - 1 }}
                            </td>
                            <!-- Kode (Sticky) -->
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800/60 sticky left-12 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/80 z-10">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-black font-mono-num bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
                                    {{ $d->kode_harga }}
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
                            <!-- Harga Dus -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-brand-600 dark:text-brand-400 border-r border-slate-100 dark:border-slate-800/60">
                                {{ formatRupiah($d->harga_dus) }}
                            </td>
                            <!-- Harga Pack -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-sky-600 dark:text-sky-400 border-r border-slate-100 dark:border-slate-800/60">
                                {{ formatRupiah($d->harga_pack) }}
                            </td>
                            <!-- Harga Pcs -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400 border-r border-slate-100 dark:border-slate-800/60">
                                {{ formatRupiah($d->harga_pcs) }}
                            </td>
                            <!-- Kategori Salesman -->
                            <td class="py-1.5 px-3 text-center border-r border-slate-100 dark:border-slate-800/60">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                    {{ $d->kode_kategori_salesman }}
                                </span>
                            </td>
                            <!-- Promo -->
                            <td class="py-1.5 px-3 text-center border-r border-slate-100 dark:border-slate-800/60">
                                @if ($d->status_promo == 1)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">
                                        <i data-lucide="check" class="w-3 h-3 mr-0.5"></i> Promo
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[11px]">-</span>
                                @endif
                            </td>
                            <!-- PPN -->
                            <td class="py-1.5 px-3 text-center border-r border-slate-100 dark:border-slate-800/60">
                                @if ($d->status_ppn == 'IN')
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">
                                        INCLUDE
                                    </span>
                                @elseif($d->status_ppn == 'EX')
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400">
                                        EXCLUDE
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[11px]">-</span>
                                @endif
                            </td>
                            <!-- Status -->
                            <td class="py-1.5 px-3 text-center border-r border-slate-100 dark:border-slate-800/60">
                                @if ($d->status_aktif_harga == 1)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200/60 dark:border-rose-800/60">
                                        Non Aktif
                                    </span>
                                @endif
                            </td>
                            <!-- Cabang -->
                            <td class="py-1.5 px-3 text-center border-r border-slate-100 dark:border-slate-800/60">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                    {{ $d->kode_cabang }}
                                </span>
                            </td>
                            <!-- Aksi (Sticky Right) -->
                            <td class="py-1.5 px-3 text-center sticky right-0 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/80 z-10 shadow-[-4px_0_6px_-2px_rgba(0,0,0,0.05)] dark:shadow-[-4px_0_6px_-2px_rgba(0,0,0,0.3)]">
                                <div class="flex items-center justify-center gap-1">
                                    @can('harga.edit')
                                        <button type="button" 
                                            class="editHarga inline-flex items-center justify-center w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white transition shadow-2xs cursor-pointer"
                                            kode_harga="{{ Crypt::encrypt($d->kode_harga) }}"
                                            title="Edit Harga">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('harga.delete')
                                        <form method="POST" action="{{ route('harga.delete', Crypt::encrypt($d->kode_harga)) }}" class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm inline-flex items-center justify-center w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition shadow-2xs cursor-pointer"
                                                data-name="Harga {{ $d->nama_produk }} ({{ $d->kode_harga }})"
                                                title="Hapus Harga">
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
                                <span class="text-xs font-semibold">Belum ada data harga produk</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($harga->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $harga->links('v2.components.pagination') }}
            </div>
        @endif

    </div>

</div>

<!-- Modal Container -->
<div id="modalHarga" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all duration-200 my-8">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                </div>
                <h4 id="modalTitleHarga" class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                    Form Harga Produk
                </h4>
            </div>
            <button type="button" class="btn-close-modal text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <!-- Modal Body Content -->
        <div id="modalBodyHarga" class="p-4 max-h-[80vh] overflow-y-auto scrollbar-thin">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        const $modal = $("#modalHarga");
        const $modalTitle = $("#modalTitleHarga");
        const $modalBody = $("#modalBodyHarga");

        function openModal(title) {
            $modalTitle.text(title);
            $modalBody.html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-slate-400">Memuat formulir harga...</span>
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
        $("#btncreateHarga").click(function(e) {
            e.preventDefault();
            openModal("Tambah Harga Produk");
            $modalBody.load('/harga/create', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Modal Edit
        $(document).on('click', '.editHarga', function(e) {
            e.preventDefault();
            var kode_harga = $(this).attr("kode_harga");
            openModal("Edit Harga Produk");
            $modalBody.load('/harga/' + kode_harga + '/edit', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush

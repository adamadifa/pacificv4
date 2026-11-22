@extends('layouts.app')
@section('titlepage', 'Kendaraan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Data Master</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Kendaraan</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Kendaraan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola armada kendaraan operasional, masa berlaku pajak, dan KIR.</p>
        </div>

        @can('kendaraan.create')
            <button type="button" id="btnCreateKendaraan"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Kendaraan</span>
            </button>
        @endcan
    </div>

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('kendaraan.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
            
            <!-- Input Cari No. Polisi -->
            <div class="relative flex-1 w-full sm:w-auto">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="no_polisi" id="no_polisi_search" value="{{ request('no_polisi') }}"
                    class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Cari no. polisi kendaraan...">
            </div>

            <!-- Filter Cabang -->
            @hasanyrole($roles_show_cabang)
                <div class="relative w-full sm:w-48">
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

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <!-- Submit Button -->
                <button type="submit"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>

                <!-- Reset Button -->
                @if (request('no_polisi') || request('kode_cabang'))
                    <a href="{{ route('kendaraan.index') }}"
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
                        <th colspan="12" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="truck" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Armada Kendaraan</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                    Total {{ method_exists($kendaraan, 'total') ? $kendaraan->total() : $kendaraan->count() }} Kendaraan
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2.5 px-3 w-28 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2.5 px-3 w-32 font-bold uppercase tracking-wider text-white/90 border-none">NO. POLISI</th>
                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">MERK / TIPE</th>
                        <th class="py-2.5 px-3 w-20 text-center font-bold uppercase tracking-wider text-white/90 border-none">TAHUN</th>
                        <th class="py-2.5 px-3 w-28 text-center font-bold uppercase tracking-wider text-white/90 border-none">KIR</th>
                        <th class="py-2.5 px-3 w-28 text-center font-bold uppercase tracking-wider text-white/90 border-none">PAJAK 1 TH</th>
                        <th class="py-2.5 px-3 w-28 text-center font-bold uppercase tracking-wider text-white/90 border-none">PAJAK 5 TH</th>
                        <th class="py-2.5 px-3 w-24 text-center font-bold uppercase tracking-wider text-white/90 border-none">CABANG</th>
                        <th class="py-2.5 px-3 w-28 text-right font-bold uppercase tracking-wider text-white/90 border-none">KAPASITAS</th>
                        <th class="py-2.5 px-3 w-20 text-center font-bold uppercase tracking-wider text-white/90 border-none">STATUS</th>
                        <th class="py-2.5 px-3 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($kendaraan as $d)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100">
                            <!-- No -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-400 border-r border-slate-100 dark:border-slate-800/60 text-[11px]">
                                {{ $loop->iteration + $kendaraan->firstItem() - 1 }}
                            </td>
                            <!-- Kode -->
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800/60">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-black font-mono-num bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
                                    {{ $d->kode_kendaraan }}
                                </span>
                            </td>
                            <!-- No. Polisi -->
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800/60">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-black tracking-wide bg-slate-900 text-amber-300 font-mono-num shadow-2xs">
                                    {{ $d->no_polisi }}
                                </span>
                            </td>
                            <!-- Merk / Tipe -->
                            <td class="py-1.5 px-4 text-slate-800 dark:text-slate-100">
                                <span class="font-bold text-xs">{{ $d->merek ?? '-' }}</span>
                                @if ($d->tipe_kendaraan || $d->tipe)
                                    <span class="text-[11px] text-slate-400 font-medium">({{ $d->tipe_kendaraan ?? '' }}{{ $d->tipe ? ' - ' . $d->tipe : '' }})</span>
                                @endif
                            </td>
                            <!-- Tahun -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400 text-xs">
                                {{ $d->tahun_pembuatan ?? '-' }}
                            </td>
                            <!-- KIR -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-[11px]">
                                @if (!empty($d->jatuhtempo_kir))
                                    <span class="text-slate-600 dark:text-slate-300">{{ date('d/m/Y', strtotime($d->jatuhtempo_kir)) }}</span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600">-</span>
                                @endif
                            </td>
                            <!-- Pajak 1 Th -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-[11px]">
                                @if (!empty($d->jatuhtempo_pajak_satutahun))
                                    @php
                                        $diffDays = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($d->jatuhtempo_pajak_satutahun), false);
                                    @endphp
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded {{ $diffDays < 30 ? 'bg-rose-50 text-rose-600 font-bold dark:bg-rose-950/60 dark:text-rose-400' : 'text-slate-600 dark:text-slate-300' }}">
                                        {{ date('d/m/Y', strtotime($d->jatuhtempo_pajak_satutahun)) }}
                                    </span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600">-</span>
                                @endif
                            </td>
                            <!-- Pajak 5 Th -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-[11px]">
                                @if (!empty($d->jatuhtempo_pajak_limatahun))
                                    <span class="text-slate-600 dark:text-slate-300">{{ date('d/m/Y', strtotime($d->jatuhtempo_pajak_limatahun)) }}</span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600">-</span>
                                @endif
                            </td>
                            <!-- Cabang -->
                            <td class="py-1.5 px-3 text-center border-r border-slate-100 dark:border-slate-800/60">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                    {{ $d->kode_cabang }}
                                </span>
                            </td>
                            <!-- Kapasitas -->
                            <td class="py-1.5 px-3 text-right font-mono-num text-xs font-semibold text-slate-700 dark:text-slate-200">
                                {{ formatRupiah($d->kapasitas) }}
                            </td>
                            <!-- Status -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status_aktif_kendaraan == 1)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <!-- Aksi -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('kendaraan.edit')
                                        <button type="button" 
                                            class="editKendaraan inline-flex items-center justify-center w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white transition shadow-2xs cursor-pointer"
                                            kode_kendaraan="{{ Crypt::encrypt($d->kode_kendaraan) }}"
                                            title="Edit Kendaraan">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('kendaraan.show')
                                        <a href="{{ route('kendaraan.show', Crypt::encrypt($d->kode_kendaraan)) }}"
                                            class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 hover:bg-blue-500 hover:text-white transition shadow-2xs cursor-pointer"
                                            title="Detail Kendaraan">
                                            <i data-lucide="file-text" class="w-3 h-3"></i>
                                        </a>
                                    @endcan

                                    @can('kendaraan.delete')
                                        <form method="POST" action="{{ route('kendaraan.delete', Crypt::encrypt($d->kode_kendaraan)) }}" class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="delete-confirm inline-flex items-center justify-center w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition shadow-2xs cursor-pointer"
                                                title="Hapus Kendaraan">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="truck" class="w-8 h-8 text-slate-300 dark:text-slate-600 stroke-[1.5]"></i>
                                    <span class="text-xs font-semibold">Belum ada data kendaraan</span>
                                    <span class="text-[10px] text-slate-400">Silakan tambahkan data kendaraan baru atau gunakan filter lain.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($kendaraan->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $kendaraan->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Container -->
<div id="modalKendaraan" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all duration-200 my-8">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                    <i data-lucide="truck" class="w-4 h-4"></i>
                </div>
                <h4 id="modalTitleKendaraan" class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                    Form Kendaraan
                </h4>
            </div>
            <button type="button" class="btn-close-modal text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <!-- Modal Body Content -->
        <div id="modalBodyKendaraan" class="p-4 max-h-[80vh] overflow-y-auto scrollbar-thin">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        const $modal = $("#modalKendaraan");
        const $modalTitle = $("#modalTitleKendaraan");
        const $modalBody = $("#modalBodyKendaraan");

        function openModal(title) {
            $modalTitle.text(title);
            $modalBody.html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-slate-400">Memuat formulir kendaraan...</span>
                </div>
            `);
            $modal.removeClass("hidden").addClass("flex");
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function closeModal() {
            $modal.addClass("hidden").removeClass("flex");
            $modalBody.empty();
        }

        // Close on button click or outside click
        $modal.on("click", ".btn-close-modal", closeModal);
        // Static Backdrop: Explicit close only

        // Trigger Create
        $("#btnCreateKendaraan").on("click", function(e) {
            e.preventDefault();
            openModal("Tambah Data Kendaraan");
            $modalBody.load("{{ route('kendaraan.create') }}", function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Trigger Edit
        $(document).on("click", ".editKendaraan", function(e) {
            e.preventDefault();
            const kode = $(this).attr("kode_kendaraan");
            openModal("Edit Data Kendaraan");
            $modalBody.load(`/kendaraan/${kode}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush
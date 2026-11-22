@extends('layouts.app')
@section('titlepage', 'Insentif Karyawan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Data Master</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Insentif</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Insentif Karyawan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola data insentif umum dan insentif manager karyawan per periode berlaku.</p>
        </div>

        @can('insentif.create')
            <button type="button" id="btnCreateInsentif"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Insentif</span>
            </button>
        @endcan
    </div>

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('insentif.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2">
            
            <!-- Cari Nama Karyawan -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="nama_karyawan" id="nama_karyawan_search" value="{{ request('nama_karyawan') }}"
                    class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Cari nama karyawan...">
            </div>

            <!-- Filter Cabang -->
            <div class="relative">
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

            <!-- Filter Departemen -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="network" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_dept" id="kode_dept_search"
                    class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                    <option value="">Semua Dept</option>
                    @foreach ($departemen as $d)
                        <option value="{{ $d->kode_dept }}" {{ request('kode_dept') == $d->kode_dept ? 'selected' : '' }}>
                            {{ $d->nama_dept }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>

            <!-- Filter Group -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_group" id="kode_group_search"
                    class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                    <option value="">Semua Group</option>
                    @foreach ($group as $g)
                        <option value="{{ $g->kode_group }}" {{ request('kode_group') == $g->kode_group ? 'selected' : '' }}>
                            {{ $g->nama_group }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit"
                    class="flex-1 inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>

                @if (request('nama_karyawan') || request('kode_cabang') || request('kode_dept') || request('kode_group'))
                    <a href="{{ route('insentif.index') }}"
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
                        <th colspan="14" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="award" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Insentif Umum & Manager</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                    Total {{ method_exists($insentif, 'total') ? $insentif->total() : $insentif->count() }} Data
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Sub-headers for Insentif -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th rowspan="2" class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-r border-white/10">NO</th>
                        <th rowspan="2" class="py-2.5 px-3 w-28 font-bold uppercase tracking-wider text-white/90 border-r border-white/10">KODE</th>
                        <th rowspan="2" class="py-2.5 px-3 w-28 font-bold uppercase tracking-wider text-white/90 border-r border-white/10">NIK</th>
                        <th rowspan="2" class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-r border-white/10">NAMA KARYAWAN</th>
                        <th colspan="4" class="py-1 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-r border-white/10">INSENTIF UMUM</th>
                        <th colspan="4" class="py-1 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-r border-white/10">INSENTIF MANAGER</th>
                        <th rowspan="2" class="py-2.5 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-r border-white/10">BERLAKU</th>
                        <th rowspan="2" class="py-2.5 px-3 text-center w-20 font-bold uppercase tracking-wider text-white/90">AKSI</th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[10px] border-none">
                        <th class="py-1.5 px-2.5 text-right w-24 font-semibold text-white/80 border-r border-white/10">MASA KERJA</th>
                        <th class="py-1.5 px-2.5 text-right w-24 font-semibold text-white/80 border-r border-white/10">LEMBUR</th>
                        <th class="py-1.5 px-2.5 text-right w-24 font-semibold text-white/80 border-r border-white/10">PENEMPATAN</th>
                        <th class="py-1.5 px-2.5 text-right w-24 font-semibold text-white/80 border-r border-white/10">KPI</th>
                        <th class="py-1.5 px-2.5 text-right w-24 font-semibold text-white/80 border-r border-white/10">R. LINGKUP</th>
                        <th class="py-1.5 px-2.5 text-right w-24 font-semibold text-white/80 border-r border-white/10">PENEMPATAN</th>
                        <th class="py-1.5 px-2.5 text-right w-24 font-semibold text-white/80 border-r border-white/10">KINERJA</th>
                        <th class="py-1.5 px-2.5 text-right w-24 font-semibold text-white/80 border-r border-white/10">KENDARAAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($insentif as $d)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100">
                            <!-- No -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-400 border-r border-slate-100 dark:border-slate-800/60 text-[11px]">
                                {{ $loop->iteration + $insentif->firstItem() - 1 }}
                            </td>
                            <!-- Kode -->
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800/60">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-black font-mono-num bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
                                    {{ $d->kode_insentif }}
                                </span>
                            </td>
                            <!-- NIK -->
                            <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-600 dark:text-slate-300 border-r border-slate-100 dark:border-slate-800/60">
                                {{ $d->nik }}
                            </td>
                            <!-- Nama Karyawan -->
                            <td class="py-1.5 px-4 font-semibold text-slate-800 dark:text-slate-100 border-r border-slate-100 dark:border-slate-800/60">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></div>
                                    <span>{{ formatName($d->nama_karyawan) }}</span>
                                </div>
                            </td>
                            <!-- IU Masa Kerja -->
                            <td class="py-1.5 px-2.5 text-right font-mono-num text-slate-600 dark:text-slate-400 border-r border-slate-100 dark:border-slate-800/60">
                                {{ !empty($d->iu_masakerja) ? formatRupiah($d->iu_masakerja) : '-' }}
                            </td>
                            <!-- IU Lembur -->
                            <td class="py-1.5 px-2.5 text-right font-mono-num text-slate-600 dark:text-slate-400 border-r border-slate-100 dark:border-slate-800/60">
                                {{ !empty($d->iu_lembur) ? formatRupiah($d->iu_lembur) : '-' }}
                            </td>
                            <!-- IU Penempatan -->
                            <td class="py-1.5 px-2.5 text-right font-mono-num text-slate-600 dark:text-slate-400 border-r border-slate-100 dark:border-slate-800/60">
                                {{ !empty($d->iu_penempatan) ? formatRupiah($d->iu_penempatan) : '-' }}
                            </td>
                            <!-- IU KPI -->
                            <td class="py-1.5 px-2.5 text-right font-mono-num text-slate-600 dark:text-slate-400 border-r border-slate-100 dark:border-slate-800/60">
                                {{ !empty($d->iu_kpi) ? formatRupiah($d->iu_kpi) : '-' }}
                            </td>
                            <!-- IM Ruang Lingkup -->
                            <td class="py-1.5 px-2.5 text-right font-mono-num text-slate-600 dark:text-slate-400 border-r border-slate-100 dark:border-slate-800/60">
                                {{ !empty($d->im_ruanglingkup) ? formatRupiah($d->im_ruanglingkup) : '-' }}
                            </td>
                            <!-- IM Penempatan -->
                            <td class="py-1.5 px-2.5 text-right font-mono-num text-slate-600 dark:text-slate-400 border-r border-slate-100 dark:border-slate-800/60">
                                {{ !empty($d->im_penempatan) ? formatRupiah($d->im_penempatan) : '-' }}
                            </td>
                            <!-- IM Kinerja -->
                            <td class="py-1.5 px-2.5 text-right font-mono-num text-slate-600 dark:text-slate-400 border-r border-slate-100 dark:border-slate-800/60">
                                {{ !empty($d->im_kinerja) ? formatRupiah($d->im_kinerja) : '-' }}
                            </td>
                            <!-- IM Kendaraan -->
                            <td class="py-1.5 px-2.5 text-right font-mono-num text-slate-600 dark:text-slate-400 border-r border-slate-100 dark:border-slate-800/60">
                                {{ !empty($d->im_kendaraan) ? formatRupiah($d->im_kendaraan) : '-' }}
                            </td>
                            <!-- Berlaku -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400 border-r border-slate-100 dark:border-slate-800/60">
                                {{ date('d-m-Y', strtotime($d->tanggal_berlaku)) }}
                            </td>
                            <!-- Aksi -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->kode_insentif == $d->kode_lastinsentif)
                                    <div class="flex items-center justify-center gap-1">
                                        @can('insentif.edit')
                                            <button type="button" 
                                                class="editInsentif inline-flex items-center justify-center w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white transition shadow-2xs cursor-pointer"
                                                kode_insentif="{{ Crypt::encrypt($d->kode_insentif) }}"
                                                title="Edit Insentif">
                                                <i data-lucide="edit-3" class="w-3 h-3"></i>
                                            </button>
                                        @endcan

                                        @can('insentif.delete')
                                            <form method="POST" action="{{ route('insentif.delete', Crypt::encrypt($d->kode_insentif)) }}" class="inline-flex m-0 p-0 deleteform">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="delete-confirm inline-flex items-center justify-center w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition shadow-2xs cursor-pointer"
                                                    title="Hapus Insentif">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="award" class="w-8 h-8 text-slate-300 dark:text-slate-600 stroke-[1.5]"></i>
                                    <span class="text-xs font-semibold">Belum ada data insentif</span>
                                    <span class="text-[10px] text-slate-400">Silakan tambahkan data insentif baru atau gunakan filter lain.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($insentif->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $insentif->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Container -->
<div id="modalInsentif" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all duration-200 my-8">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                    <i data-lucide="award" class="w-4 h-4"></i>
                </div>
                <h4 id="modalTitleInsentif" class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                    Form Insentif Karyawan
                </h4>
            </div>
            <button type="button" class="btn-close-modal text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <!-- Modal Body Content -->
        <div id="modalBodyInsentif" class="p-4 max-h-[80vh] overflow-y-auto scrollbar-thin">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        const $modal = $("#modalInsentif");
        const $modalTitle = $("#modalTitleInsentif");
        const $modalBody = $("#modalBodyInsentif");

        function openModal(title) {
            $modalTitle.text(title);
            $modalBody.html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-slate-400">Memuat formulir insentif...</span>
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
        $("#btnCreateInsentif").on("click", function(e) {
            e.preventDefault();
            openModal("Tambah Insentif Karyawan");
            $modalBody.load("{{ route('insentif.create') }}", function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Trigger Edit
        $(document).on("click", ".editInsentif", function(e) {
            e.preventDefault();
            const kode = $(this).attr("kode_insentif");
            openModal("Edit Insentif Karyawan");
            $modalBody.load(`/insentif/${kode}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush

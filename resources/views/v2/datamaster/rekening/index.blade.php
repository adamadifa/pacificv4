@extends('layouts.app')
@section('titlepage', 'Rekening Karyawan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Data Master</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Rekening</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Rekening Karyawan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola nomor rekening bank karyawan untuk kebutuhan transfer penggajian.</p>
        </div>
    </div>

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('rekening.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2">
            
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
                    <a href="{{ route('rekening.index') }}"
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
                                    <i data-lucide="credit-card" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Rekening Payroll Karyawan</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                    Total {{ method_exists($rekening, 'total') ? $rekening->total() : $rekening->count() }} Rekening
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2.5 px-3 w-28 font-bold uppercase tracking-wider text-white/90 border-none">NIK</th>
                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">NAMA KARYAWAN</th>
                        <th class="py-2.5 px-3 w-28 text-center font-bold uppercase tracking-wider text-white/90 border-none">DEPT</th>
                        <th class="py-2.5 px-4 w-44 font-bold uppercase tracking-wider text-white/90 border-none">JABATAN</th>
                        <th class="py-2.5 px-3 w-24 text-center font-bold uppercase tracking-wider text-white/90 border-none">PERUSAHAAN</th>
                        <th class="py-2.5 px-3 w-24 text-center font-bold uppercase tracking-wider text-white/90 border-none">CABANG</th>
                        <th class="py-2.5 px-4 w-44 font-bold uppercase tracking-wider text-white/90 border-none">NO. REKENING</th>
                        <th class="py-2.5 px-3 text-center w-20 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($karyawan as $d)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100 {{ $d->status_aktif_karyawan === '0' ? 'bg-rose-50/30 dark:bg-rose-950/20' : '' }}">
                            <!-- No -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-400 border-r border-slate-100 dark:border-slate-800/60 text-[11px]">
                                {{ $loop->iteration + $karyawan->firstItem() - 1 }}
                            </td>
                            <!-- NIK -->
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800/60 font-mono-num font-semibold text-slate-700 dark:text-slate-300">
                                {{ $d->nik }}
                            </td>
                            <!-- Nama Karyawan -->
                            <td class="py-1.5 px-4 font-semibold text-slate-800 dark:text-slate-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $d->status_aktif_karyawan === '0' ? 'bg-rose-400' : 'bg-emerald-500' }} shrink-0"></div>
                                    <span>{{ textCamelCase($d->nama_karyawan) }}</span>
                                </div>
                            </td>
                            <!-- Dept -->
                            <td class="py-1.5 px-3 text-center border-r border-slate-100 dark:border-slate-800/60 font-semibold text-slate-600 dark:text-slate-300">
                                {{ $d->kode_dept }}
                            </td>
                            <!-- Jabatan -->
                            <td class="py-1.5 px-4 text-slate-600 dark:text-slate-300 font-medium">
                                {{ $d->nama_jabatan }}
                            </td>
                            <!-- Perusahaan -->
                            <td class="py-1.5 px-3 text-center border-r border-slate-100 dark:border-slate-800/60">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black {{ $d->kode_perusahaan == 'MP' ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200/60' : 'bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400 border border-blue-200/60' }}">
                                    {{ $d->kode_perusahaan == 'MP' ? 'MP' : 'PCF' }}
                                </span>
                            </td>
                            <!-- Cabang -->
                            <td class="py-1.5 px-3 text-center border-r border-slate-100 dark:border-slate-800/60">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                    {{ $d->kode_cabang }}
                                </span>
                            </td>
                            <!-- No. Rekening -->
                            <td class="py-1.5 px-4 border-r border-slate-100 dark:border-slate-800/60">
                                @if (!empty($d->no_rekening))
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-xs font-black font-mono-num bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-100 border border-slate-200 dark:border-slate-700">
                                        <i data-lucide="credit-card" class="w-3 h-3 text-brand-500"></i>
                                        <span>{{ $d->no_rekening }}</span>
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">- Belum Diisi -</span>
                                @endif
                            </td>
                            <!-- Aksi -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('rekening.edit')
                                        <button type="button" 
                                            class="editRekening inline-flex items-center justify-center w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white transition shadow-2xs cursor-pointer"
                                            nik="{{ Crypt::encrypt($d->nik) }}"
                                            title="Edit Rekening">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="credit-card" class="w-8 h-8 text-slate-300 dark:text-slate-600 stroke-[1.5]"></i>
                                    <span class="text-xs font-semibold">Belum ada data karyawan</span>
                                    <span class="text-[10px] text-slate-400">Silakan sesuaikan filter pencarian.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($karyawan->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $karyawan->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Container -->
<div id="modalRekening" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all duration-200">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                </div>
                <h4 id="modalTitleRekening" class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                    Update Rekening Bank
                </h4>
            </div>
            <button type="button" class="btn-close-modal text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <!-- Modal Body Content -->
        <div id="modalBodyRekening" class="p-4">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        const $modal = $("#modalRekening");
        const $modalTitle = $("#modalTitleRekening");
        const $modalBody = $("#modalBodyRekening");

        function openModal(title) {
            $modalTitle.text(title);
            $modalBody.html(`
                <div class="py-8 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-slate-400">Memuat formulir rekening...</span>
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

        // Trigger Edit
        $(document).on("click", ".editRekening", function(e) {
            e.preventDefault();
            const nik = $(this).attr("nik");
            openModal("Edit Rekening Bank");
            $modalBody.load(`/rekening/${nik}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush
@extends('layouts.app')
@section('titlepage', 'Setting Komisi Driver Helper')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Komisi</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Setting Komisi Driver Helper</span>
    </div>
@endsection

@section('content')
@php
    $roles_show_cabang = config('global.roles_show_cabang');
    $user = auth()->user();
    $hasAccessAllCabang = $user->hasRole($roles_show_cabang);
    $namaBulanArr = config('global.nama_bulan');
@endphp

<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Setting Komisi Driver &amp; Helper</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola konfigurasi parameter perhitungan komisi operasional driver dan helper cabang.</p>
        </div>

        @can('ratiodriverhelper.create')
            <button type="button" id="btnCreate"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Setting Komisi</span>
            </button>
        @endcan
    </div>

    <!-- NAVIGATION TABS TARGET KOMISI -->
    @include('v2.layouts.navigation_targetkomisi')

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ url()->current() }}" method="GET" id="formSearch" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
            
            @if ($hasAccessAllCabang)
                <!-- Filter Cabang (Col-span 5) -->
                <div class="lg:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang_search" id="kode_cabang_search"
                        class="select2Kodecabangsearch select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                                {{ $c->kode_cabang }} - {{ textUpperCase($c->nama_cabang) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Filter Bulan (Col-span 3 / 5) -->
            <div class="{{ $hasAccessAllCabang ? 'lg:col-span-3' : 'lg:col-span-5' }} relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <select name="bulan" id="bulan_filter"
                    class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Semua Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}"
                            {{ !empty(Request('bulan')) ? (Request('bulan') == $d['kode_bulan'] ? 'selected' : '') : (date('m') == $d['kode_bulan'] ? 'selected' : '') }}>
                            {{ $d['nama_bulan'] }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3 h-3"></i>
                </div>
            </div>

            <!-- Filter Tahun (Col-span 2 / 5) -->
            <div class="{{ $hasAccessAllCabang ? 'lg:col-span-2' : 'lg:col-span-5' }} relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                </div>
                <select name="tahun" id="tahun_filter"
                    class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Semua Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}"
                            {{ !empty(Request('tahun')) ? (Request('tahun') == $t ? 'selected' : '') : (date('Y') == $t ? 'selected' : '') }}>
                            {{ $t }}
                        </option>
                    @endfor
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3 h-3"></i>
                </div>
            </div>

            <!-- Tombol Cari & Reset (Col-span 2) -->
            <div class="lg:col-span-2 flex items-center gap-1.5">
                <button type="submit"
                    class="w-full h-[34px] px-3 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>
                @if (request()->hasAny(['kode_cabang_search', 'bulan', 'tahun']))
                    <a href="{{ url()->current() }}"
                        class="h-[34px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-medium transition flex items-center justify-center cursor-pointer shrink-0"
                        title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- DATA TABLE SECTION (KOMPAK ~36px) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead>
                    <!-- Baris 1: Strip Header Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="11" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="truck" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Daftar Setting Komisi Driver &amp; Helper</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num">
                                    Total: {{ method_exists($settingkomisidriverhelper, 'total') ? number_format($settingkomisidriverhelper->total(), 0, ',', '.') : number_format($settingkomisidriverhelper->count(), 0, ',', '.') }} Data
                                </span>
                            </div>
                        </th>
                    </tr>

                    <!-- Baris 2: Header Kolom -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">BULAN</th>
                        <th class="py-2 px-3 text-center w-20 font-bold uppercase tracking-wider text-white/90 border-none">TAHUN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">CABANG</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none">KOMISI SALESMAN</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none">QTY PENJUALAN</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none">VALUE / UNIT</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none">QTY FLAT</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none">UMK</th>
                        <th class="py-2 px-3 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">PERSENTASE</th>
                        <th class="py-2 px-3 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($settingkomisidriverhelper as $d)
                        @php
                            $qty_penjualan = 0;
                            foreach ($produk as $p) {
                                $qty_penjualan += FLOOR((float)($d->{"qty_kendaraan_$p->kode_produk"} ?? 0));
                            }
                            $valueperunit = $qty_penjualan > 0 ? ROUND((float)$d->komisi_salesman / $qty_penjualan, 2) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <!-- Kode -->
                            <td class="py-1.5 px-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md font-mono-num font-bold text-[11px] bg-blue-50 text-[#284c9a] dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200/60 dark:border-blue-900/50">
                                    {{ $d->kode_komisi }}
                                </span>
                            </td>

                            <!-- Bulan -->
                            <td class="py-1.5 px-3 text-center font-semibold text-slate-800 dark:text-slate-100">
                                {{ $namaBulanArr[(int)$d->bulan] ?? ($nama_bulan[(int)$d->bulan] ?? $d->bulan) }}
                            </td>

                            <!-- Tahun -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-700 dark:text-slate-300">
                                {{ $d->tahun }}
                            </td>

                            <!-- Cabang -->
                            <td class="py-1.5 px-3">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                    <i data-lucide="building-2" class="w-3 h-3 text-slate-400"></i>
                                    <span>{{ textUpperCase($d->nama_cabang ?? $d->kode_cabang) }}</span>
                                </span>
                            </td>

                            <!-- Komisi Salesman -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-900 dark:text-slate-100">
                                {{ formatAngka($d->komisi_salesman) }}
                            </td>

                            <!-- Qty Penjualan -->
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                {{ formatAngka($qty_penjualan) }}
                            </td>

                            <!-- Value Per Unit -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50/40 dark:bg-emerald-950/20">
                                {{ number_format((float)($valueperunit ?? 0), 2, ',', '.') }}
                            </td>

                            <!-- Qty Flat -->
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                {{ formatAngka($d->qty_flat) }}
                            </td>

                            <!-- UMK -->
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                {{ formatAngka($d->umk) }}
                            </td>

                            <!-- Persentase -->
                            <td class="py-1.5 px-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md font-mono-num font-bold text-[11px] bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                    {{ formatAngka($d->persentase) }}%
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    
                                    <!-- 1. Edit -->
                                    <button type="button" class="btnEdit w-6 h-6 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/50 dark:hover:bg-amber-900 dark:text-amber-300 flex items-center justify-center transition cursor-pointer"
                                        kode_komisi="{{ Crypt::encrypt($d->kode_komisi) }}" title="Edit Setting Komisi">
                                        <i data-lucide="edit-3" class="w-3 h-3"></i>
                                    </button>

                                    <!-- 2. Cetak Laporan -->
                                    <a href="{{ route('settingkomisidriverhelper.cetak', Crypt::encrypt($d->kode_komisi)) }}" target="_blank"
                                        class="w-6 h-6 rounded-md bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-950/50 dark:hover:bg-blue-900 dark:text-blue-300 flex items-center justify-center transition cursor-pointer"
                                        title="Cetak Rekap Komisi">
                                        <i data-lucide="printer" class="w-3 h-3"></i>
                                    </a>

                                    <!-- 3. Export Excel -->
                                    <a href="{{ route('settingkomisidriverhelper.cetak', Crypt::encrypt($d->kode_komisi)) }}?export=true" target="_blank"
                                        class="w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:hover:bg-emerald-900 dark:text-emerald-300 flex items-center justify-center transition cursor-pointer"
                                        title="Export Excel">
                                        <i data-lucide="file-spreadsheet" class="w-3 h-3"></i>
                                    </a>

                                    <!-- 4. Hapus -->
                                    <form method="POST" action="{{ route('settingkomisidriverhelper.delete', Crypt::encrypt($d->kode_komisi)) }}" class="inline-flex m-0 p-0 deleteform">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900 dark:text-rose-300 flex items-center justify-center transition cursor-pointer"
                                            title="Hapus Setting Komisi">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-8 h-8 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-medium">Tidak ada data setting komisi driver &amp; helper ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION SECTION -->
        @if ($settingkomisidriverhelper->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $settingkomisidriverhelper->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- STANDAR V2 MODAL CONTAINER (STATIC BACKDROP, EXPLICIT CLOSE, ZERO LAYOUT SHIFT) -->
<div id="modalSettingKomisi" class="fixed inset-0 z-50 hidden overflow-y-auto modal-wrapper" aria-labelledby="modalTitle" role="dialog" aria-modal="true">
    <!-- Backdrop Gelap -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity modal-backdrop-v2"></div>

    <div class="flex min-h-screen items-center justify-center p-3 sm:p-4 text-center">
        <div id="modalDialog"
            class="relative w-full max-w-xl transform rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all duration-200 border border-slate-200/80 dark:border-slate-800 overflow-hidden flex flex-col max-h-[92vh]">
            
            <!-- Modal Header Bersih -->
            <div class="flex items-center justify-between px-4 py-3 sm:px-5 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div id="modalIconContainer" class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                        <i data-lucide="settings" id="modalHeaderIcon" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 id="modalTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-none">
                            Formulir Setting Komisi Driver Helper
                        </h3>
                        <p id="modalSubtitle" class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 leading-none">
                            Kelola parameter perhitungan komisi
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Content (Scrollable Container) -->
            <div id="modalBodyContent" class="p-4 sm:p-5 overflow-y-auto flex-1">
                <div class="py-12 flex flex-col items-center justify-center gap-2 text-slate-400">
                    <div class="w-7 h-7 border-2 border-[#284c9a] border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs font-medium">Memuat data...</span>
                </div>
            </div>

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

        // Initialize Select2 in Filter
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2Kodecabangsearch').select2({
                placeholder: 'Semua Cabang',
                allowClear: true,
                width: '100%'
            });
        }

        // Modal Variables
        const $modal = $('#modalSettingKomisi');
        const $modalDialog = $('#modalDialog');
        const $modalTitle = $('#modalTitle');
        const $modalSubtitle = $('#modalSubtitle');
        const $modalContent = $('#modalBodyContent');

        function openModal(title, subtitle, widthClass, iconName) {
            $modalTitle.text(title);
            $modalSubtitle.text(subtitle);
            $modalDialog.removeClass('max-w-md max-w-lg max-w-xl max-w-2xl max-w-3xl')
                        .addClass(widthClass || 'max-w-xl');
            
            if (iconName && typeof lucide !== 'undefined') {
                $('#modalHeaderIcon').attr('data-lucide', iconName);
                lucide.createIcons();
            }

            loadingModal();
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $modalContent.html('');
        }

        function loadingModal() {
            $modalContent.html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2 text-slate-400">
                    <div class="w-7 h-7 border-2 border-[#284c9a] border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs font-medium">Memuat form data...</span>
                </div>
            `);
        }

        function triggerLockAnimation() {
            $modalDialog.addClass('scale-[1.015] shadow-2xl');
            setTimeout(function() {
                $modalDialog.removeClass('scale-[1.015] shadow-2xl');
            }, 150);
        }

        // Static Backdrop Handling
        $modal.on('click', function(e) {
            if ($(e.target).is($modal) || $(e.target).hasClass('modal-wrapper') || $(e.target).hasClass('modal-backdrop-v2')) {
                triggerLockAnimation();
            }
        });

        $(document).on('keydown', function(e) {
            if (!$modal.hasClass('hidden') && e.key === 'Escape') {
                triggerLockAnimation();
            }
        });

        // Close explicit
        $modal.on('click', '.btn-close-modal', function(e) {
            e.preventDefault();
            closeModal();
        });

        // 1. Tambah Setting Komisi (Create)
        $('#btnCreate').on('click', function(e) {
            e.preventDefault();
            openModal('Tambah Setting Komisi', 'Input parameter perhitungan komisi driver helper baru', 'max-w-xl', 'plus-circle');
            $.ajax({
                url: "{{ route('settingkomisidriverhelper.create') }}",
                type: 'GET',
                success: function(response) {
                    $modalContent.html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $modalContent.html(`
                        <div class="py-10 text-center text-rose-500">
                            <i data-lucide="alert-triangle" class="w-8 h-8 mx-auto mb-2 text-rose-500"></i>
                            <p class="text-xs font-bold">Gagal memuat form tambah setting komisi.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // 2. Edit Setting Komisi
        $('.btnEdit').on('click', function(e) {
            e.preventDefault();
            const kodeKomisi = $(this).attr('kode_komisi');
            openModal('Edit Setting Komisi', 'Perbarui konfigurasi parameter perhitungan komisi', 'max-w-xl', 'edit-3');
            $.ajax({
                url: `/settingkomisidriverhelper/${kodeKomisi}/edit`,
                type: 'GET',
                success: function(response) {
                    $modalContent.html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $modalContent.html(`
                        <div class="py-10 text-center text-rose-500">
                            <i data-lucide="alert-triangle" class="w-8 h-8 mx-auto mb-2 text-rose-500"></i>
                            <p class="text-xs font-bold">Gagal memuat form edit setting komisi.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // 3. SweetAlert Konfirmasi Hapus Setting Komisi
        $('.delete-confirm').on('click', function(e) {
            e.preventDefault();
            const $form = $(this).closest('form');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus Setting Komisi?',
                    text: 'Konfigurasi parameter komisi cabang ini akan dihapus permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $form.submit();
                    }
                });
            } else {
                if (confirm('Yakin ingin menghapus setting komisi driver helper ini?')) {
                    $form.submit();
                }
            }
        });

    });
</script>
@endpush

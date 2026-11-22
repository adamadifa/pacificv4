@extends('layouts.app')
@section('titlepage', 'Barang Masuk Maintenance')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Maintenance</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Barang Masuk</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 max-w-7xl">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Barang Masuk Maintenance</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola data penerimaan suku cadang dan perlengkapan maintenance.</p>
        </div>

        @can('barangmasukmtc.create')
            <button type="button" id="btnCreate"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Barang Masuk</span>
            </button>
        @endcan
    </div>

    <!-- STATISTIC & METRIC STRIP (PACIFIC NAVY SOLID) -->
    <div class="relative overflow-hidden rounded-xl bg-[#284c9a] text-white shadow-xs">
        <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="grid grid-cols-2 sm:grid-cols-3 divide-x divide-white/10 text-xs">
            <!-- 1. Total Transaksi Masuk -->
            <div class="p-3 sm:p-3.5 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/70 block">Total Data Masuk</span>
                    <span class="text-lg sm:text-xl font-black text-white font-mono-num block leading-tight mt-0.5">
                        {{ method_exists($barangmasuk, 'total') ? number_format($barangmasuk->total(), 0, ',', '.') : number_format($barangmasuk->count(), 0, ',', '.') }}
                    </span>
                    <span class="text-[10px] text-white/50 block">Dokumen penerimaan</span>
                </div>
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white/80 shrink-0">
                    <i data-lucide="package-plus" class="w-5 h-5"></i>
                </div>
            </div>

            <!-- 2. Halaman Aktif -->
            <div class="p-3 sm:p-3.5 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/70 block">Halaman Aktif</span>
                    <span class="text-lg sm:text-xl font-black text-white font-mono-num block leading-tight mt-0.5">
                        {{ $barangmasuk->currentPage() }}
                    </span>
                    <span class="text-[10px] text-white/50 block">Navigasi dokumen</span>
                </div>
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white/80 shrink-0">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                </div>
            </div>

            <!-- 3. Status Maintenance -->
            <div class="p-3 sm:p-3.5 hidden sm:flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/70 block">Unit Operasional</span>
                    <span class="text-sm sm:text-base font-bold text-white block leading-tight mt-0.5">
                        Gudang Maintenance
                    </span>
                    <span class="text-[10px] text-emerald-300 block font-semibold flex items-center gap-1 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        Aktif Terintegrasi
                    </span>
                </div>
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white/80 shrink-0">
                    <i data-lucide="wrench" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- NAVIGATION TABS -->
    @include('v2.layouts.navigation_maintenance')

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('barangmasukmtc.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
            
            <!-- Tanggal Dari -->
            <div class="lg:col-span-4 relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="dari" id="dari" value="{{ request('dari') }}"
                    class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                    placeholder="Dari Tanggal" readonly>
            </div>

            <!-- Tanggal Sampai -->
            <div class="lg:col-span-4 relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="sampai" id="sampai" value="{{ request('sampai') }}"
                    class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                    placeholder="Sampai Tanggal" readonly>
            </div>

            <!-- No. Bukti -->
            <div class="lg:col-span-3 relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="no_bukti_search" id="no_bukti_search" value="{{ request('no_bukti_search') }}"
                    class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="No. Bukti / Dokumen">
            </div>

            <!-- Tombol Cari -->
            <div class="lg:col-span-1">
                <button type="submit"
                    class="w-full h-[34px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span class="lg:hidden">Cari</span>
                </button>
            </div>

        </form>
    </div>

    <!-- DATA TABLE (STANDARD V2 PACIFIC NAVY & COMPACT ROW ~36PX) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead>
                    <!-- Row 1: Header Strip Pacific Navy with Count -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="5" class="px-3.5 py-2 font-bold uppercase tracking-wider text-white border-none">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="package-plus" class="w-4 h-4 text-white/80"></i>
                                    <span class="text-xs">Data Barang Masuk Maintenance</span>
                                </div>
                                <span class="text-[11px] font-mono-num text-white/70">
                                    Total: {{ method_exists($barangmasuk, 'total') ? number_format($barangmasuk->total(), 0, ',', '.') : number_format($barangmasuk->count(), 0, ',', '.') }} Dokumen
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles (Darker Navy #1e3a78) -->
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 w-44">NO. BUKTI</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">SUPPLIER</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 w-36">TGL PEMBELIAN</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 w-36">TGL DITERIMA</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 w-20">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($barangmasuk as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3">
                                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                    {{ $d->no_bukti }}
                                </span>
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                                {{ $d->nama_supplier ?? '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-center text-slate-600 dark:text-slate-400 font-mono-num text-[11px]">
                                {{ !empty($d->tanggal_pembelian) ? DateToIndo($d->tanggal_pembelian) : '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-center text-slate-800 dark:text-slate-200 font-mono-num text-[11px] font-semibold">
                                {{ DateToIndo($d->tanggal) }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('barangmasukmtc.show')
                                        <button type="button" class="btnShow w-6 h-6 rounded-md bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-950/50 dark:hover:bg-blue-900/50 dark:text-blue-400 flex items-center justify-center transition cursor-pointer"
                                            data-no-bukti="{{ Crypt::encrypt($d->no_bukti) }}" title="Detail Dokumen">
                                            <i data-lucide="eye" class="w-3 h-3"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i data-lucide="inbox" class="w-8 h-8 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-medium">Belum ada data barang masuk maintenance</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION SECTION -->
        @if ($barangmasuk->hasPages())
            <div class="px-3.5 py-2.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $barangmasuk->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- MODAL DIALOG V2 (STATIC BACKDROP, BOUNCE ANIMATION, CLEAN HEADER) -->
<div id="modalBarangMasuk" class="fixed inset-0 z-[1055] hidden transition-opacity duration-200">
    <!-- Backdrop -->
    <div class="modal-backdrop-v2 fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200 z-10"></div>

    <!-- Modal Box Wrapper -->
    <div class="modal-wrapper fixed inset-0 z-20 overflow-y-auto p-3 sm:p-4 flex items-center justify-center">
        <div class="modal-dialog relative w-full !max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-200 flex flex-col max-h-[92vh]">
            
            <!-- Modal Header (Clean without solid bg) -->
            <div class="flex items-center justify-between px-4 py-2.5 border-b border-slate-200/80 dark:border-slate-800 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="package-plus" class="w-3.5 h-3.5"></i>
                    </div>
                    <div>
                        <h4 id="modalBarangMasukTitle" class="text-xs font-bold text-slate-800 dark:text-slate-100">Barang Masuk Maintenance</h4>
                        <p id="modalBarangMasukSubtitle" class="text-[10px] text-slate-400">Penerimaan barang & suku cadang maintenance</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body (Loaded via AJAX) -->
            <div id="modalBarangMasukContent" class="p-3 sm:p-3.5 overflow-y-auto">
                <div class="flex flex-col items-center justify-center py-10 gap-2 text-slate-400">
                    <div class="w-7 h-7 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs font-medium">Memuat form data...</span>
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

        // Initialize Filter Flatpickr
        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Modal Elements
        const $modal = $('#modalBarangMasuk');
        const $modalDialog = $modal.find('.modal-dialog');
        const $modalContent = $('#modalBarangMasukContent');
        const $modalTitle = $('#modalBarangMasukTitle');
        const $modalSubtitle = $('#modalBarangMasukSubtitle');

        function openModal(title, subtitle, sizeClass) {
            $modalTitle.text(title);
            $modalSubtitle.text(subtitle);
            $modalDialog.removeClass(function(index, className) {
                return (className.match(/(^|\s)\S*max-w-\S+/g) || []).join(' ');
            });
            $modalDialog.addClass(sizeClass || '!max-w-4xl');
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $modalContent.html(`
                <div class="flex flex-col items-center justify-center py-10 gap-2 text-slate-400">
                    <div class="w-7 h-7 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
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

        // Backdrop Click Lock
        $modal.on('click', function(e) {
            if ($(e.target).is($modal) || $(e.target).hasClass('modal-wrapper') || $(e.target).hasClass('modal-backdrop-v2')) {
                triggerLockAnimation();
            }
        });

        // Escape Key Lock
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

        // Click Create Barang Masuk
        $('#btnCreate').on('click', function(e) {
            e.preventDefault();
            openModal('Tambah Data Barang Masuk', 'Input penerimaan suku cadang / perlengkapan maintenance', '!max-w-3xl');
            $.ajax({
                url: '/barangmasukmaintenance/create',
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
                            <p class="text-xs font-bold">Gagal memuat form tambah barang masuk.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // Click Detail Barang Masuk
        $('.btnShow').on('click', function(e) {
            e.preventDefault();
            const noBukti = $(this).data('no-bukti');
            openModal('Detail Barang Masuk', 'Rincian data penerimaan suku cadang maintenance', '!max-w-2xl');
            $.ajax({
                url: `/barangmasukmaintenance/${noBukti}/show`,
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
                            <p class="text-xs font-bold">Gagal memuat detail barang masuk.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

    });
</script>
@endpush

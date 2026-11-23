@extends('layouts.app')
@section('titlepage', 'Permintaan Kiriman')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Permintaan Kiriman</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Permintaan Kiriman</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola data permintaan kiriman barang ke cabang dan penerbitan surat jalan.</p>
        </div>

        @can('permintaankiriman.create')
            <button type="button" id="btnCreate"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Buat Permintaan</span>
            </button>
        @endcan
    </div>

    <!-- PETUNJUK ALUR OPERASIONAL (INFO BANNER) -->
    <div class="rounded-xl border border-sky-200 dark:border-sky-800/60 bg-sky-50/70 dark:bg-sky-950/30 p-3 flex items-start gap-3">
        <div class="w-7 h-7 rounded-lg bg-sky-100 dark:bg-sky-900/50 flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0 mt-0.5">
            <i data-lucide="info" class="w-4 h-4"></i>
        </div>
        <div class="text-xs text-sky-900 dark:text-sky-200 space-y-1">
            <p class="font-bold">Petunjuk Alur Operasional Permintaan Kiriman</p>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[11px] text-sky-700 dark:text-sky-300">
                <span class="inline-flex items-center gap-1">
                    <i data-lucide="external-link" class="w-3.5 h-3.5 text-brand-600 dark:text-blue-400"></i>
                    <span>Gunakan icon <strong>Buat Surat Jalan</strong> untuk menerbitkan surat jalan gudang jadi.</span>
                </span>
                <span class="inline-flex items-center gap-1">
                    <i data-lucide="minus-circle" class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400"></i>
                    <span>Gunakan icon <strong>Batalkan Surat Jalan</strong> jika surat jalan belum diterima cabang dan perlu dikoreksi.</span>
                </span>
            </div>
        </div>
    </div>

    <!-- FILTER SECTION (No-Label, Icon Prefix, h-[34px], rounded-lg) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('permintaankiriman.index') }}" method="GET" id="formSearch" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
            
            <!-- Dari Tanggal (Col-span 2) -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="dari" id="dari" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                    class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>

            <!-- Sampai Tanggal (Col-span 2) -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="sampai" id="sampai" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                    class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>

            <!-- Cabang (Col-span 3) -->
            <div class="lg:col-span-3 relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_cabang_search" id="kode_cabang_search"
                    class="select2Kodecabangsearch select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                            {{ $c->kode_cabang }} - {{ textUpperCase($c->nama_cabang) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status (Col-span 3) -->
            <div class="lg:col-span-3 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                </div>
                <select name="status_search" id="status_search"
                    class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="0|pk" {{ Request('status_search') == '0|pk' ? 'selected' : '' }}>Belum Di Proses</option>
                    <option value="1|pk" {{ Request('status_search') == '1|pk' ? 'selected' : '' }}>Sudah Di Proses Gudang</option>
                    <option value="0|sj" {{ Request('status_search') == '0|sj' ? 'selected' : '' }}>Belum Diterima Cabang</option>
                    <option value="1|sj" {{ Request('status_search') == '1|sj' ? 'selected' : '' }}>Sudah Diterima Cabang</option>
                    <option value="2|sj" {{ Request('status_search') == '2|sj' ? 'selected' : '' }}>Transit Out</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3 h-3"></i>
                </div>
            </div>

            <!-- Tombol Cari (Col-span 2) -->
            <div class="lg:col-span-2 flex items-center">
                <button type="submit"
                    class="w-full h-[34px] inline-flex items-center justify-center gap-1.5 px-3 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>
            </div>

        </form>
    </div>

    <!-- DATA TABLE SECTION -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <!-- Strip Judul Tabel (Colspan Penuh = 8) -->
                    <tr class="bg-[#284c9a] border-b border-[#1e3a78] text-white">
                        <th colspan="8" class="px-3.5 py-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="package" class="w-4 h-4 text-white/80"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Data Permintaan Kiriman</span>
                                </div>
                                <span class="text-[11px] text-white/80 font-mono-num font-semibold">
                                    Total: {{ method_exists($pk, 'total') ? number_format($pk->total(), 0, ',', '.') : number_format($pk->count(), 0, ',', '.') }}
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Header Kolom Pacific Navy Pekat (#1e3a78) -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-2 px-3">No. Permintaan</th>
                        <th class="py-2 px-3">Tanggal</th>
                        <th class="py-2 px-3">Cabang</th>
                        <th class="py-2 px-3">Keterangan</th>
                        <th class="py-2 px-3">Status PK</th>
                        <th class="py-2 px-3">Salesman</th>
                        <th class="py-2 px-3">Status SJ</th>
                        <th class="py-2 px-3 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs text-slate-700 dark:text-slate-300">
                    @forelse ($pk as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            
                            <!-- No. Permintaan -->
                            <td class="py-1.5 px-3">
                                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400">
                                    {{ $d->no_permintaan }}
                                </span>
                            </td>

                            <!-- Tanggal -->
                            <td class="py-1.5 px-3 font-mono-num">
                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                            </td>

                            <!-- Cabang -->
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-200">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 font-bold text-[11px] border border-blue-200/60 dark:border-blue-900/50">
                                    {{ textUpperCase($d->kode_cabang) }}
                                </span>
                            </td>

                            <!-- Keterangan -->
                            <td class="py-1.5 px-3 font-medium max-w-xs truncate" title="{{ $d->keterangan }}">
                                {{ $d->keterangan ?: '-' }}
                            </td>

                            <!-- Status PK -->
                            <td class="py-1.5 px-3">
                                @if ($d->status == 1)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 font-mono-num" title="No. Surat Jalan: {{ $d->no_mutasi }}">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                        <span>{{ $d->no_mutasi }}</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        <span>Menunggu Gudang</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Salesman -->
                            <td class="py-1.5 px-3 font-medium">
                                @php
                                    $nama_sales = explode(' ', $d->nama_salesman);
                                @endphp
                                {{ $nama_sales[0] ?: '-' }}
                            </td>

                            <!-- Status SJ -->
                            <td class="py-1.5 px-3">
                                @if ($d->status == 1)
                                    @if ($d->status_surat_jalan == 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50">
                                            Belum Diterima
                                        </span>
                                    @elseif($d->status_surat_jalan == 1)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                                            Sudah Diterima
                                        </span>
                                    @elseif($d->status_surat_jalan == 2)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-600 dark:bg-sky-900/30 dark:text-sky-400 border border-sky-200 dark:border-sky-800/50">
                                            Transit Out
                                        </span>
                                    @endif
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>

                            <!-- Aksi Kompak (24x24 px) -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    
                                    {{-- Edit Permintaan --}}
                                    @can('permintaankiriman.edit')
                                        @if ($d->status == 0)
                                            <button type="button" no_permintaan="{{ Crypt::encrypt($d->no_permintaan) }}" title="Edit Permintaan"
                                                class="btnEdit w-6 h-6 rounded-md flex items-center justify-center text-amber-600 hover:text-amber-700 hover:bg-amber-50 dark:hover:bg-amber-950/30 transition cursor-pointer">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            </button>
                                        @endif
                                    @endcan

                                    {{-- Detail Permintaan --}}
                                    @can('permintaankiriman.show')
                                        <button type="button" no_permintaan="{{ Crypt::encrypt($d->no_permintaan) }}" title="Lihat Rincian"
                                            class="btnShow w-6 h-6 rounded-md flex items-center justify-center text-sky-600 hover:text-sky-700 hover:bg-sky-50 dark:hover:bg-sky-950/30 transition cursor-pointer">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                    @endcan

                                    {{-- Hapus Permintaan --}}
                                    @can('permintaankiriman.delete')
                                        @if ($d->status == 0)
                                            <form method="POST" action="{{ route('permintaankiriman.delete', Crypt::encrypt($d->no_permintaan)) }}" class="inline-flex m-0 p-0 deleteform">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Permintaan"
                                                    class="w-6 h-6 rounded-md flex items-center justify-center text-rose-600 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition cursor-pointer">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan

                                    {{-- Buat Surat Jalan Gudang Jadi --}}
                                    @can('suratjalan.create')
                                        @if ($d->status === '0')
                                            <button type="button" no_permintaan="{{ Crypt::encrypt($d->no_permintaan) }}" title="Buat Surat Jalan"
                                                class="btnCreateSuratjalan w-6 h-6 rounded-md flex items-center justify-center text-brand-600 hover:text-brand-700 hover:bg-brand-50 dark:hover:bg-brand-950/30 transition cursor-pointer">
                                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                            </button>
                                        @endif
                                    @endcan

                                    {{-- Batalkan Surat Jalan --}}
                                    @can('suratjalan.delete')
                                        @if ($d->status == '1' && $d->status_surat_jalan === '0')
                                            <form method="POST" action="{{ route('suratjalan.delete', Crypt::encrypt($d->no_mutasi)) }}" class="inline-flex m-0 p-0 cancel-sj-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Batalkan Surat Jalan"
                                                    class="w-6 h-6 rounded-md flex items-center justify-center text-amber-600 hover:text-amber-700 hover:bg-amber-50 dark:hover:bg-amber-950/30 transition cursor-pointer">
                                                    <i data-lucide="minus-circle" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-6 h-6 text-slate-300"></i>
                                    <span class="text-xs font-medium">Belum ada data permintaan kiriman</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION V2 STANDAR -->
        @if ($pk->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $pk->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- UNIFIED MODAL DIALOG V2 (STATIC BACKDROP & BOUNCE LOCK) -->
<div id="modalPermintaanKiriman" class="fixed inset-0 z-50 hidden transition-opacity duration-200">
    <!-- Backdrop Blur with dark overlay -->
    <div class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs modal-backdrop-v2"></div>
    
    <div class="modal-wrapper fixed inset-0 z-20 overflow-y-auto p-3 sm:p-4 flex items-center justify-center">
        <div id="modalPermintaanKirimanDialog" class="modal-dialog relative w-full !max-w-3xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-200 flex flex-col max-h-[92vh]">
            
            <!-- Modal Header (Clean without solid bg per GEMINI.md) -->
            <div class="flex items-center justify-between px-4 py-2.5 border-b border-slate-200/80 dark:border-slate-800 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div id="modalHeaderIconContainer" class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i id="modalHeaderIcon" data-lucide="package" class="w-3.5 h-3.5"></i>
                    </div>
                    <div>
                        <h4 id="modalPermintaanKirimanTitle" class="text-xs font-bold text-slate-800 dark:text-slate-100">Permintaan Kiriman</h4>
                        <p id="modalPermintaanKirimanSubtitle" class="text-[10px] text-slate-400">Pengelolaan data permintaan kiriman</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body (Loaded via AJAX) -->
            <div id="modalPermintaanKirimanContent" class="p-3 sm:p-3.5 overflow-y-auto">
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

        // Initialize Select2 on Filter
        $('.select2Kodecabangsearch').select2({
            placeholder: "Semua Cabang",
            allowClear: true,
            width: '100%'
        });

        // Modal Elements
        const $modal = $('#modalPermintaanKiriman');
        const $modalDialog = $('#modalPermintaanKirimanDialog');
        const $modalContent = $('#modalPermintaanKirimanContent');
        const $modalTitle = $('#modalPermintaanKirimanTitle');
        const $modalSubtitle = $('#modalPermintaanKirimanSubtitle');

        function openModal(title, subtitle, sizeClass, iconName) {
            $modalTitle.text(title);
            $modalSubtitle.text(subtitle);
            $modalDialog.removeClass(function(index, className) {
                return (className.match(/(^|\s)\S*max-w-\S+/g) || []).join(' ');
            });
            $modalDialog.addClass(sizeClass || '!max-w-3xl');
            
            if (iconName) {
                $('#modalHeaderIcon').attr('data-lucide', iconName);
            }
            
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

        // Backdrop Click Lock (Static Backdrop)
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

        // Click Buat Permintaan (Create)
        $('#btnCreate').on('click', function(e) {
            e.preventDefault();
            openModal('Buat Permintaan Kiriman', 'Input permintaan kiriman barang ke cabang', '!max-w-3xl', 'plus-circle');
            $.ajax({
                url: "{{ route('permintaankiriman.create') }}",
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
                            <p class="text-xs font-bold">Gagal memuat form tambah permintaan kiriman.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // Click Edit Permintaan
        $('.btnEdit').on('click', function(e) {
            e.preventDefault();
            const noPermintaan = $(this).attr('no_permintaan');
            openModal('Edit Permintaan Kiriman', 'Perbarui data permintaan kiriman cabang', '!max-w-3xl', 'edit-3');
            $.ajax({
                url: `/permintaankiriman/${noPermintaan}/edit`,
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
                            <p class="text-xs font-bold">Gagal memuat form edit permintaan kiriman.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // Click Detail Permintaan (Show)
        $('.btnShow').on('click', function(e) {
            e.preventDefault();
            const noPermintaan = $(this).attr('no_permintaan');
            openModal('Detail Permintaan Kiriman', 'Rincian data permintaan & status surat jalan', '!max-w-4xl', 'eye');
            $.ajax({
                url: `/permintaankiriman/${noPermintaan}/show`,
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
                            <p class="text-xs font-bold">Gagal memuat rincian permintaan kiriman.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // Click Buat Surat Jalan (Surat Jalan Create)
        $('.btnCreateSuratjalan').on('click', function(e) {
            e.preventDefault();
            const noPermintaan = $(this).attr('no_permintaan');
            openModal('Buat Surat Jalan Gudang Jadi', 'Terbitkan surat jalan untuk permintaan kiriman ini', '!max-w-4xl', 'truck');
            $.ajax({
                url: `/suratjalan/${noPermintaan}/create`,
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
                            <p class="text-xs font-bold">Gagal memuat form buat surat jalan.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // Delete confirmation Permintaan Kiriman
        $(document).on('submit', '.deleteform', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: 'Data permintaan kiriman ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Data!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Cancel / Hapus Surat Jalan Confirmation
        $(document).on('submit', '.cancel-sj-form', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Batalkan Surat Jalan?',
                text: 'Surat jalan ini akan dibatalkan dan status permintaan kiriman akan dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Batalkan Surat Jalan!',
                cancelButtonText: 'Kembali',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

    });
</script>
@endpush

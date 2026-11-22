@extends('layouts.app')
@section('titlepage', 'Kirim Pusat Gudang Cabang')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Gudang Cabang</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Kirim Pusat</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Kirim Pusat Gudang Cabang</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola data pengiriman barang dari gudang cabang ke gudang pusat.</p>
        </div>

        @can('kirimpusat.create')
            <button type="button" id="btnCreateKirimPusat"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Kirim Pusat</span>
            </button>
        @endcan
    </div>

    <!-- NAVIGATION TABS -->
    @include('layouts.navigation_mutasigudangcabang')

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('kirimpusat.index') }}" method="GET" id="formSearch" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
            
            <!-- Dari Tanggal (Flatpickr) -->
            <div class="lg:col-span-3 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="dari" id="dari" value="{{ request('dari') }}" placeholder="Dari Tanggal" readonly
                    class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>

            <!-- Sampai Tanggal (Flatpickr) -->
            <div class="lg:col-span-3 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="sampai" id="sampai" value="{{ request('sampai') }}" placeholder="Sampai Tanggal" readonly
                    class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>

            @php
                $roles_show_cabang = config('global.roles_show_cabang');
                $canFilterCabang = auth()->user()->hasAnyRole($roles_show_cabang);
            @endphp

            @if ($canFilterCabang)
                <!-- Cabang (Select2 V2) -->
                <div class="lg:col-span-4 relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang_search" id="kode_cabang_search"
                        class="select2Kodecabangsearch select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                                {{ strtoupper($c->nama_cabang) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Tombol Cari -->
            <div class="{{ $canFilterCabang ? 'lg:col-span-2' : 'lg:col-span-6' }} flex items-center justify-end">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>
            </div>

        </form>
    </div>

    <!-- DATA TABLE CONTAINER -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead>
                    <!-- Row 1: Header Strip (colspan=5) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="5" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="send" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Kirim Pusat</span>
                                </div>
                                <span class="text-[11px] text-white/70 font-mono-num">
                                    Total: {{ method_exists($kirimpusat, 'total') ? $kirimpusat->total() : count($kirimpusat) }} data
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-44">NO. MUTASI</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-32">TANGGAL</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">CABANG</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">KETERANGAN</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-24">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($kirimpusat as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->no_mutasi }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-500 dark:text-slate-400 font-mono-num">
                                {{ DateToIndo($d->tanggal) }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                                {{ textUpperCase($d->nama_cabang) }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                                {{ $d->keterangan ?? '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('kirimpusat.edit')
                                        <button type="button"
                                            class="btnEditKirimPusat w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                                            data-no-mutasi="{{ Crypt::encrypt($d->no_mutasi) }}"
                                            title="Edit Kirim Pusat">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                    @endcan
                                    @can('kirimpusat.show')
                                        <button type="button"
                                            class="btnShowKirimPusat w-6 h-6 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 flex items-center justify-center transition cursor-pointer"
                                            data-no-mutasi="{{ Crypt::encrypt($d->no_mutasi) }}"
                                            title="Lihat Detail Kirim Pusat">
                                            <i data-lucide="eye" class="w-3 h-3"></i>
                                        </button>
                                    @endcan
                                    @can('kirimpusat.delete')
                                        <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                            action="{{ route('kirimpusat.delete', Crypt::encrypt($d->no_mutasi)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                                title="Hapus Data Kirim Pusat">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                        <i data-lucide="inbox" class="w-6 h-6"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Belum ada data kirim pusat</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION (V2 STANDARD COMPONENT) -->
        @if ($kirimpusat->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $kirimpusat->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- MODAL DIALOG KIRIM PUSAT (CREATE, EDIT, DETAIL) -->
<div id="modalKirimPusat" class="fixed inset-0 z-[1055] hidden transition-opacity duration-200">
    <!-- Backdrop -->
    <div class="modal-backdrop-v2 fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200 z-10"></div>

    <!-- Modal Box Wrapper -->
    <div class="modal-wrapper fixed inset-0 z-20 overflow-y-auto p-4 sm:p-6 flex items-center justify-center">
        <div class="modal-dialog modal-xl relative w-full !max-w-5xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-200 flex flex-col max-h-[92vh]">
            
            <!-- Modal Header (Bersih tanpa solid color) -->
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-200/80 dark:border-slate-800 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="send" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h4 id="modalKirimPusatTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Kirim Pusat</h4>
                        <p id="modalKirimPusatSubtitle" class="text-[11px] text-slate-400">Kelola rincian pengiriman barang ke gudang pusat</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body (Loaded via AJAX) -->
            <div id="modalKirimPusatContent" class="p-5 overflow-y-auto">
                <div class="flex flex-col items-center justify-center py-12 gap-2 text-slate-400">
                    <div class="w-8 h-8 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
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
        // Initialize Flatpickr for filter
        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Initialize Select2 if present
        const $select2Cabang = $('.select2Kodecabangsearch');
        if ($select2Cabang.length) {
            $select2Cabang.select2({
                placeholder: 'Semua Cabang',
                allowClear: true,
                width: '100%'
            });
        }

        // Modal Elements
        const $modal = $('#modalKirimPusat');
        const $modalContent = $('#modalKirimPusatContent');
        const $modalTitle = $('#modalKirimPusatTitle');
        const $modalSubtitle = $('#modalKirimPusatSubtitle');

        function openModal(title, subtitle) {
            $modalTitle.text(title);
            $modalSubtitle.text(subtitle);
            $modalContent.html(`
                <div class="flex flex-col items-center justify-center py-12 gap-2 text-slate-400">
                    <div class="w-8 h-8 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs font-medium">Memuat data...</span>
                </div>
            `);
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $modalContent.empty();
        }

        // Close on button click
        $(document).on('click', '.btn-close-modal', function(e) {
            e.preventDefault();
            closeModal();
        });

        // Static backdrop bounce effect
        $(document).on('click', '#modalKirimPusat .modal-backdrop-v2, #modalKirimPusat .modal-wrapper', function(e) {
            if ($(e.target).hasClass('modal-backdrop-v2') || $(e.target).hasClass('modal-wrapper')) {
                const $dialog = $modal.find('.modal-dialog');
                $dialog.addClass('scale-[1.015] shadow-2xl');
                setTimeout(() => {
                    $dialog.removeClass('scale-[1.015] shadow-2xl');
                }, 150);
            }
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && !$modal.hasClass('hidden')) {
                const $dialog = $modal.find('.modal-dialog');
                $dialog.addClass('scale-[1.015] shadow-2xl');
                setTimeout(() => {
                    $dialog.removeClass('scale-[1.015] shadow-2xl');
                }, 150);
            }
        });

        // Open Create
        $('#btnCreateKirimPusat').on('click', function(e) {
            e.preventDefault();
            openModal('Tambah Data Kirim Pusat', 'Input pengiriman barang dari cabang ke pusat');
            $.ajax({
                url: "{{ route('kirimpusat.create') }}",
                type: 'GET',
                success: function(res) {
                    $modalContent.html(res);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                },
                error: function() {
                    $modalContent.html(`
                        <div class="text-center py-8 text-rose-500">
                            <i data-lucide="alert-circle" class="w-8 h-8 mx-auto mb-2"></i>
                            <p class="text-xs font-semibold">Gagal memuat formulir kirim pusat.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        });

        // Open Edit
        $(document).on('click', '.btnEditKirimPusat', function(e) {
            e.preventDefault();
            const noMutasi = $(this).data('no-mutasi');
            openModal('Edit Kirim Pusat', 'Perbarui rincian pengiriman barang ke pusat');
            $.ajax({
                url: `/kirimpusat/${noMutasi}/edit`,
                type: 'GET',
                success: function(res) {
                    $modalContent.html(res);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                },
                error: function() {
                    $modalContent.html(`
                        <div class="text-center py-8 text-rose-500">
                            <i data-lucide="alert-circle" class="w-8 h-8 mx-auto mb-2"></i>
                            <p class="text-xs font-semibold">Gagal memuat data kirim pusat.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        });

        // Open Show Detail
        $(document).on('click', '.btnShowKirimPusat', function(e) {
            e.preventDefault();
            const noMutasi = $(this).data('no-mutasi');
            openModal('Detail Kirim Pusat', 'Rincian produk yang dikirimkan ke gudang pusat');
            $.ajax({
                url: `/kirimpusat/${noMutasi}/show`,
                type: 'GET',
                success: function(res) {
                    $modalContent.html(res);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                },
                error: function() {
                    $modalContent.html(`
                        <div class="text-center py-8 text-rose-500">
                            <i data-lucide="alert-circle" class="w-8 h-8 mx-auto mb-2"></i>
                            <p class="text-xs font-semibold">Gagal memuat rincian kirim pusat.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        });
    });
</script>
@endpush

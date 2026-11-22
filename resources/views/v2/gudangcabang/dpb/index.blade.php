@extends('layouts.app')
@section('titlepage', 'Data Pengambilan Barang (DPB)')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Gudang Cabang</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">DPB</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Pengambilan Barang (DPB)</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola dan monitor surat penyerahan serta pengambilan barang salesmen cabang.</p>
        </div>

        @can('dpb.create')
            <button type="button" id="btnCreateDpb"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Buat DPB Baru</span>
            </button>
        @endcan
    </div>

    <!-- NAVIGATION TABS -->
    @include('layouts.navigation_mutasigudangcabang')

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('dpb.index') }}" method="GET" id="formSearch" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
            
            <!-- Dari Tanggal (Flatpickr) -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="dari" id="dari" value="{{ request('dari') }}" placeholder="Dari Tanggal" readonly
                    class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>

            <!-- Sampai Tanggal (Flatpickr) -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="sampai" id="sampai" value="{{ request('sampai') }}" placeholder="Sampai Tanggal" readonly
                    class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>

            <!-- No DPB -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="no_dpb_search" id="no_dpb_search" value="{{ request('no_dpb_search') }}" placeholder="No. DPB"
                    class="w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>

            @php
                $roles_show_cabang = config('global.roles_show_cabang');
                $canFilterCabang = auth()->user()->hasAnyRole($roles_show_cabang);
            @endphp

            @if ($canFilterCabang)
                <!-- Cabang (Select2 V2) -->
                <div class="lg:col-span-2 relative">
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

            <!-- Salesman -->
            <div class="{{ $canFilterCabang ? 'lg:col-span-2' : 'lg:col-span-4' }} relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_salesman_search" id="kode_salesman_search"
                    class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Semua Salesman</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>

            <!-- Tombol Cari -->
            <div class="lg:col-span-2 flex items-center">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-1.5 h-[34px] px-3 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
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
                    <!-- Row 1: Header Strip (colspan=8) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="8" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="list-ordered" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Pengambilan Barang (DPB)</span>
                                </div>
                                <span class="text-[11px] text-white/70 font-mono-num">
                                    Total: {{ method_exists($dpb, 'total') ? $dpb->total() : count($dpb) }} data
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-36">NO. DPB</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-28">TANGGAL</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">SALESMAN</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">CABANG</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">TUJUAN</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-28">NO. POLISI</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-28 text-center">KEMBALI</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-24">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($dpb as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->no_dpb }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-500 dark:text-slate-400 font-mono-num">
                                {{ DateToIndo($d->tanggal_ambil) }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                                {{ $d->nama_salesman }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                                {{ textUpperCase($d->nama_cabang) }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                                {{ $d->tujuan }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-700 dark:text-slate-300">
                                {{ $d->no_polisi }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                @if (!empty($d->tanggal_kembali))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60">
                                        <i data-lucide="check" class="w-3 h-3 text-emerald-600"></i>
                                        {{ DateToIndo($d->tanggal_kembali) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Belum Kembali
                                    </span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('dpb.edit')
                                        <button type="button"
                                            class="btnEditDpb w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                                            data-no-dpb="{{ Crypt::encrypt($d->no_dpb) }}"
                                            title="Edit DPB">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                    @endcan
                                    @can('dpb.show')
                                        <button type="button"
                                            class="btnShowDpb w-6 h-6 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 flex items-center justify-center transition cursor-pointer"
                                            data-no-dpb="{{ Crypt::encrypt($d->no_dpb) }}"
                                            title="Lihat Detail DPB & Mutasi">
                                            <i data-lucide="eye" class="w-3 h-3"></i>
                                        </button>
                                    @endcan
                                    @can('dpb.delete')
                                        <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                            action="{{ route('dpb.delete', Crypt::encrypt($d->no_dpb)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                                title="Hapus DPB">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                        <i data-lucide="inbox" class="w-6 h-6"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Belum ada data DPB</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION (V2 STANDARD COMPONENT) -->
        @if ($dpb->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $dpb->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- MODAL DPB (CREATE, EDIT, DETAIL) (V2 STANDARD: CLEAN HEADER & STATIC BACKDROP) -->
<div id="modalDpb" class="fixed inset-0 z-[1055] hidden transition-opacity duration-200">
    <!-- Backdrop -->
    <div class="modal-backdrop-v2 fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200 z-10"></div>

    <!-- Modal Box Wrapper -->
    <div class="modal-wrapper fixed inset-0 z-20 overflow-y-auto p-3 sm:p-4 lg:p-6 flex items-center justify-center">
        <div class="modal-dialog relative w-full !max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-200 flex flex-col max-h-[94vh]">
            
            <!-- Modal Header (Clean without solid bg) -->
            <div class="flex items-center justify-between px-4 py-2.5 border-b border-slate-200/80 dark:border-slate-800 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="list-ordered" class="w-3.5 h-3.5"></i>
                    </div>
                    <div>
                        <h4 id="modalDpbTitle" class="text-xs font-bold text-slate-800 dark:text-slate-100">Daftar Pengambilan Barang (DPB)</h4>
                        <p id="modalDpbSubtitle" class="text-[10px] text-slate-400">Kelola rincian data DPB</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body (Loaded via AJAX) -->
            <div id="modalDpbContent" class="p-3 sm:p-3.5 overflow-y-auto">
                <div class="flex flex-col items-center justify-center py-12 gap-2 text-slate-400">
                    <div class="w-8 h-8 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs font-medium">Memuat form data...</span>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- SUB-MODAL MUTASI DPB (V2 STANDARD) -->
<div id="modalMutasiDpb" class="fixed inset-0 z-[1065] hidden transition-opacity duration-200">
    <div class="modal-backdrop-mutasi fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200 z-10"></div>
    <div class="modal-wrapper-mutasi fixed inset-0 z-20 overflow-y-auto p-4 sm:p-6 flex items-center justify-center">
        <div class="modal-dialog-mutasi relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-200 flex flex-col max-h-[90vh]">
            
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-200/80 dark:border-slate-800 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <i data-lucide="repeat" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h4 id="modalMutasiDpbTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Mutasi DPB</h4>
                        <p id="modalMutasiDpbSubtitle" class="text-[11px] text-slate-400">Rincian mutasi DPB</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal-mutasi w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div id="modalMutasiDpbContent" class="p-5 overflow-y-auto">
                <div class="flex flex-col items-center justify-center py-12 gap-2 text-slate-400">
                    <div class="w-8 h-8 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs font-medium">Memuat form mutasi...</span>
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

        // Load salesman by branch for filter
        const $formSearch = $("#formSearch");
        function getsalesmanbyCabangFilter() {
            const kodeCabang = $formSearch.find("#kode_cabang_search").val();
            const kodeSalesman = "{{ request('kode_salesman_search') }}";
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kodeCabang,
                    kode_salesman: kodeSalesman
                },
                cache: false,
                success: function(respond) {
                    $formSearch.find("#kode_salesman_search").html(respond);
                }
            });
        }

        getsalesmanbyCabangFilter();
        $formSearch.find("#kode_cabang_search").on('change', function() {
            getsalesmanbyCabangFilter();
        });

        // Modal Elements
        const $modalDpb = $('#modalDpb');
        const $modalDialogDpb = $modalDpb.find('.modal-dialog');
        const $modalContentDpb = $('#modalDpbContent');
        const $modalTitleDpb = $('#modalDpbTitle');
        const $modalSubtitleDpb = $('#modalDpbSubtitle');

        function openModalDpb(title, subtitle, sizeClass) {
            $modalTitleDpb.text(title);
            $modalSubtitleDpb.text(subtitle);
            $modalDialogDpb.removeClass(function(index, className) {
                return (className.match(/(^|\s)\S*max-w-\S+/g) || []).join(' ');
            });
            $modalDialogDpb.addClass(sizeClass || '!max-w-4xl');
            $modalDpb.removeClass('hidden');
            $('body').addClass('overflow-hidden');
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function closeModalDpb() {
            $modalDpb.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $modalContentDpb.html(`
                <div class="flex flex-col items-center justify-center py-12 gap-2 text-slate-400">
                    <div class="w-8 h-8 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs font-medium">Memuat form data...</span>
                </div>
            `);
        }

        function triggerLockAnimationDpb() {
            $modalDialogDpb.addClass('scale-[1.015] shadow-2xl');
            setTimeout(function() {
                $modalDialogDpb.removeClass('scale-[1.015] shadow-2xl');
            }, 150);
        }

        // Sub-modal Mutasi DPB
        const $modalMutasi = $('#modalMutasiDpb');
        const $modalDialogMutasi = $modalMutasi.find('.modal-dialog-mutasi');
        const $modalContentMutasi = $('#modalMutasiDpbContent');
        const $modalTitleMutasi = $('#modalMutasiDpbTitle');
        const $modalSubtitleMutasi = $('#modalMutasiDpbSubtitle');

        window.openModalMutasi = function(title, subtitle, url) {
            $modalTitleMutasi.text(title);
            $modalSubtitleMutasi.text(subtitle);
            $modalMutasi.removeClass('hidden');
            $modalContentMutasi.html(`
                <div class="flex flex-col items-center justify-center py-12 gap-2 text-slate-400">
                    <div class="w-8 h-8 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs font-medium">Memuat form mutasi...</span>
                </div>
            `);
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $modalContentMutasi.html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $modalContentMutasi.html(`
                        <div class="py-8 text-center text-rose-500">
                            Gagal memuat data mutasi DPB.
                        </div>
                    `);
                }
            });
        };

        window.closeModalMutasi = function() {
            $modalMutasi.addClass('hidden');
            $modalContentMutasi.html('');
        };

        $('.btn-close-modal-mutasi').on('click', function(e) {
            e.preventDefault();
            window.closeModalMutasi();
        });

        $modalMutasi.on('click', function(e) {
            if ($(e.target).is($modalMutasi) || $(e.target).hasClass('modal-wrapper-mutasi') || $(e.target).hasClass('modal-backdrop-mutasi')) {
                $modalDialogMutasi.addClass('scale-[1.015] shadow-2xl');
                setTimeout(function() {
                    $modalDialogMutasi.removeClass('scale-[1.015] shadow-2xl');
                }, 150);
            }
        });

        // Click Create DPB
        $('#btnCreateDpb').on('click', function(e) {
            e.preventDefault();
            openModalDpb('Buat DPB Baru', 'Input data pengambilan barang salesmen', '!max-w-4xl');
            $.ajax({
                url: '/dpb/create',
                type: 'GET',
                success: function(response) {
                    $modalContentDpb.html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $modalContentDpb.html(`
                        <div class="py-12 text-center text-rose-500">
                            <i data-lucide="alert-triangle" class="w-8 h-8 mx-auto mb-2 text-rose-500"></i>
                            <p class="text-xs font-bold">Gagal memuat form buat DPB.</p>
                        </div>
                    `);
                }
            });
        });

        // Click Detail DPB
        $('.btnShowDpb').on('click', function(e) {
            e.preventDefault();
            const noDpb = $(this).data('no-dpb');
            openModalDpb('Detail DPB & Mutasi', 'Rincian pengambilan dan pergerakan mutasi barang', '!max-w-6xl');
            $.ajax({
                url: `/dpb/${noDpb}/show`,
                type: 'GET',
                success: function(response) {
                    $modalContentDpb.html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $modalContentDpb.html(`
                        <div class="py-12 text-center text-rose-500">
                            <i data-lucide="alert-triangle" class="w-8 h-8 mx-auto mb-2 text-rose-500"></i>
                            <p class="text-xs font-bold">Gagal memuat detail DPB.</p>
                        </div>
                    `);
                }
            });
        });

        // Click Edit DPB
        $('.btnEditDpb').on('click', function(e) {
            e.preventDefault();
            const noDpb = $(this).data('no-dpb');
            openModalDpb('Edit Data DPB', 'Perbarui data penugasan DPB', '!max-w-[96vw] 2xl:!max-w-[1440px] xl:!max-w-[1360px]');
            $.ajax({
                url: `/dpb/${noDpb}/edit`,
                type: 'GET',
                success: function(response) {
                    $modalContentDpb.html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $modalContentDpb.html(`
                        <div class="py-12 text-center text-rose-500">
                            <i data-lucide="alert-triangle" class="w-8 h-8 mx-auto mb-2 text-rose-500"></i>
                            <p class="text-xs font-bold">Gagal memuat form edit DPB.</p>
                        </div>
                    `);
                }
            });
        });

        // Close explicit
        $('.btn-close-modal').on('click', function(e) {
            e.preventDefault();
            closeModalDpb();
        });

        // Static Backdrop
        $modalDpb.on('click', function(e) {
            if ($(e.target).is($modalDpb) || $(e.target).hasClass('modal-wrapper') || $(e.target).hasClass('modal-backdrop-v2')) {
                triggerLockAnimationDpb();
            }
        });

        // Prevent ESC close
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!$modalMutasi.hasClass('hidden')) {
                    e.preventDefault();
                    $modalDialogMutasi.addClass('scale-[1.015] shadow-2xl');
                    setTimeout(function() {
                        $modalDialogMutasi.removeClass('scale-[1.015] shadow-2xl');
                    }, 150);
                } else if (!$modalDpb.hasClass('hidden')) {
                    e.preventDefault();
                    triggerLockAnimationDpb();
                }
            }
        });

        // Delete DPB confirmation
        $('.deleteform').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: 'Data DPB ini akan dihapus secara permanen!',
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
    });
</script>
@endpush

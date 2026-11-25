@extends('v2.layouts.app')
@section('titlepage', 'Jurnal Umum')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Accounting</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Jurnal Umum</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full max-w-7xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Jurnal Umum</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Manajemen posting jurnal umum (Accounting).</p>
        </div>
    </div>

    <!-- FILTER BAR (COMPACT NO-LABEL V2) -->
    <form action="{{ route('jurnalumum.index') }}" method="GET" id="formSearchJurnalUmum"
        class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
            <!-- Dari -->
            <div class="sm:col-span-3 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_search" value="{{ request('dari') }}"
                    placeholder="Dari Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <!-- Sampai -->
            <div class="sm:col-span-3 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_search" value="{{ request('sampai') }}"
                    placeholder="Sampai Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <!-- Cabang -->
            <div class="sm:col-span-5 relative select2-container-v2">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building" class="w-4 h-4"></i>
                </div>
                <select name="kode_cabang_search" id="kode_cabang_search" class="select2Kodecabangsearch w-full">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}" @selected(request('kode_cabang_search') == $d->kode_cabang)>
                            {{ textUpperCase($d->nama_cabang) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Cari -->
            <div class="sm:col-span-1">
                <button type="submit"
                    class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1 cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span class="sm:hidden lg:inline">Cari</span>
                </button>
            </div>
        </div>
    </form>

    <!-- DATA TABLE CARD (V2 COMPACT ~36PX) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <!-- BARIS 1: STRIP JUDUL TABEL (bg-[#284c9a]) -->
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="book" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Data Jurnal Umum</h5>
            </div>
            @can('jurnalumum.create')
                <button type="button" id="btnCreateJurnalUmum"
                    class="h-[28px] px-3 rounded-lg bg-white text-[#284c9a] hover:bg-blue-50 text-xs font-bold shadow-xs transition flex items-center gap-1 cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Input Jurnal Umum</span>
                </button>
            @endcan
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-2 px-3">KODE JU</th>
                        <th class="py-2 px-3">TANGGAL</th>
                        <th class="py-2 px-3 min-w-[220px]">KETERANGAN</th>
                        <th class="py-2 px-3 min-w-[200px]">AKUN</th>
                        <th class="py-2 px-3">PERUNTUKAN</th>
                        <th class="py-2 px-3 text-right">DEBET</th>
                        <th class="py-2 px-3 text-right">KREDIT</th>
                        <th class="py-2 px-3">DEPT</th>
                        <th class="py-2 px-3 text-center w-20">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($jurnalumum as $d)
                        @php
                            $debet = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                            $kredit = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                            $rowBg = !empty($d->kode_cr) ? 'bg-blue-50/70 dark:bg-blue-950/25' : '';
                        @endphp
                        <tr class="{{ $rowBg }} hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold {{ !empty($d->kode_cr) ? 'text-[#284c9a] dark:text-blue-400' : 'text-slate-800 dark:text-slate-100' }}">
                                {{ $d->kode_ju }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">
                                {{ formatIndo($d->tanggal) }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-200 max-w-[280px] truncate" title="{{ $d->keterangan }}">
                                {{ $d->keterangan }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-200">
                                <span class="px-1.5 py-0.5 rounded bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-300 font-mono-num font-bold text-[11px]">{{ $d->kode_akun }}</span>
                                <span class="ml-1">{{ $d->nama_akun }}</span>
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-600 dark:text-slate-300">
                                {{ $d->kode_peruntukan }} {{ !empty($d->kode_cabang) ? '(' . $d->kode_cabang . ')' : '' }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-800 dark:text-slate-100">
                                {{ formatAngkaDesimal($debet) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-800 dark:text-slate-100">
                                {{ formatAngkaDesimal($kredit) }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">
                                {{ $d->kode_dept }}
                            </td>
                            <td class="py-1.5 px-3">
                                <div class="flex items-center justify-center gap-1">
                                    @can('jurnalumum.edit')
                                        <button type="button"
                                            class="btnEditJurnalUmum w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:hover:bg-emerald-900/60 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                                            kode_ju="{{ Crypt::encrypt($d->kode_ju) }}"
                                            title="Edit">
                                            <i data-lucide="pencil" class="w-3 h-3"></i>
                                        </button>
                                    @endcan
                                    @can('jurnalumum.delete')
                                        <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                            action="{{ route('jurnalumum.delete', Crypt::encrypt($d->kode_ju)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                                title="Hapus">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-10 px-4 text-center">
                                <div class="flex flex-col items-center justify-center gap-1.5 text-slate-400">
                                    <i data-lucide="database-zap" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Tidak ada data jurnal umum ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= MODAL JURNAL UMUM (STATIC BACKDROP) ================= -->
<div id="modalJurnalUmum" class="fixed inset-0 z-50 hidden">
    <div id="modalJurnalUmumBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalJurnalUmumDialog"
            class="relative w-full max-w-5xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            <!-- Modal Header Bersih -->
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 rounded-t-xl">
                <div class="flex items-center gap-2">
                    <i data-lucide="book" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalJurnalUmumTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Input Jurnal Umum</h5>
                </div>
                <button type="button" id="btnCloseModalJurnalUmum"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="loadmodalJurnalUmum" class="p-4 sm:p-5 max-h-[85vh] overflow-y-auto"></div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        const $select2CabangSearch = $('.select2Kodecabangsearch');
        if ($select2CabangSearch.length) {
            $select2CabangSearch.select2({
                placeholder: 'Semua Cabang',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        const $modal = $('#modalJurnalUmum');
        const $dialog = $('#modalJurnalUmumDialog');
        const $backdrop = $('#modalJurnalUmumBackdrop');
        const $title = $('#modalJurnalUmumTitle');
        const $content = $('#loadmodalJurnalUmum');

        function openModal(sizeClass) {
            $dialog.removeClass('max-w-lg max-w-5xl').addClass(sizeClass);
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $content.html('');
        }

        window.closeModalJurnalUmum = closeModal;

        $('#btnCloseModalJurnalUmum').on('click', closeModal);

        $backdrop.on('click', function(e) {
            e.stopPropagation();
            $dialog.addClass('scale-[1.015]');
            setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
        });

        function showLoading() {
            $content.html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500">Memuat formulir...</span>
                </div>
            `);
        }

        $('#btnCreateJurnalUmum').on('click', function(e) {
            e.preventDefault();
            $title.text('Input Jurnal Umum');
            openModal('max-w-5xl');
            showLoading();
            $content.load('/jurnalumum/create', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '.btnEditJurnalUmum', function(e) {
            e.preventDefault();
            const kode_ju = $(this).attr('kode_ju');
            $title.text('Edit Jurnal Umum');
            openModal('max-w-lg');
            showLoading();
            $content.load('/jurnalumum/' + kode_ju + '/edit', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush

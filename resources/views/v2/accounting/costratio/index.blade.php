@extends('v2.layouts.app')
@section('titlepage', 'Cost Ratio')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Accounting</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Cost Ratio</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full max-w-6xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Cost Ratio</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Manajemen biaya dan rasio biaya operasional.</p>
        </div>
    </div>

    <!-- FILTER BAR (COMPACT NO-LABEL V2) -->
    <form action="{{ url()->current() }}" method="GET" id="formSearchCostRatio"
        class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-start">
            <!-- Dari -->
            <div class="sm:col-span-3">
                <div class="relative">
                    <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="dari" id="dari_search" value="{{ request('dari') }}"
                        placeholder="Dari Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
                </div>
            </div>

            <!-- Sampai -->
            <div class="sm:col-span-3">
                <div class="relative">
                    <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="sampai" id="sampai_search" value="{{ request('sampai') }}"
                        placeholder="Sampai Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
                </div>
            </div>

            <!-- Cabang (Role-based) -->
            @hasanyrole($roles_show_cabang)
                <div class="sm:col-span-2">
                    <div class="relative select2-container-v2">
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
                </div>
            @endhasanyrole

            <!-- Sumber -->
            <div class="@hasanyrole($roles_show_cabang) sm:col-span-3 @else sm:col-span-5 @endhasanyrole">
                <div class="relative">
                    <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="layers" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_sumber_search" id="kode_sumber_search"
                        class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        <option value="">Semua Sumber Cost Ratio</option>
                        @foreach ($sumber as $d)
                            <option value="{{ $d->kode_sumber }}" @selected(request('kode_sumber_search') == $d->kode_sumber)>
                                {{ textUpperCase($d->sumber) }}
                            </option>
                        @endforeach
                    </select>
                </div>
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
        <div class="px-3.5 py-2 bg-[#284c9a] flex flex-wrap items-center justify-between gap-2 text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="bar-chart-2" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Data Cost Ratio</h5>
            </div>

            <div class="flex flex-wrap items-center gap-1.5">
                @can('costratio.index')
                    <form action="/costratio/cetak" method="GET" id="formCetakCostRatio" target="_blank" class="inline-flex items-center gap-1.5 m-0 p-0">
                        <input type="hidden" name="dari" id="dari_cetak" value="{{ request('dari') }}" />
                        <input type="hidden" name="sampai" id="sampai_cetak" value="{{ request('sampai') }}" />
                        <input type="hidden" name="kode_cabang_search" id="kode_cabang_cetak" value="{{ request('kode_cabang_search') }}" />
                        <button type="submit"
                            class="h-[28px] px-2.5 rounded-lg bg-white/15 hover:bg-white/25 text-white text-xs font-bold transition flex items-center gap-1 cursor-pointer">
                            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                            <span>Cetak</span>
                        </button>
                        <button type="submit" name="exportButton" value="1"
                            class="h-[28px] px-2.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold transition flex items-center gap-1 cursor-pointer">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                            <span>Export</span>
                        </button>
                    </form>
                @endcan
                @can('costratio.create')
                    <button type="button" id="btnCreateCostRatio"
                        class="h-[28px] px-3 rounded-lg bg-white text-[#284c9a] hover:bg-blue-50 text-xs font-bold shadow-xs transition flex items-center gap-1 cursor-pointer">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Input Cost Ratio</span>
                    </button>
                @endcan
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-2 px-3">KODE CR</th>
                        <th class="py-2 px-3">TANGGAL</th>
                        <th class="py-2 px-3">AKUN</th>
                        <th class="py-2 px-3">KETERANGAN</th>
                        <th class="py-2 px-3 text-right">JUMLAH</th>
                        <th class="py-2 px-3">SUMBER</th>
                        <th class="py-2 px-3">CABANG</th>
                        <th class="py-2 px-3 text-center w-16">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($costratio as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-slate-800 dark:text-slate-100">
                                {{ $d->kode_cr }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">
                                {{ formatIndo($d->tanggal) }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-200">
                                <span class="font-mono-num font-semibold text-[#284c9a] dark:text-blue-400">{{ $d->kode_akun }}</span> - {{ $d->nama_akun }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">
                                {{ textCamelCase($d->keterangan) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100">
                                {{ formatAngka($d->jumlah) }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300">
                                {{ $d->sumber }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-700 dark:text-slate-300">
                                {{ textUpperCase($d->nama_cabang) }}
                            </td>
                            <td class="py-1.5 px-3">
                                <div class="flex items-center justify-center gap-1">
                                    @can('costratio.delete')
                                        @if ($d->kode_sumber == '3')
                                            <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                                action="{{ route('costratio.delete', Crypt::encrypt($d->kode_cr)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                                    title="Hapus">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 px-4 text-center">
                                <div class="flex flex-col items-center justify-center gap-1.5 text-slate-400">
                                    <i data-lucide="database-zap" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Tidak ada data cost ratio ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($costratio->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $costratio->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- ================= MODAL INPUT COST RATIO (STATIC BACKDROP) ================= -->
<div id="modalCostRatio" class="fixed inset-0 z-50 hidden">
    <div id="modalCostRatioBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalCostRatioDialog"
            class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            <!-- Modal Header Bersih -->
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 rounded-t-xl">
                <div class="flex items-center gap-2">
                    <i data-lucide="bar-chart-2" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Input Cost Ratio</h5>
                </div>
                <button type="button" id="btnCloseModalCostRatio"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="loadmodalCostRatio" class="p-4 sm:p-5"></div>
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

        const $modal = $('#modalCostRatio');
        const $dialog = $('#modalCostRatioDialog');
        const $backdrop = $('#modalCostRatioBackdrop');
        const $content = $('#loadmodalCostRatio');

        function openModal() {
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $content.html('');
        }

        window.closeModalCostRatio = closeModal;

        $('#btnCloseModalCostRatio').on('click', closeModal);

        $backdrop.on('click', function(e) {
            e.stopPropagation();
            $dialog.addClass('scale-[1.015]');
            setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
        });

        $('#btnCreateCostRatio').on('click', function(e) {
            e.preventDefault();
            openModal();
            $content.html(`
                <div class="py-10 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500">Memuat formulir...</span>
                </div>
            `);
            $content.load('/costratio/create', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Inline validation untuk Cetak/Export jika rentang tanggal belum dipilih
        const $dariSearch = $('#dari_search');
        const $sampaiSearch = $('#sampai_search');

        function setFilterError($el, msg) {
            const $col = $el.closest('div').parent();
            $el.addClass('border-rose-500 ring-1 ring-rose-500');
            $el.closest('.relative').find('.prefix-icon').removeClass('text-slate-400').addClass('text-rose-500');
            $col.find('.field-error-msg').remove();
            $col.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>${msg}</span>
                </p>
            `);
        }

        function clearFilterError($el) {
            const $col = $el.closest('div').parent();
            $el.removeClass('border-rose-500 ring-1 ring-rose-500');
            $el.closest('.relative').find('.prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $col.find('.field-error-msg').remove();
        }

        $dariSearch.add($sampaiSearch).on('change input', function() {
            if ($(this).val()) clearFilterError($(this));
        });

        $('#formCetakCostRatio').on('submit', function(e) {
            const dari = $('#dari_cetak').val();
            const sampai = $('#sampai_cetak').val();
            let hasError = false;
            let firstInvalid = null;

            if (!dari) {
                setFilterError($dariSearch, 'Pilih tanggal awal terlebih dahulu.');
                hasError = true;
                firstInvalid = firstInvalid || $dariSearch;
            }
            if (!sampai) {
                setFilterError($sampaiSearch, 'Pilih tanggal akhir terlebih dahulu.');
                hasError = true;
                firstInvalid = firstInvalid || $sampaiSearch;
            }

            if (hasError) {
                e.preventDefault();
                if (firstInvalid) firstInvalid.focus();
                return false;
            }
        });
    });
</script>
@endpush

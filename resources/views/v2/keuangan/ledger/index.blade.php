@extends('v2.layouts.app')
@section('titlepage', 'Ledger')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Ledger</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- SUB-NAVIGATION TABS (Saldo Awal, Ledger) -->
    @include('v2.layouts.navigation_ledger')

    <!-- PAGE TITLE BAR & ACTION BUTTON -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Ledger Bank</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Manajemen pembukuan dan mutasi rekening koran ledger bank.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('ledger.create')
                <button type="button" id="btnCreateLedger"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Input Ledger</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- FILTER SECTION (COMPACT NO-LABEL V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
        <form action="{{ route('ledger.index') }}" method="GET" id="formFilterLedger" class="w-full">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5 items-center">
                <!-- Pilihan Bank -->
                <div class="relative select2-container-v2 lg:col-span-4">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="landmark" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_bank_search" id="kode_bank_filter" class="select2Kodebanksearch w-full">
                        <option value="">Pilih Bank</option>
                        @foreach ($bank as $d)
                            <option value="{{ $d->kode_bank }}" {{ Request('kode_bank_search') == $d->kode_bank ? 'selected' : '' }}>
                                {{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dari Tanggal -->
                <div class="relative lg:col-span-3">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="dari" id="dari_filter" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Sampai Tanggal -->
                <div class="relative lg:col-span-3">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="sampai" id="sampai_filter" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Tombol Cari & Reset Filter -->
                <div class="flex items-center gap-1.5 lg:col-span-2">
                    <button type="submit"
                        class="flex-1 h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center justify-center gap-1 shadow-xs cursor-pointer shrink-0">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>

                    @if (request()->hasAny(['kode_bank_search', 'dari', 'sampai']))
                        <a href="{{ route('ledger.index') }}"
                            class="h-[36px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 text-slate-500 text-xs font-bold transition flex items-center justify-center cursor-pointer shrink-0" title="Reset Filter">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- DATA TABLE CARD (V2 COMPACT ~36PX) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <!-- BARIS 1: STRIP JUDUL TABEL (bg-[#284c9a]) -->
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="book-open" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Data Ledger Bank</h5>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-white/10 text-white border border-white/20 font-mono-num">
                    Total: {{ count($ledger) }} Transaksi
                </span>
            </div>
        </div>

        <!-- TABLE CONTENT (KOLOM TERPISAH MANDIRI, TANPA DATA BERTUMPUK) -->
        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <!-- BARIS 2: HEADER KOLOM (bg-[#1e3a78]) -->
                <thead class="bg-[#1e3a78] text-white/90">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">TANGGAL</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider min-w-[140px]">PELANGGAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider min-w-[200px]">KETERANGAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider min-w-[180px]">KODE AKUN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">PRT</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">DEBET</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">KREDIT</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">SALDO</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-16 sticky right-0 z-10 bg-[#1e3a78]">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    <!-- BARIS SALDO AWAL -->
                    <tr class="bg-slate-50/80 dark:bg-slate-800/40 font-bold">
                        <td colspan="7" class="py-2 px-3.5 text-slate-800 dark:text-slate-100 uppercase tracking-wider text-xs">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="archive" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                                <span>SALDO AWAL</span>
                            </div>
                        </td>
                        <td class="py-2 px-3 text-right font-mono-num text-xs font-black">
                            @if ($saldo_awal != null)
                                <span class="text-[#284c9a] dark:text-blue-400">
                                    {{ formatAngka($saldo_awal->jumlah - ($mutasi ? $mutasi->debet : 0) + ($mutasi ? $mutasi->kredit : 0)) }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300">
                                    BELUM DI SET
                                </span>
                            @endif
                        </td>
                        <td class="py-2 px-3 sticky right-0 z-10 bg-slate-50/80 dark:bg-slate-800/40"></td>
                    </tr>

                    @php
                        $saldo = $saldo_awal != null ? $saldo_awal->jumlah - ($mutasi ? $mutasi->debet : 0) + ($mutasi ? $mutasi->kredit : 0) : 0;
                        $total_debet = 0;
                        $total_kredit = 0;
                    @endphp

                    @forelse ($ledger as $d)
                        @php
                            $debet = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                            $kredit = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                            $saldo = $saldo - $debet + $kredit;
                            $total_debet += $debet;
                            $total_kredit += $kredit;
                            $hasCr = !empty($d->kode_cr);
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition {{ $hasCr ? 'bg-blue-50/30 dark:bg-blue-950/20' : '' }}">
                            <!-- TANGGAL -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">
                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                            </td>

                            <!-- PELANGGAN -->
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-200">
                                {{ textCamelCase($d->pelanggan) ?: '-' }}
                            </td>

                            <!-- KETERANGAN -->
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200 max-w-[260px] truncate" title="{{ $d->keterangan }}">
                                <div class="flex items-center gap-1.5">
                                    <span>{{ textCamelCase($d->keterangan) }}</span>
                                    @if ($hasCr)
                                        <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9.5px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300" title="Cost Ratio: {{ $d->kode_cr }}">
                                            CR
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- KODE AKUN -->
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                                <span class="font-bold text-slate-700 dark:text-slate-300 font-mono-num">{{ $d->kode_akun }}</span>
                                <span class="text-slate-500 dark:text-slate-400 text-[11px]">- {{ $d->nama_akun }}</span>
                            </td>

                            <!-- PRT -->
                            <td class="py-1.5 px-3 text-center font-bold text-slate-700 dark:text-slate-300">
                                @php
                                    $prt = $d->kode_peruntukan == 'MP' ? $d->kode_peruntukan : $d->keterangan_peruntukan;
                                @endphp
                                @if ($prt)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $prt == 'MP' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300' }}">
                                        {{ $prt }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- DEBET -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-rose-600 dark:text-rose-400">
                                {{ $d->debet_kredit == 'D' ? formatAngka($d->jumlah) : '-' }}
                            </td>

                            <!-- KREDIT -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $d->debet_kredit == 'K' ? formatAngka($d->jumlah) : '-' }}
                            </td>

                            <!-- SALDO -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-black text-[#284c9a] dark:text-blue-400">
                                {{ formatAngka($saldo) }}
                            </td>

                            <!-- AKSI STICKY RIGHT -->
                            <td class="py-1.5 px-3 text-center sticky right-0 z-10 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/40">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Edit -->
                                    @can('ledger.edit')
                                        <button type="button" data-nobukti="{{ Crypt::encrypt($d->no_bukti) }}"
                                            class="btnEditLedger w-6 h-6 rounded-md bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-950/60 dark:text-amber-300 transition flex items-center justify-center cursor-pointer" title="Edit Transaksi Ledger">
                                            <i data-lucide="pencil" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    <!-- Delete -->
                                    @can('ledger.delete')
                                        <form action="{{ route('ledger.delete', Crypt::encrypt($d->no_bukti)) }}" method="POST" class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btnDeleteLedger w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition flex items-center justify-center cursor-pointer" title="Hapus Transaksi Ledger">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Silakan pilih bank dan periode tanggal, atau belum ada transaksi ledger</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                <!-- FOOTER TOTAL (bg-[#1e3a78]) -->
                @if (count($ledger) > 0)
                    <tfoot class="bg-[#1e3a78] text-white font-bold">
                        <tr>
                            <td colspan="5" class="py-2 px-3.5 text-center uppercase tracking-wider text-xs">
                                TOTAL TRANSAKSI
                            </td>
                            <td class="py-2 px-3 text-right font-mono-num text-xs text-rose-300">
                                {{ formatAngka($total_debet) }}
                            </td>
                            <td class="py-2 px-3 text-right font-mono-num text-xs text-emerald-300">
                                {{ formatAngka($total_kredit) }}
                            </td>
                            <td class="py-2 px-3 text-right font-mono-num text-xs text-white font-black">
                                {{ formatAngka($saldo) }}
                            </td>
                            <td class="py-2 px-3 sticky right-0 z-10 bg-[#1e3a78]"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

<!-- ================= MODAL CREATE LEDGER (STATIC BACKDROP) ================= -->
<div id="modalLedger" class="fixed inset-0 z-50 hidden">
    <div id="modalLedgerBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalLedgerDialog" class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="book-open" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalLedgerTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Input Ledger</h5>
                </div>
                <button type="button" id="btnCloseModalLedger"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="modalLedgerContent" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>

<!-- ================= MODAL EDIT LEDGER (STATIC BACKDROP) ================= -->
<div id="modalEditLedger" class="fixed inset-0 z-50 hidden">
    <div id="modalEditLedgerBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalEditLedgerDialog" class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="pencil" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalEditLedgerTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Edit Ledger</h5>
                </div>
                <button type="button" id="btnCloseModalEditLedger"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="modalEditLedgerContent" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        const $select2Bank = $('.select2Kodebanksearch');
        if ($select2Bank.length) {
            $select2Bank.select2({
                placeholder: 'Pilih Bank',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        function initModalHelper(modalId, dialogId, backdropId, closeBtnId) {
            const $modal = $(modalId);
            const $dialog = $(dialogId);
            const $backdrop = $(backdropId);
            const $closeBtn = $(closeBtnId);

            function open() {
                $modal.removeClass('hidden');
                $('body').addClass('overflow-hidden');
            }

            function close() {
                $modal.addClass('hidden');
                $('body').removeClass('overflow-hidden');
                $modal.find(`${modalId}Content`).html('');
            }

            $closeBtn.on('click', close);

            $backdrop.on('click', function(e) {
                e.stopPropagation();
                $dialog.addClass('scale-[1.015] shadow-2xl');
                setTimeout(() => $dialog.removeClass('scale-[1.015] shadow-2xl'), 150);
            });

            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && !$modal.hasClass('hidden')) {
                    $dialog.addClass('scale-[1.015] shadow-2xl');
                    setTimeout(() => $dialog.removeClass('scale-[1.015] shadow-2xl'), 150);
                }
            });

            return { open, close };
        }

        window.modalLedgerHelper = initModalHelper('#modalLedger', '#modalLedgerDialog', '#modalLedgerBackdrop', '#btnCloseModalLedger');
        window.modalEditLedgerHelper = initModalHelper('#modalEditLedger', '#modalEditLedgerDialog', '#modalEditLedgerBackdrop', '#btnCloseModalEditLedger');

        const loadingSpinner = `
            <div class="py-12 flex flex-col items-center justify-center gap-2">
                <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs text-slate-500 font-semibold">Memuat formulir...</span>
            </div>
        `;

        // Open Create Modal
        $("#btnCreateLedger").on('click', function(e) {
            e.preventDefault();
            $("#modalLedgerTitle").text("Input Ledger");
            $("#modalLedgerContent").html(loadingSpinner);
            modalLedgerHelper.open();
            $("#modalLedgerContent").load(`/ledger/create`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Open Edit Modal
        $(document).on('click', '.btnEditLedger', function(e) {
            e.preventDefault();
            const nobukti = $(this).data("nobukti");
            $("#modalEditLedgerTitle").text("Edit Ledger");
            $("#modalEditLedgerContent").html(loadingSpinner);
            modalEditLedgerHelper.open();
            $("#modalEditLedgerContent").load(`/ledger/${nobukti}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Delete Ledger Confirm
        $(document).on('click', '.btnDeleteLedger', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Transaksi Ledger?',
                text: 'Data transaksi ledger yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#284c9a',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

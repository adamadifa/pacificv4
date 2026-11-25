@extends('v2.layouts.app')
@section('titlepage', 'Saldo Kas Besar Pusat')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Saldo Kas Besar Pusat</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- SUB-NAVIGATION TABS -->
    @include('v2.layouts.navigation_mutasikeuangan')

    <!-- PAGE TITLE BAR & ACTION BUTTON -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Saldo Kas Besar Pusat</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Manajemen saldo kas besar keuangan pusat.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('sakasbesarkeuangan.create')
                <button type="button" id="btnCreateSaldoKasBesarPusat"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Input Saldo Kas Besar</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- FILTER SECTION (COMPACT NO-LABEL V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
        <form action="{{ route('sakasbesarkeuanganpusat.index') }}" method="GET" id="formFilterSaldoKasBesarPusat" class="w-full">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5 items-center">
                <div class="relative lg:col-span-5">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="dari" id="dari_filter" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <div class="relative lg:col-span-5">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="sampai" id="sampai_filter" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <div class="flex items-center gap-1.5 lg:col-span-2">
                    <button type="submit"
                        class="flex-1 h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center justify-center gap-1 shadow-xs cursor-pointer shrink-0">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>

                    @if (request()->hasAny(['dari', 'sampai']))
                        <a href="{{ route('sakasbesarkeuanganpusat.index') }}"
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
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="landmark" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Data Saldo Kas Besar Pusat</h5>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-white/10 text-white border border-white/20 font-mono-num">
                    Total: {{ count($saldokasbesar) }} Data
                </span>
            </div>
        </div>

        <div class="overflow-x-auto min-h-[260px]">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">TANGGAL</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider min-w-[260px]">KETERANGAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">DEBET</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">KREDIT</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-14 sticky right-0 z-10 bg-[#1e3a78]">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    @forelse ($saldokasbesar as $d)
                        @php
                            $debet = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                            $kredit = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">
                                {{ DateToIndo($d->tanggal) }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-800 dark:text-slate-200">{{ $d->keterangan }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">
                                {{ formatAngkaDesimal($debet) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-rose-600 dark:text-rose-400">
                                {{ formatAngkaDesimal($kredit) }}
                            </td>
                            <td class="py-1.5 px-3 text-center sticky right-0 z-10 bg-white dark:bg-slate-900">
                                <form action="{{ route('sakasbesarkeuangan.delete', Crypt::encrypt($d->id)) }}" method="POST" class="inline-flex m-0 p-0 deleteform">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btnDeleteSaldoKasBesarPusat w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition flex items-center justify-center cursor-pointer" title="Hapus Data">
                                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Belum ada data saldo kas besar pusat</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= MODAL SALDO KAS BESAR PUSAT (STATIC BACKDROP) ================= -->
<div id="modalSaldoKasBesarPusat" class="fixed inset-0 z-50 hidden">
    <div id="modalSaldoKasBesarPusatBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalSaldoKasBesarPusatDialog" class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="landmark" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalSaldoKasBesarPusatTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Input Saldo Kas Besar Pusat</h5>
                </div>
                <button type="button" id="btnCloseModalSaldoKasBesarPusat"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="modalSaldoKasBesarPusatContent" class="p-4 sm:p-5 max-h-[85vh] overflow-y-auto"></div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $(".flatpickr-date").flatpickr({ dateFormat: "Y-m-d", allowInput: false });

        function initModalHelper(modalId, dialogId, backdropId, closeBtnId) {
            const $modal = $(modalId), $dialog = $(dialogId), $backdrop = $(backdropId), $closeBtn = $(closeBtnId);
            function open() { $modal.removeClass('hidden'); $('body').addClass('overflow-hidden'); }
            function close() { $modal.addClass('hidden'); $('body').removeClass('overflow-hidden'); $modal.find(`${modalId}Content`).html(''); }
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

        window.modalSaldoKasBesarPusatHelper = initModalHelper('#modalSaldoKasBesarPusat', '#modalSaldoKasBesarPusatDialog', '#modalSaldoKasBesarPusatBackdrop', '#btnCloseModalSaldoKasBesarPusat');

        const loadingSpinner = `
            <div class="py-12 flex flex-col items-center justify-center gap-2">
                <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs text-slate-500 font-semibold">Memuat formulir...</span>
            </div>
        `;

        $("#btnCreateSaldoKasBesarPusat").on('click', function(e) {
            e.preventDefault();
            $("#modalSaldoKasBesarPusatTitle").text("Input Saldo Kas Besar Pusat");
            $("#modalSaldoKasBesarPusatContent").html(loadingSpinner);
            modalSaldoKasBesarPusatHelper.open();
            $("#modalSaldoKasBesarPusatContent").load('/sakasbesarkeuangan/createpusat', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '.btnDeleteSaldoKasBesarPusat', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Saldo Kas Besar?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#284c9a',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
@endpush

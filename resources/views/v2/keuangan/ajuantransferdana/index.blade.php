@extends('v2.layouts.app')
@section('titlepage', 'Ajuan Transfer Dana')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Ajuan Transfer Dana</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION BUTTON -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Ajuan Transfer Dana</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Manajemen pengajuan transfer dana.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('ajuantransfer.create')
                <button type="button" id="btnCreateAjuanTransfer"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Buat Ajuan</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- FILTER FORM (NO-LABEL COMPACT V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
        <form action="{{ route('ajuantransfer.index') }}" method="GET" id="formSearch" class="w-full">
            <div class="grid grid-cols-1 sm:grid-cols-2 @hasanyrole($roles_show_cabang) lg:grid-cols-12 @else lg:grid-cols-6 @endhasanyrole gap-2.5 items-center">
                <!-- Dari Tanggal -->
                <div class="relative @hasanyrole($roles_show_cabang) lg:col-span-3 @else lg:col-span-2 @endhasanyrole">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="dari" id="dari_filter" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Sampai Tanggal -->
                <div class="relative @hasanyrole($roles_show_cabang) lg:col-span-3 @else lg:col-span-2 @endhasanyrole">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="sampai" id="sampai_filter" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Cabang (Jika Ada Hak Akses) -->
                @hasanyrole($roles_show_cabang)
                    <div class="relative select2-container-v2 lg:col-span-3">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_cabang_search" id="kode_cabang_filter" class="select2Kodecabangsearch w-full">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ textUpperCase($c->nama_cabang) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endhasanyrole

                <!-- Tombol Cari, Cetak, Export & Reset -->
                <div class="flex items-center gap-1.5 @hasanyrole($roles_show_cabang) lg:col-span-3 @else lg:col-span-2 @endhasanyrole">
                    <button type="submit"
                        class="flex-1 h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center justify-center gap-1 shadow-xs cursor-pointer shrink-0">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>

                    @can('ajuantransfer.show')
                        <button type="button" id="btnCetakAjuan"
                            class="h-[36px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 text-slate-600 dark:text-slate-300 text-xs font-bold transition flex items-center justify-center cursor-pointer shrink-0" title="Cetak Ajuan">
                            <i data-lucide="printer" class="w-3.5 h-3.5 text-blue-600"></i>
                        </button>
                        <button type="button" id="btnExportAjuan"
                            class="h-[36px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 text-slate-600 dark:text-slate-300 text-xs font-bold transition flex items-center justify-center cursor-pointer shrink-0" title="Export Excel">
                            <i data-lucide="download" class="w-3.5 h-3.5 text-emerald-600"></i>
                        </button>
                    @endcan

                    @if (request()->hasAny(['dari', 'sampai', 'kode_cabang_search']))
                        <a href="{{ route('ajuantransfer.index') }}"
                            class="h-[36px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 text-slate-500 text-xs font-bold transition flex items-center justify-center cursor-pointer shrink-0" title="Reset Filter">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>

        @can('ajuantransfer.show')
            <form action="{{ route('ajuantransfer.cetak') }}" method="GET" id="formCetakAjuan" target="_blank" class="hidden">
                <input type="hidden" name="dari" id="dari_cetak" value="{{ Request('dari') }}" />
                <input type="hidden" name="sampai" id="sampai_cetak" value="{{ Request('sampai') }}" />
                <input type="hidden" name="kode_cabang_search" id="kode_cabang_cetak" value="{{ Request('kode_cabang_search') }}" />
                <input type="hidden" name="exportButton" id="export_flag" value="" />
            </form>
        @endcan
    </div>

    <!-- DATA TABLE (V2 COMPACT ~36PX) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <!-- BARIS 1: STRIP JUDUL TABEL (bg-[#284c9a]) -->
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="send" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Data Ajuan Transfer Dana</h5>
            </div>
            @php
                $totalData = method_exists($ajuantransfer, 'total') ? $ajuantransfer->total() : $ajuantransfer->count();
            @endphp
            <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-white/10 text-white border border-white/20 font-mono-num">
                Total: {{ $totalData }} Data
            </span>
        </div>

        <!-- TABLE CONTENT (KOLOM TERPISAH MANDIRI, TANPA DATA BERTUMPUK) -->
        <div class="overflow-x-auto min-h-[280px]">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <!-- BARIS 2: HEADER KOLOM (bg-[#1e3a78]) -->
                <thead class="bg-[#1e3a78] text-white/90">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">NO. PENGAJUAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">TANGGAL</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">NAMA</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">BANK</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">NO. REKENING</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">JUMLAH</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">KETERANGAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">VALIDASI</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">STATUS</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-24 sticky right-0 z-10 bg-[#1e3a78]">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    @forelse ($ajuantransfer as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <!-- NO. PENGAJUAN -->
                            <td class="py-1.5 px-3 text-center font-bold font-mono-num">
                                @if (!empty($d->bukti))
                                    <a href="{{ $d->bukti }}" target="_blank" class="text-[#284c9a] dark:text-blue-400 hover:underline flex items-center justify-center gap-1">
                                        <span>{{ $d->no_pengajuan }}</span>
                                        <i data-lucide="external-link" class="w-3 h-3 text-blue-500"></i>
                                    </a>
                                @else
                                    <span class="text-[#284c9a] dark:text-blue-400">{{ $d->no_pengajuan }}</span>
                                @endif
                            </td>

                            <!-- TANGGAL -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">
                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                            </td>

                            <!-- NAMA -->
                            <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-white">
                                {{ textUpperCase($d->nama) }}
                            </td>

                            <!-- BANK -->
                            <td class="py-1.5 px-3 text-center font-medium text-slate-700 dark:text-slate-300">
                                {{ textUpperCase($d->nama_bank) }}
                            </td>

                            <!-- NO. REKENING -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">
                                {{ $d->no_rekening ?: '-' }}
                            </td>

                            <!-- JUMLAH -->
                            <td class="py-1.5 px-3 text-right font-bold font-mono-num text-[#284c9a] dark:text-blue-400">
                                {{ formatAngka($d->jumlah) }}
                            </td>

                            <!-- KETERANGAN -->
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400 max-w-[220px] truncate" title="{{ $d->keterangan }}">
                                {{ $d->keterangan ?: '-' }}
                            </td>

                            <!-- VALIDASI -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status == '1')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                        <i data-lucide="check-circle-2" class="w-3 h-3 text-emerald-600"></i>
                                        <span>Valid</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">
                                        <i data-lucide="clock" class="w-3 h-3 text-amber-600"></i>
                                        <span>Pending</span>
                                    </span>
                                @endif
                            </td>

                            <!-- STATUS -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status == '1')
                                    @if (!empty($d->kode_setoran))
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold {{ $d->status_setoran == '1' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60' }}">
                                            <i data-lucide="{{ $d->status_setoran == '1' ? 'check' : 'clock' }}" class="w-3 h-3"></i>
                                            <span>{{ !empty($d->tanggal_proses) ? date('d-m-Y', strtotime($d->tanggal_proses)) : 'Proses' }}</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-sky-50 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300 border border-sky-200 dark:border-sky-800/60">
                                            <i data-lucide="info" class="w-3 h-3 text-sky-600"></i>
                                            <span>Belum diproses</span>
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60">
                                        <i data-lucide="alert-circle" class="w-3 h-3 text-rose-600"></i>
                                        <span>Belum divalidasi</span>
                                    </span>
                                @endif
                            </td>

                            <!-- # (AKSI STICKY RIGHT) -->
                            <td class="py-1.5 px-3 text-center sticky right-0 z-10 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/40">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Edit -->
                                    @can('ajuantransfer.edit')
                                        @if ($d->status === '0')
                                            <button type="button" no_pengajuan="{{ $d->no_pengajuan }}"
                                                class="btnEditAjuan w-6 h-6 rounded-md bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-950/60 dark:text-amber-300 transition flex items-center justify-center cursor-pointer" title="Edit Ajuan">
                                                <i data-lucide="pencil" class="w-3 h-3"></i>
                                            </button>
                                        @endif
                                    @endcan

                                    <!-- Delete -->
                                    @can('ajuantransfer.delete')
                                        @if ($d->status === '0')
                                            <form action="{{ route('ajuantransfer.delete', Crypt::encrypt($d->no_pengajuan)) }}" method="POST" class="inline-flex m-0 p-0 deleteform">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btnDeleteAjuan w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition flex items-center justify-center cursor-pointer" title="Hapus Ajuan">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan

                                    <!-- Validasi / Approve -->
                                    @can('ajuantransfer.approve')
                                        @if ($d->status === '0')
                                            <a href="{{ route('ajuantransfer.approve', Crypt::encrypt($d->no_pengajuan)) }}"
                                                class="btnApproveAjuan w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300 transition flex items-center justify-center cursor-pointer" title="Validasi Ajuan">
                                                <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                            </a>
                                        @else
                                            @if (empty($d->kode_setoran))
                                                <a href="{{ route('ajuantransfer.cancelapprove', Crypt::encrypt($d->no_pengajuan)) }}"
                                                    class="btnBatalApproveAjuan w-6 h-6 rounded-md bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-950/60 dark:text-amber-300 transition flex items-center justify-center cursor-pointer" title="Batalkan Validasi">
                                                    <i data-lucide="x-circle" class="w-3 h-3"></i>
                                                </a>
                                            @endif
                                        @endif
                                    @endcan

                                    <!-- Proses Transfer -->
                                    @can('ajuantransfer.proses')
                                        @if ($d->status == '1' && empty($d->kode_setoran))
                                            <button type="button" no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}"
                                                class="btnProsesAjuan w-6 h-6 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-950/60 dark:text-blue-300 transition flex items-center justify-center cursor-pointer" title="Proses Transfer Dana">
                                                <i data-lucide="external-link" class="w-3 h-3"></i>
                                            </button>
                                        @else
                                            @if ($d->status_setoran == 0 && !empty($d->kode_setoran))
                                                <form action="{{ route('ajuantransfer.cancelproses', Crypt::encrypt($d->no_pengajuan)) }}" method="POST" class="inline-flex m-0 p-0 deleteform">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btnBatalProsesAjuan w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition flex items-center justify-center cursor-pointer" title="Batalkan Proses Transfer">
                                                        <i data-lucide="square-x" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Belum ada data ajuan transfer dana</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION BAR -->
        @if ($ajuantransfer->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $ajuantransfer->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- ================= MODAL V2 UNIFIED (STATIC BACKDROP) ================= -->
<div id="modalAjuantransfer" class="fixed inset-0 z-50 hidden">
    <div id="modalAjuantransferBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalAjuantransferDialog" class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="send" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalAjuantransferTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Ajuan Transfer Dana</h5>
                </div>
                <button type="button" id="btnCloseModalAjuantransfer"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="modalAjuantransferContent" class="p-4 sm:p-5"></div>
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

        const $select2Cabang = $('.select2Kodecabangsearch');
        if ($select2Cabang.length) {
            $select2Cabang.select2({
                placeholder: 'Semua Cabang',
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
                $("#modalAjuantransferContent").html('');
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

        window.modalAjuantransfer = initModalHelper('#modalAjuantransfer', '#modalAjuantransferDialog', '#modalAjuantransferBackdrop', '#btnCloseModalAjuantransfer');

        const loadingSpinner = `
            <div class="py-12 flex flex-col items-center justify-center gap-2">
                <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs text-slate-500 font-semibold">Memuat formulir...</span>
            </div>
        `;

        // Action: Buat Ajuan (Create)
        $("#btnCreateAjuanTransfer").on('click', function(e) {
            e.preventDefault();
            $("#modalAjuantransferTitle").text("Buat Ajuan Transfer Dana");
            $("#modalAjuantransferContent").html(loadingSpinner);
            modalAjuantransfer.open();
            $("#modalAjuantransferContent").load(`/ajuantransfer/create`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Edit Ajuan
        $(document).on('click', '.btnEditAjuan', function(e) {
            e.preventDefault();
            const no_pengajuan = $(this).attr("no_pengajuan");
            $("#modalAjuantransferTitle").text("Edit Ajuan Transfer Dana");
            $("#modalAjuantransferContent").html(loadingSpinner);
            modalAjuantransfer.open();
            $("#modalAjuantransferContent").load(`/ajuantransfer/${no_pengajuan}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Proses Transfer
        $(document).on('click', '.btnProsesAjuan', function(e) {
            e.preventDefault();
            const no_pengajuan = $(this).attr("no_pengajuan");
            $("#modalAjuantransferTitle").text("Proses Ajuan Transfer Dana");
            $("#modalAjuantransferContent").html(loadingSpinner);
            modalAjuantransfer.open();
            $("#modalAjuantransferContent").load(`/ajuantransfer/${no_pengajuan}/proses`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Validasi (Approve) Confirm
        $(document).on('click', '.btnApproveAjuan', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            Swal.fire({
                title: 'Validasi Pengajuan Transfer?',
                text: 'Status ajuan transfer dana ini akan diubah menjadi Valid!',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#284c9a',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Validasi!',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });

        // Action: Batalkan Validasi Confirm
        $(document).on('click', '.btnBatalApproveAjuan', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            Swal.fire({
                title: 'Batalkan Validasi?',
                text: 'Status ajuan transfer dana ini akan dikembalikan menjadi Pending!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#284c9a',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });

        // Action: Hapus Ajuan Confirm
        $(document).on('click', '.btnDeleteAjuan', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Ajuan Transfer Dana?',
                text: 'Data ajuan yang dihapus tidak dapat dikembalikan!',
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

        // Action: Batalkan Proses Confirm
        $(document).on('click', '.btnBatalProsesAjuan', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Batalkan Proses Transfer?',
                text: 'Data setoran pusat untuk ajuan ini akan dihapus dan status dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#284c9a',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Cetak & Export Trigger
        $("#btnCetakAjuan").on('click', function(e) {
            e.preventDefault();
            const dari = $("#dari_filter").val();
            const sampai = $("#sampai_filter").val();
            if (!dari || !sampai) {
                Swal.fire({
                    title: "Perhatian!",
                    text: "Silakan pilih periode Dari dan Sampai Tanggal terlebih dahulu!",
                    icon: "warning",
                    confirmButtonColor: '#284c9a'
                });
                return;
            }
            $("#dari_cetak").val(dari);
            $("#sampai_cetak").val(sampai);
            $("#kode_cabang_cetak").val($("#kode_cabang_filter").val() || '');
            $("#export_flag").val('');
            $("#formCetakAjuan").submit();
        });

        $("#btnExportAjuan").on('click', function(e) {
            e.preventDefault();
            const dari = $("#dari_filter").val();
            const sampai = $("#sampai_filter").val();
            if (!dari || !sampai) {
                Swal.fire({
                    title: "Perhatian!",
                    text: "Silakan pilih periode Dari dan Sampai Tanggal terlebih dahulu!",
                    icon: "warning",
                    confirmButtonColor: '#284c9a'
                });
                return;
            }
            $("#dari_cetak").val(dari);
            $("#sampai_cetak").val(sampai);
            $("#kode_cabang_cetak").val($("#kode_cabang_filter").val() || '');
            $("#export_flag").val('true');
            $("#formCetakAjuan").submit();
        });
    });
</script>
@endpush

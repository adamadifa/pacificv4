@extends('v2.layouts.app')
@section('titlepage', 'Klaim Kas Kecil')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Klaim Kas Kecil</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- SUB-NAVIGATION TABS (Saldo Awal, Kas Kecil, Klaim Kas Kecil) -->
    @include('v2.layouts.navigation_kaskecil')

    <!-- PAGE TITLE BAR & ACTION BUTTON -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Klaim Kas Kecil</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Manajemen pengajuan dan proses klaim penggantian kas kecil cabang.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('klaimkaskecil.create')
                <button type="button" id="btnCreateKlaim"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Buat Klaim</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- FILTER SECTION (COMPACT NO-LABEL V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
        <form action="{{ route('klaimkaskecil.index') }}" method="GET" id="formFilterKlaim" class="w-full">
            <div class="grid grid-cols-1 sm:grid-cols-2 @hasanyrole($roles_show_cabang) lg:grid-cols-12 @else lg:grid-cols-8 @endhasanyrole gap-2.5 items-center">
                <!-- Dari Tanggal -->
                <div class="relative @hasanyrole($roles_show_cabang) lg:col-span-3 @else lg:col-span-3 @endhasanyrole">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="dari" id="dari_filter" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Sampai Tanggal -->
                <div class="relative @hasanyrole($roles_show_cabang) lg:col-span-3 @else lg:col-span-3 @endhasanyrole">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="sampai" id="sampai_filter" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Cabang (Jika Multi-Cabang) -->
                @hasanyrole($roles_show_cabang)
                    <div class="relative select2-container-v2 lg:col-span-4">
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

                <!-- Tombol Cari & Reset Filter -->
                <div class="flex items-center gap-1.5 @hasanyrole($roles_show_cabang) lg:col-span-2 @else lg:col-span-2 @endhasanyrole">
                    <button type="submit"
                        class="flex-1 h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center justify-center gap-1 shadow-xs cursor-pointer shrink-0">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>

                    @if (request()->hasAny(['dari', 'sampai', 'kode_cabang_search']))
                        <a href="{{ route('klaimkaskecil.index') }}"
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
                <i data-lucide="clipboard-list" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Data Klaim Kas Kecil</h5>
            </div>
            @php
                $totalData = method_exists($klaimkaskecil, 'total') ? $klaimkaskecil->total() : $klaimkaskecil->count();
            @endphp
            <div class="flex items-center gap-2">
                <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-white/10 text-white border border-white/20 font-mono-num">
                    Total: {{ $totalData }} Klaim
                </span>
            </div>
        </div>

        <!-- TABLE CONTENT (KOLOM TERPISAH MANDIRI, TANPA DATA BERTUMPUK) -->
        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <!-- BARIS 2: HEADER KOLOM (bg-[#1e3a78]) -->
                <thead class="bg-[#1e3a78] text-white/90">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">KODE</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">TANGGAL</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider min-w-[220px]">KETERANGAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">STATUS</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">NO. BUKTI</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">DIPROSES</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">VALIDASI</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">JUMLAH</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-24 sticky right-0 z-10 bg-[#1e3a78]">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    @forelse ($klaimkaskecil as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <!-- KODE -->
                            <td class="py-1.5 px-3 text-center font-bold font-mono-num text-[#284c9a] dark:text-blue-400">
                                {{ $d->kode_klaim }}
                            </td>

                            <!-- TANGGAL -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">
                                {{ formatIndo($d->tanggal) }}
                            </td>

                            <!-- KETERANGAN -->
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200 max-w-[260px] truncate" title="{{ $d->keterangan }}">
                                {{ $d->keterangan }}
                            </td>

                            <!-- STATUS -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status == 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">
                                        <i data-lucide="clock" class="w-3 h-3 text-amber-600"></i>
                                        <span>Menunggu</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                        <i data-lucide="check-circle-2" class="w-3 h-3 text-emerald-600"></i>
                                        <span>Selesai</span>
                                    </span>
                                @endif
                            </td>

                            <!-- NO. BUKTI -->
                            <td class="py-1.5 px-3 text-center font-mono-num font-bold">
                                @if (!empty($d->no_bukti))
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60">
                                        {{ $d->no_bukti }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        <span>Pending</span>
                                    </span>
                                @endif
                            </td>

                            <!-- DIPROSES (TGL PROSES) -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">
                                {{ !empty($d->tgl_proses) ? formatIndo($d->tgl_proses) : '-' }}
                            </td>

                            <!-- VALIDASI -->
                            <td class="py-1.5 px-3 text-center">
                                @if (!empty($d->cekvalidasi))
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                        <i data-lucide="check-check" class="w-3 h-3 text-emerald-600"></i>
                                        <span>Valid</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">
                                        <i data-lucide="clock" class="w-3 h-3 text-amber-600"></i>
                                        <span>Belum Valid</span>
                                    </span>
                                @endif
                            </td>

                            <!-- JUMLAH -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ !empty($d->no_bukti) ? formatAngka($d->jumlah) : '-' }}
                            </td>

                            <!-- AKSI STICKY RIGHT -->
                            <td class="py-1.5 px-3 text-center sticky right-0 z-10 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/40">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Cetak PDF -->
                                    @can('klaimkaskecil.show')
                                        <a href="{{ route('klaimkaskecil.cetak', ['kode_klaim' => Crypt::encrypt($d->kode_klaim), 'export' => 0]) }}"
                                            target="_blank" class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-950/60 dark:text-blue-300 transition flex items-center justify-center cursor-pointer" title="Cetak Nota Klaim">
                                            <i data-lucide="printer" class="w-3 h-3"></i>
                                        </a>
                                        <a href="{{ route('klaimkaskecil.cetak', ['kode_klaim' => Crypt::encrypt($d->kode_klaim), 'export' => true]) }}"
                                            target="_blank" class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300 transition flex items-center justify-center cursor-pointer" title="Export Excel">
                                            <i data-lucide="download" class="w-3 h-3"></i>
                                        </a>
                                    @endcan

                                    <!-- Proses Klaim -->
                                    @can('klaimkaskecil.proses')
                                        @if (empty($d->no_bukti))
                                            <button type="button" data-kode="{{ Crypt::encrypt($d->kode_klaim) }}"
                                                class="btnProsesKlaim w-6 h-6 rounded-md bg-sky-50 text-sky-600 hover:bg-sky-100 dark:bg-sky-950/60 dark:text-sky-300 transition flex items-center justify-center cursor-pointer" title="Proses Klaim">
                                                <i data-lucide="settings" class="w-3 h-3"></i>
                                            </button>
                                        @else
                                            @if (empty($d->cekvalidasi))
                                                <form action="{{ route('klaimkaskecil.cancelproses', Crypt::encrypt($d->no_bukti)) }}" method="POST" class="inline-flex m-0 p-0 deleteform">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btnCancelProsesKlaim w-6 h-6 rounded-md bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-950/60 dark:text-amber-300 transition flex items-center justify-center cursor-pointer" title="Batalkan Proses">
                                                        <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    @endcan

                                    <!-- Approve / Validasi -->
                                    @can('klaimkaskecil.approve')
                                        @if (!empty($d->no_bukti) && empty($d->cekvalidasi))
                                            <a href="{{ route('klaimkaskecil.approve', Crypt::encrypt($d->no_bukti)) }}"
                                                class="btnApproveKlaim w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300 transition flex items-center justify-center cursor-pointer" title="Validasi / Approve Klaim">
                                                <i data-lucide="check-check" class="w-3 h-3"></i>
                                            </a>
                                        @else
                                            @if (!empty($d->cekvalidasi))
                                                <form action="{{ route('klaimkaskecil.cancelapprove', Crypt::encrypt($d->no_bukti)) }}" method="POST" class="inline-flex m-0 p-0 deleteform">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btnCancelApproveKlaim w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition flex items-center justify-center cursor-pointer" title="Batalkan Validasi">
                                                        <i data-lucide="x-circle" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    @endcan

                                    <!-- Delete -->
                                    @can('klaimkaskecil.delete')
                                        @if (empty($d->no_bukti))
                                            <form action="{{ route('klaimkaskecil.delete', Crypt::encrypt($d->kode_klaim)) }}" method="POST" class="inline-flex m-0 p-0 deleteform">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btnDeleteKlaim w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition flex items-center justify-center cursor-pointer" title="Hapus Klaim">
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
                            <td colspan="9" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Belum ada data pengajuan klaim kas kecil</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION BAR -->
        @if ($klaimkaskecil->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $klaimkaskecil->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- ================= MODAL BUAT KLAIM (STATIC BACKDROP) ================= -->
<div id="modalKlaim" class="fixed inset-0 z-50 hidden">
    <div id="modalKlaimBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalKlaimDialog" class="relative w-full max-w-5xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="clipboard-list" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalKlaimTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Buat Pengajuan Klaim Kas Kecil</h5>
                </div>
                <button type="button" id="btnCloseModalKlaim"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="modalKlaimContent" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>

<!-- ================= MODAL PROSES KLAIM (STATIC BACKDROP) ================= -->
<div id="modalProsesKlaim" class="fixed inset-0 z-50 hidden">
    <div id="modalProsesKlaimBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalProsesKlaimDialog" class="relative w-full max-w-5xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="settings" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalProsesKlaimTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Proses Klaim Kas Kecil</h5>
                </div>
                <button type="button" id="btnCloseModalProsesKlaim"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="modalProsesKlaimContent" class="p-4 sm:p-5"></div>
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

        window.modalKlaimHelper = initModalHelper('#modalKlaim', '#modalKlaimDialog', '#modalKlaimBackdrop', '#btnCloseModalKlaim');
        window.modalProsesKlaimHelper = initModalHelper('#modalProsesKlaim', '#modalProsesKlaimDialog', '#modalProsesKlaimBackdrop', '#btnCloseModalProsesKlaim');

        const loadingSpinner = `
            <div class="py-12 flex flex-col items-center justify-center gap-2">
                <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs text-slate-500 font-semibold">Memuat formulir...</span>
            </div>
        `;

        // Open Buat Klaim Modal
        $("#btnCreateKlaim").on('click', function(e) {
            e.preventDefault();
            $("#modalKlaimTitle").text("Buat Pengajuan Klaim Kas Kecil");
            $("#modalKlaimContent").html(loadingSpinner);
            modalKlaimHelper.open();
            $("#modalKlaimContent").load(`/klaimkaskecil/create`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Open Proses Klaim Modal
        $(document).on('click', '.btnProsesKlaim', function(e) {
            e.preventDefault();
            const kode = $(this).data("kode");
            $("#modalProsesKlaimTitle").text("Proses Klaim Kas Kecil");
            $("#modalProsesKlaimContent").html(loadingSpinner);
            modalProsesKlaimHelper.open();
            $("#modalProsesKlaimContent").load(`/klaimkaskecil/${kode}/proses`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Approve / Validasi Confirm
        $(document).on('click', '.btnApproveKlaim', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            Swal.fire({
                title: 'Validasi Pengajuan Klaim?',
                text: 'Status klaim kas kecil ini akan diubah menjadi Valid!',
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
        $(document).on('click', '.btnCancelApproveKlaim', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Batalkan Validasi Klaim?',
                text: 'Status validasi klaim kas kecil ini akan dibatalkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#284c9a',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Action: Batalkan Proses Confirm
        $(document).on('click', '.btnCancelProsesKlaim', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Batalkan Proses Klaim?',
                text: 'Transaksi ledger penggantian klaim ini akan dihapus dan status dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#284c9a',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Action: Hapus Klaim Confirm
        $(document).on('click', '.btnDeleteKlaim', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Pengajuan Klaim?',
                text: 'Data pengajuan klaim kas kecil yang dihapus tidak dapat dikembalikan!',
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

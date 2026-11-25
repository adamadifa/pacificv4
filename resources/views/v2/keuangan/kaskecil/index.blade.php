@extends('v2.layouts.app')
@section('titlepage', 'Kas Kecil')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Kas Kecil</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- SUB-NAVIGATION TABS (Saldo Awal, Kas Kecil, Klaim) -->
    @include('v2.layouts.navigation_kaskecil')

    <!-- PAGE TITLE BAR & ACTION BUTTON -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Kas Kecil</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Manajemen operasional dan mutasi kas kecil cabang.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('kaskecil.create')
                <button type="button" id="btnCreateKaskecil"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Input Kas Kecil</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- FILTER SECTION (COMPACT NO-LABEL V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
        <form action="{{ route('kaskecil.index') }}" method="GET" id="formFilterKaskecil" class="w-full">
            <div class="grid grid-cols-1 sm:grid-cols-2 @hasanyrole($roles_show_cabang) lg:grid-cols-12 @else lg:grid-cols-8 @endhasanyrole gap-2.5 items-center">
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

                <!-- No Bukti -->
                <div class="relative @hasanyrole($roles_show_cabang) lg:col-span-3 @else lg:col-span-2 @endhasanyrole">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="barcode" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="no_bukti_search" id="no_bukti_filter" value="{{ Request('no_bukti_search') }}" placeholder="No. Bukti..."
                        class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Cabang (Jika Multi-Cabang) -->
                @hasanyrole($roles_show_cabang)
                    <div class="relative select2-container-v2 lg:col-span-2">
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
                <div class="flex items-center gap-1.5 @hasanyrole($roles_show_cabang) lg:col-span-1 @else lg:col-span-2 @endhasanyrole">
                    <button type="submit"
                        class="flex-1 h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center justify-center gap-1 shadow-xs cursor-pointer shrink-0">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span class="sm:hidden @hasanyrole($roles_show_cabang) lg:hidden @else lg:inline @endhasanyrole">Cari</span>
                    </button>

                    @if (request()->hasAny(['dari', 'sampai', 'no_bukti_search', 'kode_cabang_search']))
                        <a href="{{ route('kaskecil.index') }}"
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
                <i data-lucide="wallet" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Data Operasional Kas Kecil</h5>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-white/10 text-white border border-white/20 font-mono-num">
                    Total: {{ count($kaskecil) }} Transaksi
                </span>
            </div>
        </div>

        <!-- TABLE CONTENT (KOLOM TERPISAH MANDIRI, TANPA DATA BERTUMPUK) -->
        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <!-- BARIS 2: HEADER KOLOM (bg-[#1e3a78]) -->
                <thead class="bg-[#1e3a78] text-white/90">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-10">NO</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">TANGGAL</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">NO. BUKTI</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider min-w-[200px]">KETERANGAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider min-w-[180px]">AKUN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">PENERIMAAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">PENGELUARAN</th>
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
                        <td class="py-2 px-3 text-right font-mono-num text-xs font-black text-[#284c9a] dark:text-blue-400">
                            {{ $saldoawal != null ? formatAngka($saldoawal->saldo_awal) : 0 }}
                        </td>
                        <td class="py-2 px-3 sticky right-0 z-10 bg-slate-50/80 dark:bg-slate-800/40"></td>
                    </tr>

                    @php
                        $saldo = $saldoawal != null ? $saldoawal->saldo_awal : 0;
                        $total_penerimaan = 0;
                        $total_pengeluaran = 0;
                    @endphp

                    @forelse ($kaskecil as $d)
                        @php
                            $penerimaan = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                            $pengeluaran = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                            $saldo += $penerimaan - $pengeluaran;
                            $total_penerimaan += $penerimaan;
                            $total_pengeluaran += $pengeluaran;
                            $hasKlaim = !empty($d->kode_klaim);
                            $hasCr = !empty($d->kode_cr);
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition {{ $hasKlaim ? 'bg-emerald-50/30 dark:bg-emerald-950/20' : '' }}">
                            <!-- NO -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">
                                @if ($hasKlaim)
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 text-[10px] font-bold" title="Klaim: {{ $d->kode_klaim }}">
                                        {{ $loop->iteration }}
                                    </span>
                                @else
                                    {{ $loop->iteration }}
                                @endif
                            </td>

                            <!-- TANGGAL -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">
                                {{ formatIndo($d->tanggal) }}
                            </td>

                            <!-- NO. BUKTI -->
                            <td class="py-1.5 px-3 text-center font-bold font-mono-num">
                                @if ($hasCr)
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[11px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300" title="Cost Ratio: {{ $d->kode_cr }}">
                                        {{ $d->no_bukti }}
                                    </span>
                                @else
                                    <span class="text-slate-800 dark:text-slate-200">{{ $d->no_bukti }}</span>
                                @endif
                            </td>

                            <!-- KETERANGAN -->
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200 max-w-[260px] truncate" title="{{ $d->keterangan }}">
                                {{ textCamelcase($d->keterangan) }}
                            </td>

                            <!-- AKUN -->
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                                <span class="font-bold text-slate-700 dark:text-slate-300 font-mono-num">{{ $d->kode_akun }}</span>
                                <span class="text-slate-500 dark:text-slate-400 text-[11px]">- {{ $d->nama_akun }}</span>
                            </td>

                            <!-- PENERIMAAN -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $penerimaan > 0 ? formatAngka($penerimaan) : '-' }}
                            </td>

                            <!-- PENGELUARAN -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-rose-600 dark:text-rose-400">
                                {{ $pengeluaran > 0 ? formatAngka($pengeluaran) : '-' }}
                            </td>

                            <!-- SALDO -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-black text-[#284c9a] dark:text-blue-400">
                                {{ formatAngka($saldo) }}
                            </td>

                            <!-- AKSI STICKY RIGHT -->
                            <td class="py-1.5 px-3 text-center sticky right-0 z-10 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/40">
                                @if ($d->keterangan != 'Penerimaan Kas Kecil')
                                    <div class="flex items-center justify-center gap-1">
                                        <!-- Edit -->
                                        @can('kaskecil.edit')
                                            <button type="button" data-id="{{ Crypt::encrypt($d->id) }}"
                                                class="btnEditKaskecil w-6 h-6 rounded-md bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-950/60 dark:text-amber-300 transition flex items-center justify-center cursor-pointer" title="Edit Transaksi">
                                                <i data-lucide="pencil" class="w-3 h-3"></i>
                                            </button>
                                        @endcan

                                        <!-- Delete -->
                                        @can('kaskecil.delete')
                                            @if (empty($d->kode_klaim))
                                                <form action="{{ route('kaskecil.delete', Crypt::encrypt($d->id)) }}" method="POST" class="inline-flex m-0 p-0 deleteform">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btnDeleteKaskecil w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition flex items-center justify-center cursor-pointer" title="Hapus Transaksi">
                                                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Belum ada data transaksi kas kecil untuk periode ini</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                <!-- FOOTER TOTAL (bg-[#1e3a78]) -->
                @if (count($kaskecil) > 0)
                    <tfoot class="bg-[#1e3a78] text-white font-bold">
                        <tr>
                            <td colspan="5" class="py-2 px-3.5 text-center uppercase tracking-wider text-xs">
                                TOTAL TRANSAKSI
                            </td>
                            <td class="py-2 px-3 text-right font-mono-num text-xs text-emerald-300">
                                {{ formatAngka($total_penerimaan) }}
                            </td>
                            <td class="py-2 px-3 text-right font-mono-num text-xs text-rose-300">
                                {{ formatAngka($total_pengeluaran) }}
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

<!-- ================= MODAL CREATE KAS KECIL (STATIC BACKDROP) ================= -->
<div id="modalKaskecil" class="fixed inset-0 z-50 hidden">
    <div id="modalKaskecilBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalKaskecilDialog" class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="wallet" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalKaskecilTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Input Kas Kecil</h5>
                </div>
                <button type="button" id="btnCloseModalKaskecil"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="modalKaskecilContent" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>

<!-- ================= MODAL EDIT KAS KECIL (STATIC BACKDROP) ================= -->
<div id="modalEditKaskecil" class="fixed inset-0 z-50 hidden">
    <div id="modalEditKaskecilBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalEditKaskecilDialog" class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="pencil" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalEditKaskecilTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Edit Kas Kecil</h5>
                </div>
                <button type="button" id="btnCloseModalEditKaskecil"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="modalEditKaskecilContent" class="p-4 sm:p-5"></div>
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

        window.modalKaskecilHelper = initModalHelper('#modalKaskecil', '#modalKaskecilDialog', '#modalKaskecilBackdrop', '#btnCloseModalKaskecil');
        window.modalEditKaskecilHelper = initModalHelper('#modalEditKaskecil', '#modalEditKaskecilDialog', '#modalEditKaskecilBackdrop', '#btnCloseModalEditKaskecil');

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
        $("#btnCreateKaskecil").on('click', function(e) {
            e.preventDefault();
            $("#modalKaskecilTitle").text("Input Kas Kecil");
            $("#modalKaskecilContent").html(loadingSpinner);
            modalKaskecilHelper.open();
            $("#modalKaskecilContent").load(`/kaskecil/create`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Open Edit Modal
        $(document).on('click', '.btnEditKaskecil', function(e) {
            e.preventDefault();
            const id = $(this).data("id");
            $("#modalEditKaskecilTitle").text("Edit Kas Kecil");
            $("#modalEditKaskecilContent").html(loadingSpinner);
            modalEditKaskecilHelper.open();
            $("#modalEditKaskecilContent").load(`/kaskecil/${id}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Delete Kas Kecil Confirm
        $(document).on('click', '.btnDeleteKaskecil', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Transaksi Kas Kecil?',
                text: 'Data transaksi kas kecil yang dihapus tidak dapat dikembalikan!',
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

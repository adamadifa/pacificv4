@extends('v2.layouts.app')
@section('titlepage', 'Setoran Pusat')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Kas Besar</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Setoran Pusat</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION BUTTON -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Setoran Pusat</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Manajemen setoran kas ke pusat.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('setoranpusat.create')
                <button type="button" id="btnCreateSetoranPusat"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Input Setoran</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- NAVIGATION TABS (V2 PILLS) -->
    @include('v2.layouts.navigation_kasbesar')

    <!-- FILTER FORM (NO-LABEL COMPACT V2) -->
    <form action="{{ route('setoranpusat.index') }}" method="GET" class="w-full">
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 @hasanyrole($roles_show_cabang) lg:grid-cols-12 @else lg:grid-cols-5 @endhasanyrole gap-2.5 items-center">
                <!-- Dari Tanggal -->
                <div class="relative @hasanyrole($roles_show_cabang) lg:col-span-3 @else lg:col-span-2 @endhasanyrole">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="dari" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Sampai Tanggal -->
                <div class="relative @hasanyrole($roles_show_cabang) lg:col-span-3 @else lg:col-span-2 @endhasanyrole">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="sampai" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Cabang (Jika Ada Hak Akses) -->
                @hasanyrole($roles_show_cabang)
                    <div class="relative select2-container-v2 lg:col-span-4">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_cabang_search" id="kode_cabang_search_spusat" class="select2Kodecabangsearch w-full">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ textUpperCase($c->nama_cabang) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endhasanyrole

                <!-- Tombol Cari & Reset -->
                <div class="flex items-center gap-1.5 @hasanyrole($roles_show_cabang) lg:col-span-2 @else lg:col-span-1 @endhasanyrole">
                    <button type="submit"
                        class="flex-1 h-[36px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>Cari</span>
                    </button>
                    @if (request()->hasAny(['dari', 'sampai', 'kode_cabang_search']))
                        <a href="{{ route('setoranpusat.index') }}"
                            class="h-[36px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 text-slate-500 text-xs font-bold transition flex items-center justify-center cursor-pointer shrink-0" title="Reset Filter">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </form>

    <!-- DATA TABLE CARD (V2 PACIFIC NAVY 2-TIER ~36PX) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <!-- BARIS 1: STRIP JUDUL TABEL (bg-[#284c9a]) -->
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="landmark" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Data Setoran Pusat</h5>
            </div>
            @php
                $totalData = method_exists($setoran_pusat, 'total') ? $setoran_pusat->total() : $setoran_pusat->count();
            @endphp
            <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-white/10 text-white border border-white/20 font-mono-num">
                Total: {{ $totalData }} Data
            </span>
        </div>

        <!-- TABLE CONTENT (KOLOM TERPISAH MANDIRI, TANPA DATA BERTUMPUK) -->
        <div class="overflow-x-auto min-h-[280px]">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <!-- HEADER KOLOM 2 BARIS (V2 PACIFIC NAVY) -->
                <thead>
                    <tr class="bg-[#1e3a78] text-white/90">
                        <th rowspan="2" class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center align-middle">TANGGAL</th>
                        <th rowspan="2" class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider align-middle">KETERANGAN</th>
                        <th colspan="4" class="py-1 px-3 text-[11px] font-bold uppercase tracking-wider text-center border-b border-white/10 bg-[#162e60]">RINCIAN SETORAN</th>
                        <th rowspan="2" class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right align-middle">JUMLAH</th>
                        <th rowspan="2" class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center align-middle">BANK</th>
                        <th rowspan="2" class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center align-middle">STATUS</th>
                        <th rowspan="2" class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center align-middle w-24 sticky right-0 z-10 bg-[#1e3a78]">#</th>
                    </tr>
                    <tr class="bg-[#162e60] text-white/90">
                        <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-right">KERTAS</th>
                        <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-right">LOGAM</th>
                        <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-right">TRANSFER</th>
                        <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-right">GIRO</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    @forelse ($setoran_pusat as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <!-- TANGGAL -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">
                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                            </td>

                            <!-- KETERANGAN -->
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100 max-w-[280px] truncate" title="{{ textUpperCase($d->keterangan) }}">
                                {{ textUpperCase($d->keterangan) }}
                            </td>

                            <!-- RINCIAN SETORAN: KERTAS -->
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                {{ formatAngka($d->setoran_kertas) }}
                            </td>

                            <!-- RINCIAN SETORAN: LOGAM -->
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                {{ formatAngka($d->setoran_logam) }}
                            </td>

                            <!-- RINCIAN SETORAN: TRANSFER -->
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                {{ formatAngka($d->setoran_transfer) }}
                            </td>

                            <!-- RINCIAN SETORAN: GIRO -->
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                {{ formatAngka($d->setoran_giro) }}
                            </td>

                            <!-- JUMLAH -->
                            <td class="py-1.5 px-3 text-right font-bold font-mono-num text-[#284c9a] dark:text-blue-400">
                                {{ formatAngka($d->total) }}
                            </td>

                            <!-- BANK -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status == '1')
                                    <span class="font-bold text-xs text-slate-700 dark:text-slate-300">
                                        {{ !empty($d->nama_bank_alias) ? $d->nama_bank_alias : $d->nama_bank }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- STATUS -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status == '1')
                                    @php
                                        $tgl_terima = $d->tanggal_diterima ?? $d->tanggal_diterima_transfer ?? $d->tanggal_diterima_giro;
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60"
                                        title="Diterima pada {{ date('d-m-Y', strtotime($tgl_terima)) }}">
                                        <i data-lucide="check-circle-2" class="w-3 h-3 text-emerald-600"></i>
                                        <span>{{ date('d-m-Y', strtotime($tgl_terima)) }}</span>
                                    </span>
                                @elseif ($d->status == '2')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60">
                                        <i data-lucide="x-circle" class="w-3 h-3 text-rose-600"></i>
                                        <span>Ditolak</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">
                                        <i data-lucide="clock" class="w-3 h-3 text-amber-600"></i>
                                        <span>Pending</span>
                                    </span>
                                @endif
                            </td>

                            <!-- # (AKSI STICKY RIGHT) -->
                            <td class="py-1.5 px-3 text-center sticky right-0 z-10 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/40">
                                <div class="flex items-center justify-center gap-1">
                                    @can('setoranpusat.approve')
                                        @if ($d->status == '0' && empty($d->setoran_transfer) && empty($d->setoran_giro))
                                            <button type="button" kode_setoran="{{ Crypt::encrypt($d->kode_setoran) }}"
                                                class="btnApproveSetoranPusat w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300 transition flex items-center justify-center cursor-pointer" title="Verifikasi Penerimaan">
                                                <i data-lucide="external-link" class="w-3 h-3"></i>
                                            </button>
                                        @else
                                            @if (empty($d->setoran_transfer) && empty($d->setoran_giro))
                                                <form action="{{ route('setoranpusat.cancel', Crypt::encrypt($d->kode_setoran)) }}" method="POST" class="inline-flex m-0 p-0 deleteform">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btnBatalApprovePusat w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition flex items-center justify-center cursor-pointer" title="Batalkan Approval">
                                                        <i data-lucide="x" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    @endcan

                                    @can('setoranpusat.edit')
                                        @if ($d->status == '0' && empty($d->setoran_transfer) && empty($d->setoran_giro) && empty($d->no_pengajuan))
                                            <button type="button" kode_setoran="{{ Crypt::encrypt($d->kode_setoran) }}"
                                                class="btnEditSetoranPusat w-6 h-6 rounded-md bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-950/60 dark:text-amber-300 transition flex items-center justify-center cursor-pointer" title="Edit Setoran">
                                                <i data-lucide="pencil" class="w-3 h-3"></i>
                                            </button>
                                        @endif
                                    @endcan

                                    @can('setoranpusat.delete')
                                        @if ($d->status == '0' && empty($d->setoran_transfer) && empty($d->setoran_giro) && empty($d->no_pengajuan))
                                            <form action="{{ route('setoranpusat.delete', Crypt::encrypt($d->kode_setoran)) }}" method="POST" class="inline-flex m-0 p-0 deleteform">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btnDeleteSetoranPusat w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition flex items-center justify-center cursor-pointer" title="Hapus Setoran">
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
                            <td colspan="10" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Belum ada data setoran pusat</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                <!-- TFOOT REKAP TOTAL (PERSIS V1) -->
                @if ($setoran_pusat->count() > 0)
                    <tfoot class="bg-slate-50 dark:bg-slate-800/80 font-bold text-slate-800 dark:text-slate-100 border-t border-slate-200 dark:border-slate-700">
                        <tr>
                            <th colspan="2" class="py-2 px-3 text-center uppercase tracking-wider text-xs">TOTAL</th>
                            <th class="py-2 px-3 text-right font-mono-num text-xs">{{ formatAngka($setoran_pusat->sum('setoran_kertas')) }}</th>
                            <th class="py-2 px-3 text-right font-mono-num text-xs">{{ formatAngka($setoran_pusat->sum('setoran_logam')) }}</th>
                            <th class="py-2 px-3 text-right font-mono-num text-xs">{{ formatAngka($setoran_pusat->sum('setoran_transfer')) }}</th>
                            <th class="py-2 px-3 text-right font-mono-num text-xs">{{ formatAngka($setoran_pusat->sum('setoran_giro')) }}</th>
                            <th class="py-2 px-3 text-right font-mono-num text-xs text-[#284c9a] dark:text-blue-400">{{ formatAngka($setoran_pusat->sum('total')) }}</th>
                            <th colspan="3" class="py-2 px-3"></th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

<!-- ================= MODAL V2 UNIFIED (STATIC BACKDROP) ================= -->
<div id="modalSetoranPusat" class="fixed inset-0 z-50 hidden">
    <div id="modalSetoranPusatBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalSetoranPusatDialog" class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="landmark" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalSetoranPusatTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Setoran Pusat</h5>
                </div>
                <button type="button" id="btnCloseModalSetoranPusat"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="modalSetoranPusatContent" class="p-4 sm:p-5"></div>
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

        $('.select2Kodecabangsearch').select2({
            placeholder: 'Semua Cabang',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

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

        window.modalSetoranPusat = initModalHelper('#modalSetoranPusat', '#modalSetoranPusatDialog', '#modalSetoranPusatBackdrop', '#btnCloseModalSetoranPusat');

        const loadingSpinner = `
            <div class="py-12 flex flex-col items-center justify-center gap-2">
                <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs text-slate-500 font-semibold">Memuat formulir...</span>
            </div>
        `;

        // Action: Input Setoran (Create)
        $("#btnCreateSetoranPusat").on('click', function(e) {
            e.preventDefault();
            $("#modalSetoranPusatTitle").text("Input Setoran Pusat");
            $("#modalSetoranPusatContent").html(loadingSpinner);
            modalSetoranPusat.open();
            $("#modalSetoranPusatContent").load(`/setoranpusat/create`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Edit Setoran
        $(document).on('click', '.btnEditSetoranPusat', function(e) {
            e.preventDefault();
            const kode_setoran = $(this).attr("kode_setoran");
            $("#modalSetoranPusatTitle").text("Edit Setoran Pusat");
            $("#modalSetoranPusatContent").html(loadingSpinner);
            modalSetoranPusat.open();
            $("#modalSetoranPusatContent").load(`/setoranpusat/${kode_setoran}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Approve Setoran
        $(document).on('click', '.btnApproveSetoranPusat', function(e) {
            e.preventDefault();
            const kode_setoran = $(this).attr("kode_setoran");
            $("#modalSetoranPusatTitle").text("Approve Setoran Pusat");
            $("#modalSetoranPusatContent").html(loadingSpinner);
            modalSetoranPusat.open();
            $("#modalSetoranPusatContent").load(`/setoranpusat/${kode_setoran}/approve`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Batalkan Approval Confirm
        $(document).on('click', '.btnBatalApprovePusat', function(e) {
            e.preventDefault();
            const form = $(this).closest("form");
            Swal.fire({
                title: 'Batalkan Approval?',
                text: "Status setoran ini akan dikembalikan menjadi Pending!",
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

        // Action: Hapus Setoran Confirm
        $(document).on('click', '.btnDeleteSetoranPusat', function(e) {
            e.preventDefault();
            const form = $(this).closest("form");
            Swal.fire({
                title: 'Hapus Setoran Pusat?',
                text: "Data setoran yang dihapus tidak dapat dikembalikan!",
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

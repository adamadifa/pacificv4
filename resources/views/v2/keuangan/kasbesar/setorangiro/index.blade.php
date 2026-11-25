@extends('v2.layouts.app')
@section('titlepage', 'Setoran Giro')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Kas Besar</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Setoran Giro</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Setoran Giro</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Manajemen setoran kas dari hasil pembayaran giro pelanggan.</p>
        </div>
    </div>

    <!-- PETUNJUK PENGGUNAAN (V2 ALERT) -->
    <div class="rounded-xl border border-blue-200/80 dark:border-blue-900/60 bg-blue-50/60 dark:bg-blue-950/30 p-3 text-xs text-blue-800 dark:text-blue-300 flex items-start gap-2.5">
        <i data-lucide="info" class="w-4 h-4 text-[#284c9a] dark:text-blue-400 shrink-0 mt-0.5"></i>
        <div class="space-y-0.5">
            <span class="font-bold block">Petunjuk Penggunaan:</span>
            <p class="text-[11px] text-blue-700/90 dark:text-blue-300/80">
                Klik tombol <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300"><i data-lucide="external-link" class="w-3 h-3"></i> Setorkan</span> untuk melakukan setoran hasil giro pelanggan. Klik tombol <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300"><i data-lucide="x" class="w-3 h-3"></i> Batalkan</span> untuk membatalkan setoran yang sudah diinput.
            </p>
        </div>
    </div>

    <!-- NAVIGATION TABS (V2 PILLS) -->
    @include('v2.layouts.navigation_kasbesar')

    <!-- FILTER FORM (NO-LABEL COMPACT V2 2-BARIS) -->
    <form action="{{ route('setorangiro.index') }}" method="GET" class="w-full">
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2.5">
            <!-- BARIS 1: DARI & SAMPAI TANGGAL -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-center">
                <!-- Dari Tanggal -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="dari" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Sampai Tanggal -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="sampai" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
            </div>

            <!-- BARIS 2: CABANG, NO GIRO, PELANGGAN, SALESMAN, STATUS, TOMBOL CARI -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 @hasanyrole($roles_show_cabang) lg:grid-cols-6 @else lg:grid-cols-5 @endhasanyrole gap-2.5 items-center">
                <!-- Cabang (Jika Ada Hak Akses) -->
                @hasanyrole($roles_show_cabang)
                    <div class="relative select2-container-v2">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_cabang_search" id="kode_cabang_search_sg" class="select2Kodecabangsearch w-full">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ textUpperCase($c->nama_cabang) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endhasanyrole

                <!-- No. Giro -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="tag" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="no_giro" value="{{ Request('no_giro') }}" placeholder="No. Giro"
                        class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Nama Pelanggan -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="nama_pelanggan_search" value="{{ Request('nama_pelanggan_search') }}" placeholder="Pelanggan"
                        class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Salesman -->
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="users" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_salesman_search" id="kode_salesman_search_sg" class="select2Kodesalesmansearch w-full">
                        <option value="">Semua Salesman</option>
                    </select>
                </div>

                <!-- Status Giro -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                    </div>
                    <select name="status" id="status_sg"
                        class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                        <option value="">Status</option>
                        <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ Request('status') === '1' ? 'selected' : '' }}>Disetujui</option>
                        <option value="2" {{ Request('status') === '2' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                </div>

                <!-- Tombol Cari & Reset -->
                <div class="flex items-center gap-1.5">
                    <button type="submit"
                        class="flex-1 h-[36px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>Cari</span>
                    </button>
                    @if (request()->hasAny(['dari', 'sampai', 'kode_cabang_search', 'no_giro', 'nama_pelanggan_search', 'kode_salesman_search', 'status']))
                        <a href="{{ route('setorangiro.index') }}"
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
                <i data-lucide="file-check-2" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Data Setoran Giro</h5>
            </div>
            @php
                $totalData = method_exists($giro, 'total') ? $giro->total() : $giro->count();
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
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">NO. GIRO</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">TANGGAL</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">KODE PELANGGAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">NAMA PELANGGAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">JUMLAH</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">BANK PENGIRIM</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">BANK PENERIMA</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">JATUH TEMPO</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">STATUS</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">DISETORKAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-16 sticky right-0 z-10 bg-[#1e3a78]">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    @forelse ($giro as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <!-- NO. GIRO -->
                            <td class="py-1.5 px-3 font-bold font-mono-num text-[#284c9a] dark:text-blue-400">
                                {{ $d->no_giro ?: $d->kode_giro }}
                            </td>

                            <!-- TANGGAL -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">
                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                            </td>

                            <!-- KODE PELANGGAN (KOLOM TERPISAH) -->
                            <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-600 dark:text-slate-300">
                                {{ $d->kode_pelanggan }}
                            </td>

                            <!-- NAMA PELANGGAN (KOLOM TERPISAH) -->
                            <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-white">
                                {{ textUpperCase($d->nama_pelanggan) }}
                            </td>

                            <!-- JUMLAH -->
                            <td class="py-1.5 px-3 text-right font-bold font-mono-num text-[#284c9a] dark:text-blue-400">
                                {{ formatAngka($d->total) }}
                            </td>

                            <!-- BANK PENGIRIM -->
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">
                                {{ textUpperCase($d->bank_pengirim) }}
                            </td>

                            <!-- BANK PENERIMA -->
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">
                                {{ !empty($d->nama_bank_alias) ? $d->nama_bank_alias : (!empty($d->nama_bank) ? $d->nama_bank : '-') }}
                            </td>

                            <!-- JATUH TEMPO -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">
                                {{ date('d-m-Y', strtotime($d->jatuh_tempo)) }}
                            </td>

                            <!-- STATUS (VERIFIKASI GIRO) -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status == '1')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60"
                                        title="{{ $d->no_bukti }}">
                                        <i data-lucide="check-circle-2" class="w-3 h-3 text-emerald-600"></i>
                                        <span>{{ date('d-m-Y', strtotime($d->tanggal_diterima)) }}</span>
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

                            <!-- DISETORKAN (STATUS SETORAN) -->
                            <td class="py-1.5 px-3 text-center font-mono-num">
                                @if (!empty($d->tanggal_disetorkan))
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60">
                                        <i data-lucide="check" class="w-3 h-3 text-blue-600"></i>
                                        <span>{{ date('d-m-Y', strtotime($d->tanggal_disetorkan)) }}</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                        <i data-lucide="clock" class="w-3 h-3 text-slate-400"></i>
                                        <span>Belum Setor</span>
                                    </span>
                                @endif
                            </td>

                            <!-- # (AKSI STICKY RIGHT) -->
                            <td class="py-1.5 px-3 text-center sticky right-0 z-10 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/40">
                                <div class="flex items-center justify-center gap-1">
                                    @can('setorangiro.create')
                                        @if (empty($d->tanggal_disetorkan))
                                            <button type="button" kode_giro="{{ Crypt::encrypt($d->kode_giro) }}"
                                                class="btnCreateSetorGiro w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300 transition flex items-center justify-center cursor-pointer" title="Setorkan Hasil Giro">
                                                <i data-lucide="external-link" class="w-3 h-3"></i>
                                            </button>
                                        @else
                                            @can('setorangiro.delete')
                                                <form action="{{ route('setorangiro.delete', Crypt::encrypt($d->kode_setoran)) }}" method="POST" class="inline-flex m-0 p-0 deleteform">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btnBatalSetorGiro w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition flex items-center justify-center cursor-pointer" title="Batalkan Setoran">
                                                        <i data-lucide="x" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Belum ada data setoran giro</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION BAR -->
        @if ($giro->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $giro->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- ================= MODAL V2 (STATIC BACKDROP) ================= -->
<div id="modalSetoranGiro" class="fixed inset-0 z-50 hidden">
    <div id="modalSetoranGiroBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalSetoranGiroDialog" class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="file-check-2" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Setoran Giro Pelanggan</h5>
                </div>
                <button type="button" id="btnCloseModalSetoranGiro"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="modalSetoranGiroContent" class="p-4 sm:p-5"></div>
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

        $('.select2Kodesalesmansearch').select2({
            placeholder: 'Semua Salesman',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        function getsalesmanbyCabang() {
            var kode_cabang = $("#kode_cabang_search_sg").val();
            var kode_salesman = "{{ Request('kode_salesman_search') }}";
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    $("#kode_salesman_search_sg").html(respond);
                }
            });
        }

        $("#kode_cabang_search_sg").on('change', function() {
            getsalesmanbyCabang();
        });

        getsalesmanbyCabang();

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

        window.modalSetoranGiro = initModalHelper('#modalSetoranGiro', '#modalSetoranGiroDialog', '#modalSetoranGiroBackdrop', '#btnCloseModalSetoranGiro');

        const loadingSpinner = `
            <div class="py-12 flex flex-col items-center justify-center gap-2">
                <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs text-slate-500 font-semibold">Memuat form setoran...</span>
            </div>
        `;

        // Action: Setorkan Giro (Create)
        $(document).on('click', '.btnCreateSetorGiro', function(e) {
            e.preventDefault();
            const kode_giro = $(this).attr("kode_giro");
            $("#modalSetoranGiroContent").html(loadingSpinner);
            modalSetoranGiro.open();
            $("#modalSetoranGiroContent").load(`/setorangiro/${kode_giro}/create`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Batal Setoran Confirm
        $(document).on('click', '.btnBatalSetorGiro', function(e) {
            e.preventDefault();
            const form = $(this).closest("form");
            Swal.fire({
                title: 'Batalkan Setoran Giro?',
                text: "Status setoran ini akan dikembalikan menjadi Belum Disetor!",
                icon: 'question',
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
    });
</script>
@endpush

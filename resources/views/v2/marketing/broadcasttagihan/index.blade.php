@extends('layouts.app')
@section('titlepage', 'Broadcast Tagihan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Broadcast Tagihan</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Broadcast Tagihan Piutang</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kirim pengingat tagihan piutang jatuh tempo kepada pelanggan via pesan WhatsApp secara terpadu.</p>
        </div>
    </div>

    <!-- FILTER SECTION (NO-LABEL, H-[34PX], LUCIDE PREFIX, ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs">
        <form action="{{ route('broadcasttagihan.index') }}" method="GET" id="formSearch" class="space-y-2">
            
            <!-- Baris 1: Bulan & Tahun (Wajib) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
                
                <!-- Bulan (Col 6) -->
                <div class="lg:col-span-6 relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="bulan" id="bulan" required
                        class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                        <option value="">Pilih Bulan (Wajib)</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ Request('bulan') == $i ? 'selected' : '' }}>
                                {{ $list_bulan[$i] }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Tahun (Col 6) -->
                <div class="lg:col-span-6 relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="tahun" id="tahun" required
                        class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                        <option value="">Pilih Tahun (Wajib)</option>
                        @for ($y = date('Y') + 1; $y >= $start_year; $y--)
                            <option value="{{ $y }}" {{ Request('tahun') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

            </div>

            <!-- Baris 2: Cabang, Salesman, Pelanggan, Status JT, Tombol Cari -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
                
                @hasanyrole($roles_show_cabang)
                    <!-- Cabang (Col 3) -->
                    <div class="lg:col-span-3 relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_cabang" id="kode_cabang" required
                            class="select2Cabang w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                            <option value="">Pilih Cabang (Wajib)</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ $c->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endhasanyrole

                <!-- Salesman (Col 3 / 4) -->
                <div class="{{ auth()->user()->hasRole($roles_show_cabang) ? 'lg:col-span-3' : 'lg:col-span-4' }} relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="briefcase" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_salesman" id="kode_salesman"
                        class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                        <option value="">Semua Salesman</option>
                    </select>
                </div>

                <!-- Nama Pelanggan (Col 3) -->
                <div class="lg:col-span-3 relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ Request('nama_pelanggan') }}"
                        class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition"
                        placeholder="Nama Pelanggan">
                </div>

                <!-- Status Jatuh Tempo (Col 2) -->
                <div class="{{ auth()->user()->hasRole($roles_show_cabang) ? 'lg:col-span-2' : 'lg:col-span-3' }} relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="status_jatuh_tempo" id="status_jatuh_tempo"
                        class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                        <option value="">Semua Status JT</option>
                        <option value="sudah" {{ Request('status_jatuh_tempo') == 'sudah' ? 'selected' : '' }}>Sudah JT</option>
                        <option value="belum" {{ Request('status_jatuh_tempo') == 'belum' ? 'selected' : '' }}>Belum JT</option>
                    </select>
                </div>

                <!-- Tombol Cari (Col 1 / 2) -->
                <div class="{{ auth()->user()->hasRole($roles_show_cabang) ? 'lg:col-span-1' : 'lg:col-span-2' }} flex justify-end">
                    <button type="submit"
                        class="w-full h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>
                </div>

            </div>

        </form>
    </div>

    @if ($is_filtered)
        
        <!-- STATISTIC & METRIC STRIP (PACIFIC NAVY STANDAR V2) -->
        <div class="relative overflow-hidden rounded-xl bg-[#284c9a] text-white shadow-xs hover:shadow-md transition">
            <!-- Background Arc Pattern -->
            <div class="absolute inset-0 pointer-events-none opacity-10">
                <svg class="w-full h-full" viewBox="0 0 800 200" fill="none" preserveAspectRatio="none">
                    <path d="M-100 250 C 200 50, 400 300, 900 100" stroke="white" stroke-width="60" stroke-linecap="round"/>
                </svg>
            </div>

            <div class="relative grid grid-cols-2 lg:grid-cols-4 divide-y lg:divide-y-0 divide-x-0 lg:divide-x divide-white/15">
                
                <!-- 1. Total Faktur -->
                <div class="p-3.5 sm:p-4 flex items-center justify-between gap-3">
                    <div class="space-y-0.5">
                        <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-white/75 block">Total Faktur</span>
                        <div class="text-lg sm:text-xl font-black font-mono-num text-white leading-tight">
                            {{ $data->count() }} <span class="text-xs font-semibold text-white/75 font-sans">Faktur</span>
                        </div>
                        <span class="text-[10px] text-white/55 block">Tagihan aktif terfilter</span>
                    </div>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/85 shrink-0">
                        <i data-lucide="file-text" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </div>
                </div>

                <!-- 2. Total Sisa Piutang -->
                <div class="p-3.5 sm:p-4 flex items-center justify-between gap-3">
                    <div class="space-y-0.5">
                        <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-white/75 block">Total Sisa Piutang</span>
                        <div class="text-lg sm:text-xl font-black font-mono-num text-white leading-tight">
                            Rp {{ number_format($data->sum('sisa_piutang'), 0, ',', '.') }}
                        </div>
                        <span class="text-[10px] text-white/55 block">Total akumulasi sisa tagihan</span>
                    </div>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/85 shrink-0">
                        <i data-lucide="coins" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </div>
                </div>

                <!-- 3. Lewat Jatuh Tempo -->
                @php
                    $countLewatJT = $data->where('jatuh_tempo', '<', $today)->count();
                @endphp
                <div class="p-3.5 sm:p-4 flex items-center justify-between gap-3">
                    <div class="space-y-0.5">
                        <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-white/75 block">Lewat Jatuh Tempo</span>
                        <div class="text-lg sm:text-xl font-black font-mono-num text-rose-300 leading-tight">
                            {{ $countLewatJT }} <span class="text-xs font-semibold text-white/75 font-sans">Faktur</span>
                        </div>
                        <span class="text-[10px] text-white/55 block">Wajib segera ditagih</span>
                    </div>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-rose-500/20 text-rose-300 flex items-center justify-center shrink-0">
                        <i data-lucide="alert-circle" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </div>
                </div>

                <!-- 4. Belum Jatuh Tempo -->
                @php
                    $countBelumJT = $data->where('jatuh_tempo', '>=', $today)->count();
                @endphp
                <div class="p-3.5 sm:p-4 flex items-center justify-between gap-3">
                    <div class="space-y-0.5">
                        <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-white/75 block">Belum Jatuh Tempo</span>
                        <div class="text-lg sm:text-xl font-black font-mono-num text-emerald-300 leading-tight">
                            {{ $countBelumJT }} <span class="text-xs font-semibold text-white/75 font-sans">Faktur</span>
                        </div>
                        <span class="text-[10px] text-white/55 block">Tagihan berjalan</span>
                    </div>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center shrink-0">
                        <i data-lucide="check-circle-2" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </div>
                </div>

            </div>
        </div>

        <!-- TABEL DATA TAGIHAN V2 (KOMPAK ~36PX, 2-BARIS THEAD, PACIFIC NAVY) -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto min-h-[300px]">
                <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                    <thead>
                        <!-- Row 1: Header Strip Pacific Navy (Colspan 9) -->
                        <tr class="bg-[#284c9a] text-white">
                            <th colspan="9" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="file-spreadsheet" class="w-4 h-4 text-blue-200"></i>
                                        <span class="font-bold text-xs uppercase tracking-wider text-white">Daftar Tagihan Piutang Pelanggan</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-white/10 text-white/90 uppercase tracking-wider font-mono-num">
                                        TOTAL: {{ $data->count() }} FAKTUR
                                    </span>
                                </div>
                            </th>
                        </tr>

                        <!-- Row 2: Header Kolom Deep Navy -->
                        <tr class="bg-[#1e3a78] text-white border-t border-white/10">
                            <th class="py-2 px-3 text-center text-[11px] font-bold uppercase tracking-wider text-white/90 w-12">NO</th>
                            <th class="py-2 px-3 text-left text-[11px] font-bold uppercase tracking-wider text-white/90">NO. FAKTUR & TGL</th>
                            <th class="py-2 px-3 text-left text-[11px] font-bold uppercase tracking-wider text-white/90">KODE & PELANGGAN</th>
                            <th class="py-2 px-3 text-center text-[11px] font-bold uppercase tracking-wider text-white/90">NO. WHATSAPP</th>
                            <th class="py-2 px-3 text-left text-[11px] font-bold uppercase tracking-wider text-white/90">CABANG & SALESMAN</th>
                            <th class="py-2 px-3 text-right text-[11px] font-bold uppercase tracking-wider text-white/90">SISA PIUTANG</th>
                            <th class="py-2 px-3 text-center text-[11px] font-bold uppercase tracking-wider text-white/90">JATUH TEMPO</th>
                            <th class="py-2 px-3 text-center text-[11px] font-bold uppercase tracking-wider text-white/90">STATUS JT</th>
                            <th class="py-2 px-3 text-center text-[11px] font-bold uppercase tracking-wider text-white/90 w-28">AKSI</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($data as $d)
                            @php
                                $is_past_due = $d->jatuh_tempo < $today;
                            @endphp
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                <!-- No -->
                                <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">{{ $loop->iteration }}</td>

                                <!-- No Faktur & Tanggal -->
                                <td class="py-1.5 px-3 text-left">
                                    <div class="font-bold text-[#284c9a] dark:text-blue-400 font-mono-num">{{ $d->no_faktur }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono-num">{{ date('d-m-Y', strtotime($d->tanggal)) }}</div>
                                </td>

                                <!-- Pelanggan -->
                                <td class="py-1.5 px-3 text-left">
                                    <div class="font-bold text-slate-800 dark:text-slate-100">{{ $d->nama_pelanggan }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono-num">{{ $d->kode_pelanggan }}</div>
                                </td>

                                <!-- No WhatsApp -->
                                <td class="py-1.5 px-3 text-center">
                                    @if (!empty($d->no_hp_pelanggan))
                                        <span class="inline-flex items-center gap-1 font-mono-num text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                                            <i data-lucide="phone" class="w-3 h-3"></i>
                                            <span>{{ $d->no_hp_pelanggan }}</span>
                                        </span>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600 font-mono-num">-</span>
                                    @endif
                                </td>

                                <!-- Cabang & Salesman -->
                                <td class="py-1.5 px-3 text-left">
                                    <div class="font-bold text-slate-700 dark:text-slate-200">{{ $d->nama_cabang }}</div>
                                    <div class="text-[10px] text-slate-400 flex items-center gap-1">
                                        <i data-lucide="user" class="w-3 h-3 text-slate-400"></i>
                                        <span>{{ $d->nama_salesman }}</span>
                                    </div>
                                </td>

                                <!-- Sisa Piutang -->
                                <td class="py-1.5 px-3 text-right font-mono-num font-black text-rose-600 dark:text-rose-400">
                                    Rp {{ number_format($d->sisa_piutang, 0, ',', '.') }}
                                </td>

                                <!-- Jatuh Tempo -->
                                <td class="py-1.5 px-3 text-center font-mono-num font-semibold text-slate-700 dark:text-slate-300">
                                    {{ date('d-m-Y', strtotime($d->jatuh_tempo)) }}
                                </td>

                                <!-- Status JT -->
                                <td class="py-1.5 px-3 text-center">
                                    @if ($is_past_due)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-900/60 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Lewat JT</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/60 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Belum JT</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi Kirim WA -->
                                <td class="py-1.5 px-3 text-center">
                                    @if (empty($d->no_hp_pelanggan))
                                        <button type="button" disabled title="Nomor HP Pelanggan Kosong"
                                            class="h-[28px] px-2.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 text-[11px] font-bold inline-flex items-center gap-1 cursor-not-allowed opacity-60">
                                            <i data-lucide="phone-off" class="w-3 h-3"></i>
                                            <span>No HP</span>
                                        </button>
                                    @else
                                        <button type="button" 
                                            class="btn-send-wa h-[28px] px-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-[11px] font-bold inline-flex items-center justify-center gap-1.5 transition shadow-2xs cursor-pointer"
                                            data-faktur="{{ $d->no_faktur }}"
                                            data-pelanggan="{{ $d->nama_pelanggan }}"
                                            data-phone="{{ $d->no_hp_pelanggan }}"
                                            data-nominal="Rp {{ number_format($d->sisa_piutang, 0, ',', '.') }}"
                                            title="Kirim Tagihan via WhatsApp">
                                            <i data-lucide="send" class="w-3 h-3"></i>
                                            <span>Kirim WA</span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-lucide="database" class="w-10 h-10 text-slate-300 dark:text-slate-600 mb-2 stroke-1"></i>
                                        <h4 class="text-xs font-bold text-slate-600 dark:text-slate-300">Tidak Ada Tagihan Piutang</h4>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Semua faktur pada filter ini telah lunas atau tidak ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @else
        
        <!-- INFORMATIONAL EMPTY STATE (BELUM FILTER) -->
        <div class="rounded-xl border border-dashed border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-8 sm:p-12 text-center">
            <div class="max-w-md mx-auto space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 flex items-center justify-center mx-auto ring-8 ring-blue-50/50 dark:ring-blue-950/20">
                    <i data-lucide="filter" class="w-6 h-6"></i>
                </div>
                <div class="space-y-1">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100">Silakan Tentukan Filter Tagihan</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Pilih <b>Bulan</b>, <b>Tahun</b>@hasanyrole($roles_show_cabang), dan <b>Cabang</b>@endhasanyrole pada form filter di atas lalu klik tombol <b>Cari</b> untuk memuat daftar tagihan piutang pelanggan.
                    </p>
                </div>
            </div>
        </div>

    @endif

</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Dynamic Salesman Loader by Cabang
        function getsalesmanbyCabang() {
            var kode_cabang = $("#kode_cabang").val();
            var kode_salesman = "{{ Request('kode_salesman') }}";

            if (!kode_cabang) {
                $("#kode_salesman").html('<option value="">Semua Salesman</option>');
                return;
            }

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
                    $("#kode_salesman").html('<option value="">Semua Salesman</option>' + respond);
                }
            });
        }

        getsalesmanbyCabang();
        $("#kode_cabang").on('change', function() {
            getsalesmanbyCabang();
        });

        // Handler Kirim Pesan WhatsApp
        $(document).on('click', '.btn-send-wa', function(e) {
            e.preventDefault();
            var $btn = $(this);
            var noFaktur = $btn.data('faktur');
            var namaPelanggan = $btn.data('pelanggan');
            var noHp = $btn.data('phone');
            var nominal = $btn.data('nominal');

            const confirmHtml = `
                <div class="text-left text-xs space-y-2 mt-2 p-3 rounded-lg bg-slate-50 border border-slate-200">
                    <div class="flex justify-between"><span class="text-slate-500">No. Faktur:</span> <span class="font-bold text-[#284c9a]">${noFaktur}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Pelanggan:</span> <span class="font-bold text-slate-800">${namaPelanggan}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">No. WhatsApp:</span> <span class="font-bold text-emerald-600">${noHp}</span></div>
                    <div class="flex justify-between border-t border-slate-200 pt-1.5"><span class="text-slate-500">Sisa Tagihan:</span> <span class="font-black text-rose-600">${nominal}</span></div>
                </div>
            `;

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Kirim Tagihan via WhatsApp?',
                    html: `Kirimkan rincian pengingat piutang langsung ke nomor pelanggan? ${confirmHtml}`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Kirim Sekarang!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none').html(`
                            <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Mengirim...</span>
                        `);

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('broadcasttagihan.send') }}",
                            data: {
                                _token: "{{ csrf_token() }}",
                                no_faktur: noFaktur
                            },
                            success: function(res) {
                                $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none').html(`
                                    <i data-lucide="send" class="w-3 h-3"></i>
                                    <span>Kirim WA</span>
                                `);
                                if (typeof lucide !== 'undefined') {
                                    lucide.createIcons();
                                }

                                if (res.success) {
                                    Swal.fire({
                                        title: 'Terkirim ke Antrian!',
                                        text: res.message,
                                        icon: 'success',
                                        confirmButtonColor: '#284c9a'
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal Mengirim',
                                        text: res.message,
                                        icon: 'error',
                                        confirmButtonColor: '#ef4444'
                                    });
                                }
                            },
                            error: function(xhr) {
                                $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none').html(`
                                    <i data-lucide="send" class="w-3 h-3"></i>
                                    <span>Kirim WA</span>
                                `);
                                if (typeof lucide !== 'undefined') {
                                    lucide.createIcons();
                                }

                                var errMsg = 'Terjadi kesalahan sistem saat memproses broadcast.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errMsg = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    title: 'Gagal Mengirim',
                                    text: errMsg,
                                    icon: 'error',
                                    confirmButtonColor: '#ef4444'
                                });
                            }
                        });
                    }
                });
            }
        });
    });
</script>
@endpush

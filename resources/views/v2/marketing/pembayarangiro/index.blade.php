@extends('v2.layouts.app')
@section('titlepage', 'Pembayaran Giro')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Giro</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION BUTTON -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Pembayaran Giro</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola dan verifikasi penerimaan pembayaran giro dari pelanggan.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('pembayarangiro.create')
                <button type="button" id="btnCreateGiro"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Input Giro</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- NAVIGATION TABS (V2 PILLS) -->
    @include('v2.layouts.navigation_girotransfer')

    <!-- FILTER FORM (NO-LABEL COMPACT V2 - 2 BARIS PRESISI) -->
    <form action="{{ route('pembayarangiro.index') }}" method="GET" class="w-full">
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-12 gap-2.5 items-center">
                <!-- BARIS 1: DARI, SAMPAI, CABANG, SALESMAN -->
                <!-- Dari Tanggal -->
                <div class="relative col-span-1 sm:col-span-1 md:col-span-2 {{ auth()->user()->hasRole($roles_show_cabang) ? 'lg:col-span-3' : 'lg:col-span-4' }}">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="dari" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Sampai Tanggal -->
                <div class="relative col-span-1 sm:col-span-1 md:col-span-2 {{ auth()->user()->hasRole($roles_show_cabang) ? 'lg:col-span-3' : 'lg:col-span-4' }}">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="sampai" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Cabang (Jika Ada Hak Akses) -->
                @hasanyrole($roles_show_cabang)
                    <div class="relative select2-container-v2 col-span-1 sm:col-span-1 md:col-span-2 lg:col-span-3">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_cabang_search" id="kode_cabang_search_giro" class="select2Kodecabangsearch w-full">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ textUpperCase($c->nama_cabang) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endhasanyrole

                <!-- Salesman -->
                <div class="relative select2-container-v2 col-span-1 sm:col-span-1 md:col-span-2 {{ auth()->user()->hasRole($roles_show_cabang) ? 'lg:col-span-3' : 'lg:col-span-4' }}">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="users" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_salesman_search" id="kode_salesman_search_giro" class="select2Kodesalesmansearch w-full">
                        <option value="">Semua Salesman</option>
                    </select>
                </div>

                <!-- BARIS 2: NO GIRO, PELANGGAN, STATUS, CARI & RESET -->
                <!-- No Giro -->
                <div class="relative col-span-1 sm:col-span-1 md:col-span-2 lg:col-span-3">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="tag" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="no_giro" value="{{ Request('no_giro') }}" placeholder="No. Giro..."
                        class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Nama Pelanggan -->
                <div class="relative col-span-1 sm:col-span-1 md:col-span-2 lg:col-span-4">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="nama_pelanggan_search" value="{{ Request('nama_pelanggan_search') }}" placeholder="Cari Pelanggan..."
                        class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Status -->
                <div class="relative col-span-1 sm:col-span-1 md:col-span-2 lg:col-span-3">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                    </div>
                    <select name="status" id="status_filter_giro"
                        class="w-full h-[36px] pl-9 pr-7 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ Request('status') === '1' ? 'selected' : '' }}>Disetujui</option>
                        <option value="2" {{ Request('status') === '2' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>

                <!-- Tombol Cari & Reset -->
                <div class="col-span-1 sm:col-span-1 md:col-span-2 lg:col-span-2 flex items-center gap-1.5">
                    <button type="submit"
                        class="flex-1 h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>Cari</span>
                    </button>
                    @if (request()->hasAny(['dari', 'sampai', 'kode_cabang_search', 'no_giro', 'nama_pelanggan_search', 'kode_salesman_search', 'status']))
                        <a href="{{ route('pembayarangiro.index') }}"
                            class="h-[36px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-700 text-xs font-bold transition flex items-center justify-center cursor-pointer shrink-0" title="Reset Filter">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </form>
            </div>
        </div>
    </form>

    <!-- DATA TABLE CARD (V2 PACIFIC NAVY 2-TIER ~36PX) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <!-- BARIS 1: STRIP JUDUL TABEL (bg-[#284c9a]) -->
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="file-check-2" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Daftar Pembayaran Giro</h5>
            </div>
            @php
                $totalData = method_exists($giro, 'total') ? $giro->total() : $giro->count();
            @endphp
            <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-white/10 text-white border border-white/20 font-mono-num">
                Total: {{ $totalData }} Data
            </span>
        </div>

        <!-- TABLE CONTENT -->
        <div class="overflow-x-auto min-h-[280px]">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <!-- BARIS 2: HEADER KOLOM (bg-[#1e3a78]) -->
                <thead class="bg-[#1e3a78] text-white/90">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">No. Giro</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Tanggal</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Pelanggan</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">Jumlah</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Pengirim</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Penerima</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">Status</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-24 sticky right-0 z-10 bg-[#1e3a78]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    @forelse ($giro as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <td class="py-1.5 px-3 font-bold font-mono-num text-[#284c9a] dark:text-blue-400">
                                {{ $d->no_giro }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-700 dark:text-slate-300">
                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-900 dark:text-white">
                                {{ textUpperCase($d->nama_pelanggan) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-bold font-mono-num text-slate-900 dark:text-white">
                                {{ formatAngka($d->total) }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300 font-medium">
                                {{ textUpperCase($d->bank_pengirim) }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300 font-medium">
                                {{ !empty($d->nama_bank_alias) ? $d->nama_bank_alias : $d->nama_bank }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-700 dark:text-slate-300">
                                {{ date('d-m-Y', strtotime($d->jatuh_tempo)) }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status == '1')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 font-mono-num" title="{{ $d->no_bukti }}">
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
                            <!-- AKSI STICKY RIGHT -->
                            <td class="py-1.5 px-3 text-center sticky right-0 z-10 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/40">
                                <div class="flex items-center justify-center gap-1">
                                    @can('pembayarangiro.approve')
                                        @if (auth()->user()->kode_cabang != 'PST')
                                            @if ($d->status === '0')
                                                <button type="button" kode_giro="{{ Crypt::encrypt($d->kode_giro) }}"
                                                    class="btnApproveGiro w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300 transition flex items-center justify-center cursor-pointer" title="Approve">
                                                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                                                </button>
                                            @endif
                                        @else
                                            <button type="button" kode_giro="{{ Crypt::encrypt($d->kode_giro) }}"
                                                class="btnApproveGiro w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300 transition flex items-center justify-center cursor-pointer" title="Approve">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i>
                                            </button>
                                        @endif
                                    @endcan

                                    @can('pembayarangiro.show')
                                        <button type="button" kode_giro="{{ Crypt::encrypt($d->kode_giro) }}"
                                            class="btnShowGiro w-6 h-6 rounded-md bg-blue-50 text-[#284c9a] hover:bg-blue-100 dark:bg-blue-950/60 dark:text-blue-300 transition flex items-center justify-center cursor-pointer" title="Detail">
                                            <i data-lucide="file-text" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('pembayarangiro.delete')
                                        @if ($d->status === '0')
                                            <form action="/pembayarangiro/{{ Crypt::encrypt($d->kode_giro) }}/deletegiro" method="POST" class="inline-flex m-0 p-0 deleteform">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btnDeleteGiro w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition flex items-center justify-center cursor-pointer" title="Hapus">
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
                                    <span class="text-xs font-semibold">Belum ada data pembayaran giro</span>
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

<!-- ================= MODALS V2 (STATIC BACKDROP) ================= -->

<!-- 1. MODAL DETAIL / SHOW -->
<div id="modalShowGiro" class="fixed inset-0 z-50 hidden">
    <div id="modalShowGiroBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalShowGiroDialog" class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="receipt" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Detail Pembayaran Giro</h5>
                </div>
                <button type="button" id="btnCloseModalShowGiro"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="modalShowGiroContent" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>

<!-- 2. MODAL APPROVE -->
<div id="modalApproveGiro" class="fixed inset-0 z-50 hidden">
    <div id="modalApproveGiroBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalApproveGiroDialog" class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="check-square" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Verifikasi Pembayaran Giro</h5>
                </div>
                <button type="button" id="btnCloseModalApproveGiro"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="modalApproveGiroContent" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>

<!-- 3. MODAL CREATE GROUP -->
<div id="modalCreateGroupGiro" class="fixed inset-0 z-50 hidden">
    <div id="modalCreateGroupGiroBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalCreateGroupGiroDialog" class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Input Pembayaran Giro Pelanggan</h5>
                </div>
                <button type="button" id="btnCloseModalCreateGroupGiro"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="modalCreateGroupGiroContent" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>

<!-- 4. MODAL EDIT GIRO -->
<div id="modalEditGiro" class="fixed inset-0 z-50 hidden">
    <div id="modalEditGiroBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalEditGiroDialog" class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="pencil" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Edit Pembayaran Giro</h5>
                </div>
                <button type="button" id="btnCloseModalEditGiro"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="modalEditGiroContent" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>

<!-- 5. MODAL PILIH PELANGGAN (LOOKUP) -->
<div id="modalPelanggan" class="fixed inset-0 z-50 hidden">
    <div id="modalPelangganBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalPelangganDialog" class="relative w-full max-w-3xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Pilih Data Pelanggan</h5>
                </div>
                <button type="button" id="btnCloseModalPelanggan"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="p-4 sm:p-5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs whitespace-nowrap" id="tabelpelanggan">
                        <thead class="bg-[#1e3a78] text-white">
                            <tr>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-10">No</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Kode</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Nama Pelanggan</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Salesman</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Wilayah</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Status</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-12">#</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        // Flatpickr
        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        // Select2 filter styling
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
            var kode_cabang = $("#kode_cabang_search_giro").val();
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
                    $("#kode_salesman_search_giro").html(respond);
                }
            });
        }

        $("#kode_cabang_search_giro").on('change', function() {
            getsalesmanbyCabang();
        });

        getsalesmanbyCabang();

        // Modal Helpers (Rule 9: Static Backdrop & Explicit Close)
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

        window.modalShowGiro = initModalHelper('#modalShowGiro', '#modalShowGiroDialog', '#modalShowGiroBackdrop', '#btnCloseModalShowGiro');
        window.modalApproveGiro = initModalHelper('#modalApproveGiro', '#modalApproveGiroDialog', '#modalApproveGiroBackdrop', '#btnCloseModalApproveGiro');
        window.modalCreateGroupGiro = initModalHelper('#modalCreateGroupGiro', '#modalCreateGroupGiroDialog', '#modalCreateGroupGiroBackdrop', '#btnCloseModalCreateGroupGiro');
        window.modalEditGiro = initModalHelper('#modalEditGiro', '#modalEditGiroDialog', '#modalEditGiroBackdrop', '#btnCloseModalEditGiro');
        window.modalPelangganHelper = initModalHelper('#modalPelanggan', '#modalPelangganDialog', '#modalPelangganBackdrop', '#btnCloseModalPelanggan');

        const loadingSpinner = `
            <div class="py-12 flex flex-col items-center justify-center gap-2">
                <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs text-slate-500 font-semibold">Memuat data...</span>
            </div>
        `;

        // Action: Show Detail
        $(document).on('click', '.btnShowGiro', function(e) {
            e.preventDefault();
            const kode_giro = $(this).attr("kode_giro");
            $("#modalShowGiroContent").html(loadingSpinner);
            modalShowGiro.open();
            $("#modalShowGiroContent").load(`/pembayarangiro/${kode_giro}/show`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Approve
        $(document).on('click', '.btnApproveGiro', function(e) {
            e.preventDefault();
            const kode_giro = $(this).attr("kode_giro");
            $("#modalApproveGiroContent").html(loadingSpinner);
            modalApproveGiro.open();
            $("#modalApproveGiroContent").load(`/pembayarangiro/${kode_giro}/approve`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Create Group
        $(document).on('click', '#btnCreateGiro', function(e) {
            e.preventDefault();
            $("#modalCreateGroupGiroContent").html(loadingSpinner);
            modalCreateGroupGiro.open();
            $("#modalCreateGroupGiroContent").load(`/pembayarangiro/creategroup`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Edit
        $(document).on('click', '.btnEditGiro', function(e) {
            e.preventDefault();
            const kode_giro = $(this).attr("kode_giro");
            $("#modalEditGiroContent").html(loadingSpinner);
            modalEditGiro.open();
            $("#modalEditGiroContent").load(`/pembayarangiro/${kode_giro}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Delete Confirm
        $(document).on('click', '.btnDeleteGiro', function(e) {
            e.preventDefault();
            const form = $(this).closest("form");
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data pembayaran giro ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#284c9a',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Lookup Pelanggan DataTable
        let tablePelangganInitialized = false;
        function initPelangganDataTable() {
            if (tablePelangganInitialized) return;
            $('#tabelpelanggan').DataTable({
                processing: true,
                serverSide: true,
                order: [[2, 'asc']],
                ajax: "{{ route('pelanggan.getpelangganjson') }}",
                bAutoWidth: false,
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%' },
                    { data: 'kode_pelanggan', name: 'kode_pelanggan', orderable: true, searchable: true, width: '15%' },
                    { data: 'nama_pelanggan', name: 'nama_pelanggan', orderable: true, searchable: true, width: '30%' },
                    { data: 'nama_salesman', name: 'nama_salesman', orderable: true, searchable: false, width: '20%' },
                    { data: 'nama_wilayah', name: 'nama_wilayah', orderable: true, searchable: false, width: '20%' },
                    { data: 'status_pelanggan', name: 'status_pelanggan', orderable: true, searchable: false, width: '10%' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, width: '5%' }
                ],
                drawCallback: function() {
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
            tablePelangganInitialized = true;
        }

        $(document).on('click', '#btnCariPelangganGiro, #kode_pelanggan_giro_group', function() {
            initPelangganDataTable();
        });

        // Callback Select Pelanggan for Giro
        $(document).on('click', '.pilihpelanggan', function(e) {
            e.preventDefault();
            const encrypted_kode = $(this).attr('kode_pelanggan');

            $.ajax({
                url: `/pelanggan/${encrypted_kode}/getPelanggan`,
                type: "GET",
                cache: false,
                success: function(response) {
                    if (response && response.data) {
                        const status_aktif = response.data.status_aktif_pelanggan;
                        if (status_aktif === '0') {
                            Swal.fire({
                                title: "Oops!",
                                text: "Pelanggan Tidak Dapat Bertransaksi, Silahkan Hubungi Admin Untuk Mengaktifkan Pelanggan !",
                                icon: "warning",
                                showConfirmButton: true,
                            });
                            return;
                        }

                        // Set unencrypted plain code and customer info
                        $('#kode_pelanggan_giro_group').val(response.data.kode_pelanggan);
                        $('#label_nama_pelanggan_giro').text(response.data.nama_pelanggan);
                        $('#label_alamat_pelanggan_giro').text(response.data.alamat_pelanggan);
                        $('#label_no_hp_pelanggan_giro').text(response.data.no_hp_pelanggan || '-');
                        $('#label_nama_salesman_giro').text(response.data.nama_salesman);
                        $('#infoPelangganContainerGiro').removeClass('hidden');

                        // Clear error styling on input
                        $('#kode_pelanggan_giro_group').removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500');
                        $('#kode_pelanggan_giro_group').closest('.relative').find('i').removeClass('text-rose-500');
                        $('#error-kode_pelanggan-giro').addClass('hidden').removeClass('flex');

                        // Load faktur options
                        $.ajax({
                            type: 'GET',
                            url: `/pelanggan/${response.data.kode_pelanggan}/getlistfakturkreditoption`,
                            cache: false,
                            success: function(respond) {
                                $('#no_faktur_giro_group').html(respond);
                                if ($('#no_faktur_giro_group').hasClass("select2-hidden-accessible")) {
                                    $('#no_faktur_giro_group').select2('destroy');
                                }
                                $('#no_faktur_giro_group').select2({
                                    placeholder: 'Pilih Faktur',
                                    allowClear: true,
                                    dropdownCssClass: 'select2-dropdown-v2'
                                });
                            }
                        });

                        // Load salesman by cabang
                        $.ajax({
                            type: 'POST',
                            url: '/salesman/getsalesmanbycabang',
                            data: {
                                _token: "{{ csrf_token() }}",
                                kode_cabang: response.data.kode_cabang
                            },
                            cache: false,
                            success: function(respond) {
                                $('#kode_salesman_penagih_giro').html(respond);
                                if ($('#kode_salesman_penagih_giro').hasClass("select2-hidden-accessible")) {
                                    $('#kode_salesman_penagih_giro').select2('destroy');
                                }
                                $('#kode_salesman_penagih_giro').select2({
                                    placeholder: 'Pilih Salesman Penagih',
                                    allowClear: true,
                                    dropdownCssClass: 'select2-dropdown-v2'
                                });
                            }
                        });

                        modalPelangganHelper.close();
                    }
                }
            });
        });
    });
</script>
@endpush

@extends('layouts.app')
@section('titlepage', 'Ticket Perubahan Data')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-[#284c9a] transition-colors">Home</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Utilities</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-[#284c9a] dark:text-blue-400 font-bold">Ticket Perubahan Data</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">
    @include('layouts.navigation_ticket')

    <!-- STATISTIC CARDS STRIP -->
    <div class="rounded-xl overflow-hidden shadow-xs hover:shadow-md transition-shadow duration-200 bg-[#284c9a] text-white relative">
        <div class="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none"></div>
        <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-white/15">
            <div class="group relative overflow-hidden p-4 flex flex-col justify-between min-h-[104px] hover:bg-white/[0.05] transition-colors">
                <svg class="absolute right-0 bottom-0 w-20 h-20 text-white/[0.06] pointer-events-none" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1 -bottom-1 text-white/[0.08] pointer-events-none">
                    <i data-lucide="files" class="w-14 h-14 stroke-[1.2]"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-white/75 uppercase tracking-wider">Total Ajuan</p>
                    <div class="text-2xl font-black text-white font-mono-num mt-1.5">{{ number_format($stats['total'] ?? 0) }}</div>
                </div>
            </div>

            <div class="group relative overflow-hidden p-4 flex flex-col justify-between min-h-[104px] hover:bg-white/[0.05] transition-colors">
                <svg class="absolute right-0 bottom-0 w-20 h-20 text-white/[0.06] pointer-events-none" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1 -bottom-1 text-white/[0.08] pointer-events-none">
                    <i data-lucide="hourglass" class="w-14 h-14 stroke-[1.2]"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-white/75 uppercase tracking-wider">Menunggu Approval</p>
                    <div class="text-2xl font-black text-amber-300 font-mono-num mt-1.5">{{ number_format($stats['pending'] ?? 0) }}</div>
                </div>
            </div>

            <div class="group relative overflow-hidden p-4 flex flex-col justify-between min-h-[104px] hover:bg-white/[0.05] transition-colors">
                <svg class="absolute right-0 bottom-0 w-20 h-20 text-white/[0.06] pointer-events-none" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1 -bottom-1 text-white/[0.08] pointer-events-none">
                    <i data-lucide="check-circle-2" class="w-14 h-14 stroke-[1.2]"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-white/75 uppercase tracking-wider">Disetujui / Selesai</p>
                    <div class="text-2xl font-black text-emerald-300 font-mono-num mt-1.5">{{ number_format($stats['selesai'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- COMPACT NO-LABEL FILTER BAR -->
    <form action="{{ route('ticketupdate.index') }}" method="GET">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
            @hasanyrole($roles_show_cabang)
            <div class="lg:col-span-5 relative select2-icon-wrapper">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none z-20 text-slate-400">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_cabang_search" id="kode_cabang_search" class="select2Kodecabangsearch w-full" data-placeholder="Semua Cabang">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                            {{ strtoupper($c->nama_cabang) }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endhasanyrole

            <div class="@hasanyrole($roles_show_cabang) lg:col-span-5 @else lg:col-span-10 @endhasanyrole relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                </div>
                <select name="status_search" id="status_search" class="w-full h-[36px] pl-8 pr-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ Request('status_search') == 'pending' ? 'selected' : '' }}>Belum Selesai (Pending)</option>
                    <option value="selesai" {{ Request('status_search') == 'selesai' ? 'selected' : '' }}>Selesai (Approved)</option>
                </select>
            </div>

            <div class="lg:col-span-2">
                <button type="submit" class="w-full h-[36px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold flex items-center justify-center gap-1.5 transition cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Filter</span>
                </button>
            </div>
        </div>
    </form>

    <!-- DATA TABLE CARD -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        <div class="px-3.5 py-2 bg-[#284c9a] text-white flex items-center justify-between gap-2 flex-wrap">
            <div class="flex items-center gap-2">
                <i data-lucide="file-diff" class="w-3.5 h-3.5 text-blue-200"></i>
                <h2 class="text-xs font-bold text-white uppercase tracking-wider">Daftar Ticket Perubahan Data</h2>
            </div>
            <button type="button" id="btnCreate" class="inline-flex items-center gap-1.5 h-[28px] px-3 rounded-lg bg-white/15 hover:bg-white/25 border border-white/20 text-white text-xs font-bold transition cursor-pointer">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Buat Ajuan Perubahan Data</span>
            </button>
        </div>

        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-left border-collapse text-xs font-sans whitespace-nowrap">
                <thead>
                    <tr class="bg-[#1e3a78] text-white/90">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">No. Ticket</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">Tanggal</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">Kategori</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">No. Bukti</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">Keterangan</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">Lampiran</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">Pengaju</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center">Cabang</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center">Status</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">Approval</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @php
                        $kategori = [
                            '1' => 'Penjualan',
                            '2' => 'Pembayaran',
                            '3' => 'Retur',
                            '4' => 'DPB',
                            '5' => 'Mutasi Persediaan',
                        ];
                    @endphp
                    @forelse ($ticket as $d)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 font-mono-num font-bold text-[11px] border border-blue-200/80 dark:border-blue-900">
                                    {{ $d->kode_pengajuan }}
                                </span>
                            </td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">{{ date('d/m/Y', strtotime($d->tanggal)) }}</td>
                            <td class="py-1.5 px-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-400 font-semibold text-[11px]">
                                    {{ $kategori[$d->kategori] ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="py-1.5 px-3 font-mono-num font-bold text-slate-700 dark:text-slate-200">{{ $d->no_bukti }}</td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300 max-w-[220px] truncate" title="{{ $d->keterangan }}">{{ $d->keterangan }}</td>
                            <td class="py-1.5 px-3">
                                @if (!empty($d->link))
                                    <a href="{{ url($d->link) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white text-[11px] font-semibold transition">
                                        <i data-lucide="paperclip" class="w-3 h-3"></i>
                                        <span>Lampiran</span>
                                    </a>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">{{ formatName2($d->name ?? '-') }}</td>
                            <td class="py-1.5 px-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-bold text-[11px]">
                                    {{ $d->kode_cabang }}
                                </span>
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status == '1')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 font-bold text-[10px] border border-emerald-200 dark:border-emerald-900">Selesai</span>
                                @elseif ($d->status == '2')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-400 font-bold text-[10px] border border-rose-200 dark:border-rose-900">Ditolak</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 font-bold text-[10px] border border-amber-200 dark:border-amber-900">Menunggu</span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-[11px]">
                                @if ($d->approval)
                                    <span class="font-semibold text-[#284c9a] dark:text-blue-400">Disetujui: {{ formatName2($d->approval) }}</span>
                                @else
                                    <span class="text-amber-600 dark:text-amber-400 font-medium">Menunggu Approval GM</span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @if ($d->gm == null)
                                        <button type="button" class="btnEdit w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white border border-amber-200/80 dark:border-amber-900 flex items-center justify-center transition cursor-pointer" kode_pengajuan="{{ $d->kode_pengajuan }}" title="Edit Ticket">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                    @endif

                                    @can('ticket.approve')
                                        @if (in_array($level_user, ['gm administrasi', 'regional operation manager', 'super admin']))
                                            @if ($d->status == '0')
                                                <button type="button" class="btnApprove w-6 h-6 rounded-md bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white border border-emerald-200/80 dark:border-emerald-900 flex items-center justify-center transition cursor-pointer" kode_pengajuan="{{ $d->kode_pengajuan }}" title="Approve Ticket">
                                                    <i data-lucide="check" class="w-3 h-3"></i>
                                                </button>
                                            @else
                                                <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform" action="{{ route('ticketupdate.cancel', Crypt::encrypt($d->kode_pengajuan)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="cancel-confirm w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white border border-rose-200/80 dark:border-rose-900 flex items-center justify-center transition cursor-pointer" title="Batalkan Approval">
                                                        <i data-lucide="x" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    @endcan

                                    @if ($d->gm == null)
                                        <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform" action="{{ route('ticketupdate.delete', Crypt::encrypt($d->kode_pengajuan)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-confirm w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white border border-rose-200/80 dark:border-rose-900 flex items-center justify-center transition cursor-pointer" title="Hapus Ticket">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center gap-1.5">
                                    <i data-lucide="file-x" class="w-8 h-8 text-slate-300 dark:text-slate-700"></i>
                                    <span>Belum ada ticket perubahan data yang cocok dengan kriteria filter.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($ticket->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $ticket->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<x-modal-form id="mdlCreate" size="modal-lg" show="loadCreate" title="Ticket Perubahan Data" />
@endsection

@push('myscript')
<script>
    $(function() {
        $('.select2Kodecabangsearch').select2({
            placeholder: 'Semua Cabang',
            allowClear: true
        });

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $('#mdlCreate').modal("show");
            $('#mdlCreate').find('.modal-title').text('Buat Ticket Perubahan Data');
            $("#loadCreate").load('/ticketupdate/create', function() {
                if (window.lucide) lucide.createIcons();
            });
        });

        $(document).on('click', '.btnEdit', function(e) {
            e.preventDefault();
            const kode_pengajuan = $(this).attr('kode_pengajuan');
            $('#mdlCreate').modal("show");
            $('#mdlCreate').find('.modal-title').text('Edit Ticket Perubahan Data');
            $("#loadCreate").load(`/ticketupdate/${kode_pengajuan}/edit`, function() {
                if (window.lucide) lucide.createIcons();
            });
        });

        $(document).on('click', '.btnApprove', function(e) {
            e.preventDefault();
            const kode_pengajuan = $(this).attr('kode_pengajuan');
            $('#mdlCreate').modal("show");
            $('#mdlCreate').find('.modal-title').text('Approve Ticket Perubahan Data');
            $("#loadCreate").load(`/ticketupdate/${kode_pengajuan}/approve`, function() {
                if (window.lucide) lucide.createIcons();
            });
        });
    });
</script>
@endpush

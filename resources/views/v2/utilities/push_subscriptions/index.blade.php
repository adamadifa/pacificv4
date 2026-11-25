@extends('layouts.app')
@section('titlepage', 'Push Subscriptions')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Utilities</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Push Subscriptions</span>
    </div>
@endsection

@section('content')
@php
    $totalSub = method_exists($subscriptions, 'total') ? $subscriptions->total() : $subscriptions->count();
@endphp

<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Push Notification Subscriptions</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Mengelola daftar perangkat pengguna yang terdaftar menerima notifikasi push web.</p>
        </div>
    </div>

    <!-- FILTER SECTION (NO-LABEL, COMPACT H-[34PX], LUCIDE PREFIX, ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('push-subscriptions.index') }}" method="GET" id="formSearch">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                
                <!-- Nama Karyawan (Col-span 10) -->
                <div class="sm:col-span-10 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="user_name" id="user_name_search" value="{{ Request('user_name') }}"
                        placeholder="Cari Nama Karyawan..."
                        class="w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>

                <!-- Tombol Aksi Filter (Col-span 2) -->
                <div class="sm:col-span-2 flex items-center gap-1.5">
                    <button type="submit" id="btnSearch"
                        class="flex-1 h-[34px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>
                    <a href="{{ route('push-subscriptions.index') }}" title="Reset Filter"
                        class="h-[34px] w-[34px] rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/60 text-slate-600 dark:text-slate-300 flex items-center justify-center transition shadow-2xs shrink-0 cursor-pointer">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

            </div>
        </form>
    </div>

    <!-- DATA TABLE CARD (V2 STANDARD ~36PX ROW HEIGHT) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto min-h-[260px]">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead>
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="5" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="bell-ring" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">Daftar Subscription Push Notification</span>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/15 text-white">
                                    Total: {{ number_format($totalSub, 0, ',', '.') }} Perangkat
                                </span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">WAKTU SUB</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[180px]">NAMA KARYAWAN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-32">NIK</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[240px]">ENDPOINT</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($subscriptions as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-400">
                                {{ date('d-m-Y H:i:s', strtotime($d->created_at)) }}
                            </td>
                            <td class="py-1.5 px-3 font-bold text-slate-900 dark:text-slate-100">
                                {{ $d->user_name }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num font-semibold text-[#284c9a] dark:text-blue-400">
                                {{ $d->nik }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num text-[11px] text-slate-500 dark:text-slate-400">
                                {{ Str::limit($d->endpoint, 55) }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('push-subscriptions.test', $d->id) }}"
                                        class="w-6 h-6 rounded-md bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-950/50 dark:text-amber-400 inline-flex items-center justify-center transition shadow-2xs"
                                        title="Kirim Test Notifikasi">
                                        <i data-lucide="bell" class="w-3 h-3"></i>
                                    </a>
                                    <form action="{{ route('push-subscriptions.destroy', $d->id) }}" method="POST"
                                        class="inline-flex m-0 p-0 deleteform">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="delete-confirm w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 inline-flex items-center justify-center transition shadow-2xs cursor-pointer"
                                            title="Hapus Subscription">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="bell-off" class="w-8 h-8 stroke-[1.2] text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Belum ada data Push Subscription</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION STRIP -->
        @if ($subscriptions->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $subscriptions->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush

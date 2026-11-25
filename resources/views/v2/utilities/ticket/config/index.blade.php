@extends('layouts.app')
@section('titlepage', 'Config Approval Tiket')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-[#284c9a] transition-colors">Home</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('ticket.index') }}" class="text-slate-400 hover:text-[#284c9a] transition-colors">Tiket</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-[#284c9a] dark:text-blue-400 font-bold">Config Approval</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">
    @include('layouts.navigation_ticket')

    <!-- Header Strip -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3.5 sm:p-4 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-900 shrink-0">
                <i data-lucide="settings-2" class="w-4 h-4"></i>
            </div>
            <div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-white leading-tight">Konfigurasi Approval Tiket</h2>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Kelola hierarki dan alur persetujuan tiket sesuai departemen dan cabang.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('ticket.index') }}" class="inline-flex items-center gap-1.5 h-[34px] px-3 rounded-lg bg-white dark:bg-slate-800 hover:bg-slate-50 border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 transition">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali</span>
            </a>
            <a href="{{ route('ticketconfig.create') }}" class="inline-flex items-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Konfigurasi</span>
            </a>
        </div>
    </div>

    <!-- Config List -->
    <div class="space-y-2.5">
        @forelse ($config as $d)
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3.5 shadow-2xs hover:border-slate-300 dark:hover:border-slate-700 transition-all">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                    <!-- Unit Organization -->
                    <div class="md:col-span-4 flex items-center gap-3 md:border-r md:border-slate-100 dark:md:border-slate-800 md:pr-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-900 shrink-0">
                            <i data-lucide="ticket" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                @if ($d->nama_cabang)
                                    {{ $d->nama_cabang }}
                                @else
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 text-[10px]">SEMUA CABANG</span>
                                @endif
                            </div>
                            <div class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-0.5">
                                @if ($d->nama_dept)
                                    {{ $d->nama_dept }}
                                @else
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 text-[10px]">SEMUA DEPARTEMEN</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Approval Pipeline -->
                    <div class="md:col-span-6 md:border-r md:border-slate-100 dark:md:border-slate-800 md:pr-3">
                        <div class="flex items-center flex-wrap gap-1.5 p-2 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <span class="text-[10px] font-bold uppercase text-slate-400 mr-1">Flow:</span>
                            @foreach ($d->roles as $role)
                                <span class="px-2 py-0.5 rounded-md bg-white dark:bg-slate-900 text-[#284c9a] dark:text-blue-400 border border-blue-200/60 dark:border-blue-900 text-[11px] font-bold">
                                    {{ textUpperCase($role) }}
                                </span>
                                @if (!$loop->last)
                                    <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
                                @endif
                            @endforeach
                            <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
                            <span class="px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 text-[11px] font-bold">
                                ADMIN IT
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="md:col-span-2 flex items-center justify-end gap-1.5">
                        <a href="{{ route('ticketconfig.edit', $d->id) }}" class="w-7 h-7 rounded-lg bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white border border-amber-200/80 dark:border-amber-900 flex items-center justify-center transition" title="Edit">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                        </a>
                        <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform" action="{{ route('ticketconfig.destroy', $d->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-confirm w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white border border-rose-200/80 dark:border-rose-900 flex items-center justify-center transition cursor-pointer" title="Hapus">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl bg-white dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-800 p-10 text-center">
                <i data-lucide="settings-2" class="w-10 h-10 text-slate-300 dark:text-slate-700 mx-auto mb-2"></i>
                <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200">Belum Ada Konfigurasi</h4>
                <p class="text-xs text-slate-400 mt-1 mb-4">Tanpa konfigurasi, semua tiket akan langsung masuk ke Admin IT tanpa perlu persetujuan bertahap.</p>
                <a href="{{ route('ticketconfig.create') }}" class="inline-flex items-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Konfigurasi Pertama</span>
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection

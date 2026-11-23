@extends('layouts.app')
@section('titlepage', 'Konfigurasi Approval Ajuan Limit')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('ajuanlimit.index') }}" class="text-slate-400 hover:text-brand-600">Ajuan Limit Kredit</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Konfigurasi</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Konfigurasi Approval Ajuan Limit</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola alur tahapan persetujuan limit kredit berdasarkan rentang nominal.</p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('ajuanlimit.index') }}"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold transition shadow-2xs cursor-pointer">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali</span>
            </a>

            @can('ajuanlimit.config')
                <a href="{{ route('ajuanlimitconfig.create') }}"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Konfigurasi</span>
                </a>
            @endcan
        </div>
    </div>

    <!-- CONFIGURATION LIST CARDS -->
    <div class="space-y-3">
        @forelse ($config as $d)
            @php
                $roles = is_string($d->roles) ? json_decode($d->roles) : $d->roles;
            @endphp
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 shadow-2xs hover:shadow-xs transition">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    
                    <!-- Nominal Range Badge -->
                    <div class="lg:w-72 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                                <i data-lucide="coins" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Rentang Nominal</span>
                                <div class="text-xs font-mono-num font-bold text-slate-800 dark:text-slate-100 mt-0.5">
                                    <span class="text-[#284c9a] dark:text-blue-400">Rp {{ number_format($d->min_limit, 0, ',', '.') }}</span>
                                    <span class="text-slate-400 mx-1">s/d</span>
                                    <span class="text-[#284c9a] dark:text-blue-400">Rp {{ number_format($d->max_limit, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Approval Pipeline Flow -->
                    <div class="flex-1 border-t lg:border-t-0 lg:border-l border-slate-100 dark:border-slate-800 pt-3 lg:pt-0 lg:pl-4 space-y-1.5">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Alur Tahapan Persetujuan</span>
                        <div class="flex items-center flex-wrap gap-2">
                            @foreach ($roles as $role)
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 shadow-2xs">
                                    <span class="w-4 h-4 rounded-full bg-[#284c9a] text-white flex items-center justify-center text-[9px] font-mono-num font-black">
                                        {{ $loop->iteration }}
                                    </span>
                                    <span>{{ textUpperCase($role) }}</span>
                                </div>
                                @if (!$loop->last)
                                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 dark:text-slate-600 shrink-0"></i>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-1.5 shrink-0 self-end lg:self-center border-t lg:border-t-0 pt-2 lg:pt-0 border-slate-100 dark:border-slate-800">
                        @can('ajuanlimit.config')
                            <a href="{{ route('ajuanlimitconfig.edit', $d->id) }}"
                                class="w-7 h-7 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/50 dark:hover:bg-amber-900/50 dark:text-amber-400 flex items-center justify-center transition cursor-pointer"
                                title="Edit Alur">
                                <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                            </a>

                            <form action="{{ route('ajuanlimitconfig.destroy', $d->id) }}" method="POST" class="deleteform inline-flex m-0 p-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/50 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                    title="Hapus Konfigurasi">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </form>
                        @endcan
                    </div>

                </div>
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-10 text-center text-slate-400">
                <i data-lucide="settings" class="w-10 h-10 mx-auto mb-2 text-slate-300 dark:text-slate-600 stroke-1"></i>
                <h4 class="text-xs font-bold text-slate-600 dark:text-slate-300">Belum Ada Konfigurasi Approval</h4>
                <p class="text-[11px] text-slate-400 mt-0.5">Silakan tambahkan urutan role approval berdasarkan rentang nominal.</p>
            </div>
        @endforelse
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

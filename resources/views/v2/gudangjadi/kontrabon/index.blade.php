@extends('layouts.app')
@section('titlepage', 'Kontrabon Angkutan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Gudang Jadi</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Kontrabon Angkutan</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">
    @include('v2.gudangjadi.kontrabon.kontrabon')
</div>
@endsection

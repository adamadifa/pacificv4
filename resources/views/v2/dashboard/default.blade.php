@extends('layouts.app')
@section('titlepage', 'Dashboard')

@section('navigasi')
    <div class="flex items-center gap-2">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Dashboard</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">
    <!-- WELCOME BANNER V2 -->
    @include('dashboard.welcome')

    <!-- DASHBOARD SUB-NAVIGATION BAR -->
    @include('layouts.navigation_dashboard')

    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 p-8 text-center shadow-xs">
        <div class="w-16 h-16 rounded-3xl bg-brand-50 dark:bg-brand-950/50 text-brand-600 dark:text-brand-400 flex items-center justify-center mx-auto mb-4 border border-brand-100 dark:border-brand-900">
            <i data-lucide="layout-grid" class="w-8 h-8"></i>
        </div>
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Selamat Datang di Portal Pacific</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto mt-2 leading-relaxed">
            Anda telah berhasil masuk ke dalam sistem. Gunakan menu navigasi di sebelah kiri untuk mengakses modul-modul yang tersedia sesuai hak akses Anda.
        </p>
    </div>
</div>
@endsection

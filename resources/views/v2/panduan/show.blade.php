@extends('layouts.app')
@section('titlepage', $artikel->judul)

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-[#284c9a] transition-colors">Home</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('panduan.index') }}" class="text-slate-400 hover:text-[#284c9a] transition-colors">Buku Panduan</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-[#284c9a] dark:text-blue-400 font-bold">{{ $artikel->judul }}</span>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
    <!-- Main Content -->
    <div class="lg:col-span-8 space-y-3">
        <div>
            <a href="{{ route('panduan.index') }}" class="inline-flex items-center gap-1.5 h-[32px] px-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 text-xs font-bold text-slate-600 dark:text-slate-300 transition">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Daftar Panduan</span>
            </a>
        </div>

        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
            <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 border border-blue-100 dark:border-blue-900 flex items-center justify-center text-xl shrink-0">
                    {{ $artikel->icon }}
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">{{ $artikel->judul }}</h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">Kategori: <strong>{{ $artikel->kategori }}</strong> • Diperbarui {{ $artikel->updated_at->diffForHumans() }}</p>
                </div>
            </div>
            <div class="p-5 text-xs sm:text-sm text-slate-700 dark:text-slate-200 leading-relaxed prose prose-sm dark:prose-invert max-w-none">
                {!! $artikel->konten !!}
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="lg:col-span-4 space-y-4">
        @if (!$related->isEmpty())
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
                <div class="px-3.5 py-2.5 bg-[#284c9a] text-white flex items-center gap-2">
                    <i data-lucide="list" class="w-4 h-4 text-blue-200"></i>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Panduan Terkait</h3>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @foreach ($related as $rel)
                        <a href="{{ route('panduan.show', $rel->slug) }}" class="flex items-center gap-2.5 px-3.5 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                            <span class="text-base">{{ $rel->icon }}</span>
                            <span class="flex-1 text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $rel->judul }}</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
            <div class="px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-800 flex items-center gap-2">
                <i data-lucide="folder" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100">Kategori Panduan</h3>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
                @foreach ($allCategories as $cat)
                    <a href="{{ route('panduan.index', ['search' => $cat]) }}" class="flex items-center justify-between px-3.5 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $cat }}</span>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 text-center space-y-2.5">
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 border border-blue-100 dark:border-blue-900 flex items-center justify-center mx-auto">
                <i data-lucide="message-square" class="w-5 h-5"></i>
            </div>
            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Butuh Bantuan Lain?</h4>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">Tanyakan langsung ke asisten bot kami di panel chat melayang untuk penjelasan detail fitur lainnya.</p>
            <button type="button" onclick="document.getElementById('chat-widget-toggle')?.click()" class="inline-flex items-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition cursor-pointer">
                <i data-lucide="messages-square" class="w-3.5 h-3.5"></i>
                <span>Tanya Asisten</span>
            </button>
        </div>
    </div>
</div>
@endsection

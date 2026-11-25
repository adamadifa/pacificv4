@extends('layouts.app')
@section('titlepage', 'Buku Panduan & Bantuan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-[#284c9a] transition-colors">Home</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Bantuan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-[#284c9a] dark:text-blue-400 font-bold">Buku Panduan</span>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
    <!-- Main Content Area -->
    <div class="lg:col-span-8 space-y-4">

        <!-- Hero Search Card (Pacific Navy) -->
        <div class="rounded-xl bg-[#284c9a] text-white p-6 text-center relative overflow-hidden shadow-xs">
            <svg class="absolute right-0 bottom-0 w-36 h-36 text-white/[0.06] pointer-events-none" viewBox="0 0 100 100" fill="none">
                <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
            </svg>
            <div class="relative z-10 max-w-lg mx-auto">
                <h2 class="text-lg font-black text-white">Ada yang bisa kami bantu?</h2>
                <p class="text-xs text-white/75 mt-1 mb-4">Cari panduan penggunaan modul sistem di bawah ini.</p>
                <form action="{{ route('panduan.index') }}" method="GET">
                    <div class="flex items-center bg-white dark:bg-slate-900 rounded-lg overflow-hidden shadow-sm border border-white/20">
                        <div class="pl-3 flex items-center text-slate-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="search" value="{{ $search }}" class="w-full h-[38px] px-3 bg-transparent border-0 text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none" placeholder="Ketik kata kunci (misal: BPB, Pembelian, dll)...">
                        @if ($search)
                            <a href="{{ route('panduan.index') }}" class="px-3 text-slate-400 hover:text-rose-500 transition" title="Hapus Pencarian">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if ($kategoriGroup->isEmpty())
            <div class="rounded-xl bg-white dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-800 p-10 text-center">
                <i data-lucide="search-x" class="w-10 h-10 text-slate-300 dark:text-slate-700 mx-auto mb-2"></i>
                <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200">Panduan Tidak Ditemukan</h4>
                <p class="text-xs text-slate-400 mt-1 mb-4">Kata kunci <strong>"{{ $search }}"</strong> tidak cocok dengan artikel apa pun. Coba kata kunci lainnya atau gunakan Q&amp;A Chat.</p>
                <a href="{{ route('panduan.index') }}" class="inline-flex items-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition">
                    <span>Lihat Semua Panduan</span>
                </a>
            </div>
        @else
            @foreach ($kategoriGroup as $kategori => $artikels)
                <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
                    <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="folder-open" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100">Kategori: {{ $kategori }}</h3>
                        </div>
                        <span class="px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 text-[11px] font-bold font-mono-num">
                            {{ $artikels->count() }} Panduan
                        </span>
                    </div>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @foreach ($artikels as $artikel)
                            <a href="{{ route('panduan.show', $artikel->slug) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors group">
                                <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 border border-blue-100 dark:border-blue-900 flex items-center justify-center text-base shrink-0">
                                    {{ $artikel->icon }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 transition-colors">{{ $artikel->judul }}</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ $artikel->deskripsi_singkat }}</p>
                                </div>
                                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition-transform shrink-0"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Right Sidebar (FAQs & Quick Help) -->
    <div class="lg:col-span-4 space-y-4">
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
            <div class="px-3.5 py-2.5 bg-[#284c9a] text-white flex items-center gap-2">
                <i data-lucide="help-circle" class="w-4 h-4 text-blue-200"></i>
                <h3 class="text-xs font-bold text-white uppercase tracking-wider">Tanya Jawab Populer</h3>
            </div>
            <div class="p-3.5 space-y-2">
                <p class="text-[11px] text-slate-400 mb-2">Klik pada pertanyaan di bawah ini untuk melihat jawaban cepat.</p>
                @foreach ($faqs as $index => $faq)
                    <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 overflow-hidden">
                        <button type="button" class="faq-toggle w-full px-3 py-2.5 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/50 text-left text-xs font-bold text-slate-700 dark:text-slate-200 flex items-center justify-between gap-2 transition cursor-pointer" data-target="#faq-body-{{ $index }}">
                            <span>{{ $faq->pertanyaan }}</span>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform duration-200"></i>
                        </button>
                        <div id="faq-body-{{ $index }}" class="hidden px-3 py-2.5 bg-slate-50 dark:bg-slate-800/40 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            {!! $faq->jawaban !!}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 text-center space-y-2.5">
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 border border-blue-100 dark:border-blue-900 flex items-center justify-center mx-auto">
                <i data-lucide="message-square" class="w-5 h-5"></i>
            </div>
            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Masih Bingung?</h4>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">Gunakan <strong>Q&amp;A Assistant Chat</strong> di pojok kanan bawah halaman untuk bertanya langsung kepada bot asisten kami.</p>
            <button type="button" onclick="document.getElementById('chat-widget-toggle')?.click()" class="inline-flex items-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition cursor-pointer">
                <i data-lucide="messages-square" class="w-3.5 h-3.5"></i>
                <span>Mulai Bertanya</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        $('.faq-toggle').on('click', function() {
            const target = $(this).data('target');
            const $body = $(target);
            const $icon = $(this).find('svg, i');
            $body.toggleClass('hidden');
            $icon.toggleClass('rotate-180');
        });
    });
</script>
@endpush

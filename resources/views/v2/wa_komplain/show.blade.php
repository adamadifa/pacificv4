@extends('layouts.app')
@section('titlepage', 'Detail Komplain WA')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-[#284c9a] transition-colors">Home</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('wa-komplain.index') }}" class="text-slate-400 hover:text-[#284c9a] transition-colors">Komplain WA</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-[#284c9a] dark:text-blue-400 font-bold">#{{ $komplain->no_komplain }}</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">
    @if (session('success'))
        <div class="rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 px-4 py-3 flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300">
            <i data-lucide="check-circle-2" class="w-4 h-4 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
        <!-- Left Column: Complaint Details & Chat History -->
        <div class="lg:col-span-8 space-y-4">
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs space-y-3">
                <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <i data-lucide="file-text" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                        <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Komplain #{{ $komplain->no_komplain }}</h3>
                    </div>
                    <a href="{{ route('wa-komplain.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-slate-500 hover:text-[#284c9a] transition">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                        <span>Kembali</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="block text-[10px] font-bold uppercase text-slate-400">Nama Pelanggan</span>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="font-bold text-slate-800 dark:text-slate-100">{{ $komplain->nama_pelanggan }}</span>
                            @if ($komplain->kode_pelanggan)
                                <span class="px-1.5 py-0.5 rounded bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-400 font-mono-num font-bold text-[10px] border border-sky-200 dark:border-sky-900">{{ $komplain->kode_pelanggan }}</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase text-slate-400">Nomor WhatsApp</span>
                        <span class="font-mono-num font-bold text-slate-800 dark:text-slate-100 mt-0.5 inline-block">{{ $komplain->wa_number }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase text-slate-400">Kategori AI</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-bold text-[10px] uppercase mt-0.5">{{ $komplain->kategori_ai ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase text-slate-400">Tanggal Masuk</span>
                        <span class="font-mono-num font-semibold text-slate-800 dark:text-slate-100 mt-0.5 inline-block">{{ $komplain->tanggal_komplain->format('d-m-Y') }}</span>
                    </div>
                </div>

                <div>
                    <span class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Isi Komplain Original</span>
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-800 text-xs text-slate-700 dark:text-slate-200 leading-relaxed whitespace-pre-wrap">{{ $komplain->isi_komplain }}</div>
                </div>

                @if ($komplain->ringkasan_ai)
                    <div>
                        <span class="block text-[10px] font-bold uppercase text-sky-600 dark:text-sky-400 mb-1">Ringkasan AI (Gemini)</span>
                        <div class="p-3 rounded-lg bg-sky-50 dark:bg-sky-950/40 border border-sky-200 dark:border-sky-800 text-xs text-sky-900 dark:text-sky-200 leading-relaxed whitespace-pre-wrap">{{ $komplain->ringkasan_ai }}</div>
                    </div>
                @endif
            </div>

            @if (!empty($komplain->chat_history))
                <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
                    <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-800 flex items-center gap-2">
                        <i data-lucide="message-circle" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i>
                        <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100">Riwayat Percakapan WA</h3>
                    </div>
                    <div class="p-4 max-h-[400px] overflow-y-auto flex flex-col gap-3 scrollbar-thin">
                        @foreach ($komplain->chat_history as $chat)
                            @php $isFromMe = isset($chat['fromMe']) && $chat['fromMe']; @endphp
                            <div class="flex {{ $isFromMe ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[78%] px-3.5 py-2 rounded-2xl text-xs leading-relaxed whitespace-pre-wrap {{ $isFromMe ? 'bg-[#284c9a] text-white rounded-br-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-100 border border-slate-200 dark:border-slate-700 rounded-bl-xs' }}">
                                    <div class="text-[10px] font-bold mb-0.5 {{ $isFromMe ? 'text-blue-200' : 'text-slate-400' }}">
                                        {{ $isFromMe ? 'Gemini AI' : $komplain->nama_pelanggan }}
                                    </div>
                                    <div>{{ $chat['message'] ?? $chat['text'] ?? '' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: CS Actions -->
        <div class="lg:col-span-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs space-y-4">
            <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                <i data-lucide="settings" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100">Tindakan CS</h3>
            </div>

            <form action="{{ route('wa-komplain.status', $komplain->id) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label for="status_komplain" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Update Status</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="activity" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="status" id="status_komplain" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
                            <option value="baru" {{ $komplain->status == 'baru' ? 'selected' : '' }}>Baru</option>
                            <option value="diproses" {{ $komplain->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ $komplain->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ $komplain->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="catatan_cs" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Catatan Penyelesaian / Tindakan</label>
                    <textarea name="catatan_cs" id="catatan_cs" rows="4" class="w-full p-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="Tuliskan catatan penanganan di sini...">{{ $komplain->catatan_cs }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="w-full h-[36px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold flex items-center justify-center gap-1.5 transition cursor-pointer">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span>Simpan Status</span>
                    </button>
                </div>
            </form>

            <div class="border-t border-slate-100 dark:border-slate-800 pt-4">
                <form action="{{ route('wa-komplain.assign', $komplain->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label for="user_id_assign" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Tugaskan ke CS Agen</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="user_id" id="user_id_assign" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
                                <option value="">Pilih Agen</option>
                                @foreach ($csUsers as $user)
                                    <option value="{{ $user->id }}" {{ $komplain->ditangani_oleh == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full h-[36px] rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 text-xs font-bold text-slate-700 dark:text-slate-200 flex items-center justify-center gap-1.5 transition cursor-pointer">
                        <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                        <span>Tugaskan Agen</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

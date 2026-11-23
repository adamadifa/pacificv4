<div class="space-y-3.5">
    
    <!-- FOTO TOKO & OWNER PREVIEW -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <!-- Foto Toko -->
            <div class="flex items-center gap-3 p-2 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800">
                <div class="w-20 h-20 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0">
                    @if (!empty($ajuanlimit->foto) && Storage::disk('public')->exists('/pelanggan/' . $ajuanlimit->foto))
                        <img src="{{ getfotoPelanggan($ajuanlimit->foto) }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Dokumentasi Toko</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100 block mt-0.5">{{ $ajuanlimit->nama_pelanggan }}</span>
                    <span class="text-[11px] text-slate-500 block truncate max-w-[200px]">{{ $ajuanlimit->alamat_pelanggan }}</span>
                </div>
            </div>

            <!-- Foto Owner -->
            <div class="flex items-center gap-3 p-2 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800">
                <div class="w-20 h-20 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0">
                    @if (!empty($ajuanlimit->foto_owner) && Storage::disk('public')->exists('/pelanggan/owner/' . $ajuanlimit->foto_owner))
                        <img src="{{ getfotoPelangganowner($ajuanlimit->foto_owner) }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Dokumentasi Owner</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100 block mt-0.5">{{ $ajuanlimit->nama_pelanggan }}</span>
                    <span class="text-[11px] font-mono-num text-slate-500 block">NIK: {{ $ajuanlimit->nik ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- DETAIL INFORMASI UTAMA & PENDUKUNG (2 COLUMNS) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 items-start">
        
        <!-- KOLOM 1: DATA PENGAJUAN -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 bg-white dark:bg-slate-900 space-y-2">
            <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100">
                <i data-lucide="file-text" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <span>Data Pengajuan</span>
            </div>

            <div class="space-y-1.5 text-xs">
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">No. Pengajuan</span>
                    <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $ajuanlimit->no_pengajuan }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Tanggal Pengajuan</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ DateToIndo($ajuanlimit->tanggal) }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Kode Pelanggan</span>
                    <span class="font-mono-num font-semibold text-slate-700 dark:text-slate-300">{{ $ajuanlimit->kode_pelanggan }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Nama Pelanggan</span>
                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ $ajuanlimit->nama_pelanggan }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">No. HP</span>
                    <span class="font-mono-num text-slate-700 dark:text-slate-300">{{ $ajuanlimit->no_hp_pelanggan ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Cabang & Salesman</span>
                    <span class="text-slate-700 dark:text-slate-300 font-medium">
                        {{ textUpperCase($ajuanlimit->nama_cabang) }} - {{ textUpperCase($ajuanlimit->nama_salesman) }}
                    </span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Hari Kunjungan</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $ajuanlimit->hari ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Titik Koordinat</span>
                    @if (!empty($ajuanlimit->latitude) && !empty($ajuanlimit->longitude))
                        <a href="https://www.google.com/maps?q={{ $ajuanlimit->latitude }},{{ $ajuanlimit->longitude }}" target="_blank"
                            class="inline-flex items-center gap-1 font-semibold text-sky-600 dark:text-sky-400 hover:underline">
                            <i data-lucide="map-pin" class="w-3 h-3"></i>
                            <span>Buka Google Maps</span>
                        </a>
                    @else
                        <span class="text-slate-400">-</span>
                    @endif
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Jumlah Ajuan Limit</span>
                    <span class="font-mono-num font-black text-emerald-600 dark:text-emerald-400 text-sm">
                        Rp {{ number_format($ajuanlimit->jumlah, 0, ',', '.') }}
                    </span>
                </div>
                @if (!empty($ajuanlimit->jumlah_rekomendasi))
                    <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                        <span class="text-slate-400">Limit Rekomendasi</span>
                        <span class="font-mono-num font-black text-[#284c9a] dark:text-blue-400 text-sm">
                            Rp {{ number_format($ajuanlimit->jumlah_rekomendasi, 0, ',', '.') }}
                        </span>
                    </div>
                @endif
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">LJT (Tenor)</span>
                    <span class="font-mono-num font-bold text-slate-800 dark:text-slate-200">{{ $ajuanlimit->ljt }} Hari</span>
                </div>
            </div>
        </div>

        <!-- KOLOM 2: DATA PENDUKUNG & SKOR -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 bg-white dark:bg-slate-900 space-y-2">
            <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100">
                <i data-lucide="sliders" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <span>Data Pendukung & Skor</span>
            </div>

            <div class="space-y-1.5 text-xs">
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Kepemilikan Toko</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $kepemilikan[$ajuanlimit->kepemilikan] ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Lama Usaha</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $lama_berjualan[$ajuanlimit->lama_berjualan] ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Status & Tipe Outlet</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200">
                        {{ $status_outlet[$ajuanlimit->status_outlet] ?? '-' }} ({{ $type_outlet[$ajuanlimit->type_outlet] ?? '-' }})
                    </span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Cara Pembayaran</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $cara_pembayaran[$ajuanlimit->cara_pembayaran] ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Lama Langganan</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $lama_langganan[$ajuanlimit->lama_langganan] ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Jaminan</span>
                    <span class="font-bold {{ $ajuanlimit->jaminan == '1' ? 'text-emerald-600' : 'text-slate-500' }}">
                        {{ $ajuanlimit->jaminan == '1' ? 'Ada' : 'Tidak Ada' }}
                    </span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Omset Toko / Bulan</span>
                    <span class="font-mono-num font-bold text-slate-900 dark:text-slate-100">
                        Rp {{ number_format($ajuanlimit->omset_toko ?? 0, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Skor Kelayakan</span>
                    <span class="font-mono-num font-black text-[#284c9a] dark:text-blue-400 text-sm">
                        {{ formatAngkaDesimal($ajuanlimit->skor) }}
                    </span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Rekomendasi</span>
                    @php
                        if ($ajuanlimit->skor <= 2) $rekText = 'Tidak Layak';
                        elseif ($ajuanlimit->skor <= 4) $rekText = 'Tidak Disarankan';
                        elseif ($ajuanlimit->skor <= 6.75) $rekText = 'Beresiko';
                        elseif ($ajuanlimit->skor <= 8.5) $rekText = 'Layak Dengan Pertimbangan';
                        else $rekText = 'Layak';
                    @endphp
                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ $rekText }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">Referensi</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-300 uppercase">
                        {{ !empty($ajuanlimit->referensi) ? $ajuanlimit->referensi : '-' }}
                        @if (!empty($ajuanlimit->ket_referensi))
                            ({{ $ajuanlimit->ket_referensi }})
                        @endif
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- TIMELINE DISPOSISI & PERSETUJUAN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 bg-white dark:bg-slate-900 space-y-2.5">
        <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100">
            <i data-lucide="git-commit" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Riwayat Disposisi & Persetujuan</span>
        </div>

        @if ($disposisi->isEmpty())
            <div class="py-6 text-center text-slate-400 text-xs font-medium">
                Belum ada catatan riwayat disposisi untuk pengajuan ini.
            </div>
        @else
            <div class="relative pl-5 space-y-3 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200 dark:before:bg-slate-800">
                @foreach ($disposisi as $disp)
                    @php
                        if ($disp->status == '1') {
                            $dotColor = 'bg-emerald-500 ring-4 ring-emerald-100 dark:ring-emerald-950/60';
                            $statusBadge = '<span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Disetujui</span>';
                        } elseif ($disp->status == '2') {
                            $dotColor = 'bg-rose-500 ring-4 ring-rose-100 dark:ring-rose-950/60';
                            $statusBadge = '<span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>';
                        } else {
                            $dotColor = 'bg-[#284c9a] ring-4 ring-blue-100 dark:ring-blue-950/60';
                            $statusBadge = '<span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">Diteruskan</span>';
                        }
                    @endphp
                    <div class="relative text-xs">
                        <div class="absolute -left-5 top-1 w-2.5 h-2.5 rounded-full {{ $dotColor }}"></div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                            <div class="flex items-center gap-1.5">
                                <span class="font-bold text-slate-800 dark:text-slate-100">{{ $disp->username }}</span>
                                <span class="text-[11px] text-slate-400">({{ textUpperCase($disp->role) }})</span>
                                {!! $statusBadge !!}
                            </div>
                            <span class="text-[10.5px] font-mono-num text-slate-400">
                                {{ date('d M Y H:i', strtotime($disp->created_at)) }}
                            </span>
                        </div>
                        @if (!empty($disp->uraian_analisa))
                            <p class="mt-1 text-slate-600 dark:text-slate-300 text-[11px] bg-slate-50 dark:bg-slate-800/60 p-2 rounded-lg border border-slate-100 dark:border-slate-800">
                                {{ $disp->uraian_analisa }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- FOOTER CLOSE BUTTON -->
    <div class="flex items-center justify-end pt-1">
        <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
            Tutup
        </button>
    </div>

</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

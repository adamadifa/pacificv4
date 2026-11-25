<div class="space-y-4">
    <!-- HEADER BANNER (bg-[#284c9a]) -->
    <div class="rounded-xl bg-[#284c9a] p-4 sm:p-5 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-14 h-14 rounded-full overflow-hidden shrink-0 bg-white/15 border-2 border-white/40 flex items-center justify-center">
                @if (!empty($penilaiankaryawan->foto) && Storage::disk('public')->exists('/karyawan/' . ($penilaiankaryawan instanceof App\Models\Karyawan ? $penilaiankaryawan->getRawOriginal('foto') : $penilaiankaryawan->foto)))
                    <img src="{{ getfotoKaryawan($penilaiankaryawan->foto) }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    <i data-lucide="user" class="w-7 h-7 text-white"></i>
                @endif
            </div>
            <div>
                <h4 class="text-base font-black text-white leading-tight">{{ formatName($penilaiankaryawan->nama_karyawan) }}</h4>
                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                    <span class="px-2 py-0.5 rounded-md bg-white text-[#284c9a] text-[11px] font-mono-num font-bold">{{ $penilaiankaryawan->nik }}</span>
                    <span class="text-xs text-white/75 flex items-center gap-1">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        {{ DateToIndo($penilaiankaryawan->tanggal) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex sm:flex-col items-center justify-between sm:justify-center gap-1 shrink-0">
            <div class="w-16 h-16 rounded-full bg-white/15 border-2 border-white/30 flex items-center justify-center">
                <span class="text-2xl font-black font-mono-num text-white">{{ $total_score->total_score }}</span>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider text-white/75">Total Score</span>
        </div>
    </div>

    <!-- INFORMASI KARYAWAN & REKAP PRESENSI -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <!-- Info Karyawan -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5">
            <div class="flex items-center gap-1.5 pb-2 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                <i data-lucide="info" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Karyawan</h6>
            </div>
            <div class="divide-y divide-dashed divide-slate-200 dark:divide-slate-800 text-xs">
                <div class="py-1.5 flex items-center justify-between gap-2">
                    <span class="text-slate-400">Jabatan</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-100 text-right">{{ $penilaiankaryawan->nama_jabatan }}</span>
                </div>
                <div class="py-1.5 flex items-center justify-between gap-2">
                    <span class="text-slate-400">Departemen</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-100 text-right">{{ $penilaiankaryawan->nama_dept }}</span>
                </div>
                <div class="py-1.5 flex items-center justify-between gap-2">
                    <span class="text-slate-400">Cabang</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-100 text-right">{{ $penilaiankaryawan->nama_cabang }}</span>
                </div>
                <div class="py-1.5 flex items-center justify-between gap-2">
                    <span class="text-slate-400">Periode Kontrak</span>
                    <span class="font-bold text-[#284c9a] dark:text-blue-400 text-right">{{ DateToIndo($penilaiankaryawan->kontrak_dari) }} - {{ DateToIndo($penilaiankaryawan->kontrak_sampai) }}</span>
                </div>
            </div>
        </div>

        <!-- Rekap Presensi -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5">
            <div class="flex items-center gap-1.5 pb-2 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                <i data-lucide="bar-chart-2" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Rekap Presensi</h6>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="p-2.5 rounded-lg border border-sky-200/80 dark:border-sky-900/50 bg-sky-50/70 dark:bg-sky-950/30 text-center">
                    <p class="text-lg font-black font-mono-num text-sky-700 dark:text-sky-300">{{ $penilaiankaryawan->sid }}</p>
                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">SID</p>
                </div>
                <div class="p-2.5 rounded-lg border border-amber-200/80 dark:border-amber-900/50 bg-amber-50/70 dark:bg-amber-950/30 text-center">
                    <p class="text-lg font-black font-mono-num text-amber-700 dark:text-amber-300">{{ $penilaiankaryawan->izin }}</p>
                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Izin</p>
                </div>
                <div class="p-2.5 rounded-lg border border-rose-200/80 dark:border-rose-900/50 bg-rose-50/70 dark:bg-rose-950/30 text-center">
                    <p class="text-lg font-black font-mono-num text-rose-700 dark:text-rose-300">{{ $penilaiankaryawan->sakit }}</p>
                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Sakit</p>
                </div>
                <div class="p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100/70 dark:bg-slate-800/50 text-center">
                    <p class="text-lg font-black font-mono-num text-slate-700 dark:text-slate-200">{{ $penilaiankaryawan->alfa }}</p>
                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Alfa</p>
                </div>
            </div>
        </div>
    </div>

    <!-- HASIL PENILAIAN & REKOMENDASI -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 space-y-3">
        <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800">
            <i data-lucide="clock" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Hasil Penilaian & Rekomendasi</h6>
        </div>

        <div class="flex flex-wrap gap-2">
            @foreach (['TP' => 'Tidak Di Perpanjang', 'K3' => '3 Bulan', 'K6' => '6 Bulan', 'KT' => 'Karyawan Tetap'] as $kode => $label)
                @php $active = $penilaiankaryawan->masa_kontrak == $kode; @endphp
                <div class="px-3 py-1.5 rounded-lg border text-xs font-bold inline-flex items-center gap-1.5 {{ $active ? 'border-[#284c9a] bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-300' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-400' }}">
                    <i data-lucide="{{ $active ? 'check-square' : 'square' }}" class="w-3.5 h-3.5"></i>
                    <span>{{ $label }}</span>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <p class="text-[11px] font-bold text-slate-500 mb-1">Rekomendasi</p>
                <div class="p-3 rounded-r-lg border-l-4 border-[#284c9a] bg-slate-50 dark:bg-slate-800/50 text-xs text-slate-700 dark:text-slate-200">
                    {{ $penilaiankaryawan->rekomendasi }}
                </div>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-500 mb-1">Evaluasi</p>
                <div class="p-3 rounded-r-lg border-l-4 border-slate-400 bg-slate-50 dark:bg-slate-800/50 text-xs text-slate-700 dark:text-slate-200">
                    {{ $penilaiankaryawan->evaluasi }}
                </div>
            </div>
        </div>

        @if (!empty($penilaiankaryawan->approval_history))
            @php $history = json_decode($penilaiankaryawan->approval_history, true) ?? []; @endphp
            @if (count($history) > 0)
                <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                    <h6 class="text-xs font-bold text-amber-600 dark:text-amber-400 flex items-center gap-1.5 mb-2.5">
                        <i data-lucide="history" class="w-3.5 h-3.5"></i>
                        <span>Riwayat Perubahan & Approval</span>
                    </h6>
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-dashed border-slate-200 dark:border-slate-700 space-y-2.5">
                        @foreach ($history as $h)
                            @php
                                $keterangan = $h['keterangan'];
                                if (strpos($keterangan, ' oleh ') === false && !empty($h['user_name'])) {
                                    $formattedRole = singkatString($h['role'] ?? '') == 'AMH' ? 'HRD' : singkatString($h['role'] ?? '');
                                    $keterangan .= ' oleh ' . $h['user_name'] . ' (' . $formattedRole . ')';
                                }
                            @endphp
                            <div class="flex items-start gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                                    <i data-lucide="history" class="w-3.5 h-3.5"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-100">{{ $keterangan }}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        {{ formatIndo($h['date']) }} {{ date('H:i', strtotime($h['date'])) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>

    <!-- FOOTER BUTTONS -->
    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
        <a href="{{ route('penilaiankaryawan.cetak', Crypt::encrypt($penilaiankaryawan->kode_penilaian)) }}" target="_blank"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold transition inline-flex items-center gap-1.5">
            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
            <span>Cetak Detail</span>
        </a>
        <button type="button" onclick="window.closeModalPenilaian && window.closeModalPenilaian()"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition cursor-pointer">
            Tutup
        </button>
    </div>
</div>

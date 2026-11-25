@if ($kunjungan->isEmpty())
    <div class="flex flex-col items-center justify-center py-8 text-center rounded-xl border border-dashed border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40">
        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mb-2">
            <i data-lucide="map-pin-off" class="w-5 h-5"></i>
        </div>
        <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Belum ada histori kunjungan</p>
        <p class="text-[11px] text-slate-400 mt-0.5">Tidak ditemukan aktivitas check-in pelanggan pada tanggal yang dipilih.</p>
    </div>
@else
    @php
        $lokasi_cabang = !empty($cabang?->lokasi_cabang) ? explode(',', $cabang->lokasi_cabang) : [0, 0];
        $lat_start = $lokasi_cabang[0] ?? 0;
        $long_start = $lokasi_cabang[1] ?? 0;
    @endphp
    <div class="relative pl-5 space-y-3 before:content-[''] before:absolute before:left-[9px] before:top-2 before:bottom-2 before:w-px before:bg-slate-200 dark:before:bg-slate-800">
        @foreach ($kunjungan as $d)
            @php
                $durasi = hitungdurasi(date('H:i', strtotime($d->checkin_time)), date('H:i', strtotime($d->checkout_time)));
                $jarak = hitungJarak($lat_start, $long_start, $d->latitude, $d->longitude);
            @endphp
            <div class="relative p-3 rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                <span class="absolute -left-[22px] top-3.5 w-4 h-4 rounded-full bg-[#284c9a] text-white flex items-center justify-center ring-4 ring-white dark:ring-slate-900">
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                </span>

                <div class="flex flex-wrap items-center justify-between gap-2 mb-1.5">
                    <div class="flex items-center gap-1.5 min-w-0">
                        <span class="px-1.5 py-0.5 rounded bg-[#284c9a]/10 text-[#284c9a] dark:text-blue-400 text-[10px] font-bold font-mono-num shrink-0">
                            {{ $d->kode_pelanggan }}
                        </span>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate">
                            {{ textUpperCase($d->nama_pelanggan) }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2 text-[11px] font-bold font-mono-num shrink-0">
                        <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                            <i data-lucide="log-in" class="w-3 h-3"></i>
                            {{ date('H:i', strtotime($d->checkin_time)) }}
                        </span>
                        <span class="text-slate-300 dark:text-slate-700">-</span>
                        <span class="inline-flex items-center gap-1 text-rose-600 dark:text-rose-400">
                            <i data-lucide="log-out" class="w-3 h-3"></i>
                            {{ date('H:i', strtotime($d->checkout_time)) }}
                        </span>
                    </div>
                </div>

                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed mb-2">
                    {{ textCamelCase($d->alamat_pelanggan) }}
                </p>

                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 text-[10.5px] font-semibold font-mono-num">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        Durasi: {{ $durasi['jam'] }}j {{ $durasi['menit'] }}m
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[10.5px] font-semibold font-mono-num">
                        <i data-lucide="navigation" class="w-3 h-3"></i>
                        Jarak: {{ formatAngkaDesimal($jarak / 1000) }} km
                    </span>
                </div>
            </div>
            @php
                $lat_start = $d->latitude;
                $long_start = $d->longitude;
            @endphp
        @endforeach
    </div>
@endif

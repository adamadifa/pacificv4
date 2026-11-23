<div class="space-y-3.5">
    
    <!-- EXECUTIVE SUMMARY STRIP (STANDAR V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/50 p-3 sm:p-3.5 shadow-2xs">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 divide-y md:divide-y-0 divide-slate-200/80 dark:divide-slate-800">
            
            <!-- Nama Kendaraan -->
            <div class="space-y-0.5">
                <div class="flex items-center gap-1.5 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                    <i data-lucide="truck" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                    <span>Kendaraan</span>
                </div>
                <div class="text-sm font-black text-[#284c9a] dark:text-blue-400 font-mono-num">
                    {{ $device_name }}
                </div>
            </div>

            <!-- Tanggal Perjalanan -->
            <div class="space-y-0.5 pt-2 md:pt-0">
                <div class="flex items-center gap-1.5 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                    <span>Tanggal</span>
                </div>
                <div class="text-sm font-bold text-slate-800 dark:text-slate-100 font-mono-num">
                    {{ formatIndo($tanggal) }}
                </div>
            </div>

            <!-- Total Jarak Tempuh -->
            <div class="space-y-0.5 pt-2 md:pt-0">
                <div class="flex items-center gap-1.5 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                    <i data-lucide="milestone" class="w-3.5 h-3.5 text-emerald-500"></i>
                    <span>Total Jarak</span>
                </div>
                <div class="text-sm font-black text-emerald-600 dark:text-emerald-400 font-mono-num">
                    {{ number_format($trips->sum('mileage'), 2, ',', '.') }} <span class="text-xs font-semibold text-slate-400">km</span>
                </div>
            </div>

            <!-- Total Konsumsi BBM -->
            <div class="space-y-0.5 pt-2 md:pt-0">
                <div class="flex items-center gap-1.5 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                    <i data-lucide="fuel" class="w-3.5 h-3.5 text-amber-500"></i>
                    <span>Total BBM</span>
                </div>
                <div class="text-sm font-black text-amber-600 dark:text-amber-400 font-mono-num">
                    {{ number_format($trips->sum('fuel_consumption'), 2, ',', '.') }} <span class="text-xs font-semibold text-slate-400">Liter</span>
                </div>
            </div>

        </div>
    </div>

    <!-- TABEL LOG RINCIAN PERJALANAN (KOMPAK ~36PX, 2-BARIS THEAD) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto max-h-[50vh]" style="max-height: 55vh;">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10 shadow-xs">
                    <!-- Row 1: Header Strip Pacific Navy (Colspan 11) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="11" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="route" class="w-4 h-4 text-blue-200"></i>
                                    <span class="font-bold text-xs uppercase tracking-wider text-white">Log Riwayat Perjalanan Harian</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-white/10 text-white/90 uppercase tracking-wider font-mono-num">
                                    TOTAL: {{ $trips->count() }} LOG
                                </span>
                            </div>
                        </th>
                    </tr>

                    <!-- Row 2: Header Kolom Deep Navy -->
                    <tr class="bg-[#1e3a78] text-white border-t border-white/10">
                        <th class="py-2 px-3 text-center text-[11px] font-bold uppercase tracking-wider text-white/90 w-10">NO</th>
                        <th class="py-2 px-3 text-center text-[11px] font-bold uppercase tracking-wider text-white/90">START TIME</th>
                        <th class="py-2 px-3 text-left text-[11px] font-bold uppercase tracking-wider text-white/90 min-w-[180px]">START LOCATION</th>
                        <th class="py-2 px-3 text-center text-[11px] font-bold uppercase tracking-wider text-white/90">END TIME</th>
                        <th class="py-2 px-3 text-left text-[11px] font-bold uppercase tracking-wider text-white/90 min-w-[180px]">END LOCATION</th>
                        <th class="py-2 px-3 text-right text-[11px] font-bold uppercase tracking-wider text-white/90">DISTANCE (km)</th>
                        <th class="py-2 px-3 text-center text-[11px] font-bold uppercase tracking-wider text-white/90">DURATION</th>
                        <th class="py-2 px-3 text-right text-[11px] font-bold uppercase tracking-wider text-white/90">AVG SPEED</th>
                        <th class="py-2 px-3 text-right text-[11px] font-bold uppercase tracking-wider text-white/90">MAX SPEED</th>
                        <th class="py-2 px-3 text-right text-[11px] font-bold uppercase tracking-wider text-white/90">FUEL/100</th>
                        <th class="py-2 px-3 text-right text-[11px] font-bold uppercase tracking-wider text-white/90">FUEL USED</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($trips as $t)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 text-center font-bold text-[#284c9a] dark:text-blue-400 font-mono-num">{{ $t->start_time }}</td>
                            <td class="py-1.5 px-3 text-left max-w-[220px] truncate" title="{{ $t->start_location }}">
                                <span class="text-slate-700 dark:text-slate-300">{{ Str::limit($t->start_location, 50) }}</span>
                            </td>
                            <td class="py-1.5 px-3 text-center font-bold text-slate-600 dark:text-slate-300 font-mono-num">{{ $t->end_time }}</td>
                            <td class="py-1.5 px-3 text-left max-w-[220px] truncate" title="{{ $t->end_location }}">
                                <span class="text-slate-700 dark:text-slate-300">{{ Str::limit($t->end_location, 50) }}</span>
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">
                                {{ number_format($t->mileage, 3, ',', '.') }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-300">
                                {{ $t->travel_time }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-200">
                                {{ number_format($t->average_speed, 1, ',', '.') }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-rose-600 dark:text-rose-400">
                                {{ number_format($t->max_speed, 1, ',', '.') }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-600 dark:text-slate-300">
                                {{ $t->fuel_ratio > 0 ? number_format($t->fuel_ratio, 1, ',', '.') . ' L' : '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num text-emerald-600 dark:text-emerald-400 font-semibold">
                                {{ $t->fuel_consumption > 0 ? number_format($t->fuel_consumption, 1, ',', '.') . ' L' : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-8 text-center text-slate-400">
                                Tidak ada log rincian perjalanan untuk hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL FOOTER -->
    <div class="flex items-center justify-end pt-1">
        <button type="button" class="btn-close-modal h-[32px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Tutup Detail
        </button>
    </div>

</div>

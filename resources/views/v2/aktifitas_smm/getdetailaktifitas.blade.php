@php
    $lokasi = !empty($user->lokasi_cabang) ? explode(',', $user->lokasi_cabang) : [0, 0];
    $lat_start = '';
    $long_start = '';
@endphp

<div class="space-y-4">
    <!-- User Info Card Header -->
    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-10 h-10 rounded-xl bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center border border-brand-500/20 shrink-0 font-black text-sm uppercase">
                {{ substr($user->name ?? 'U', 0, 2) }}
            </div>
            <div class="min-w-0">
                <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate leading-tight">
                    {{ $user->name ?? '-' }}
                </h4>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700 text-slate-600 dark:text-slate-300">
                        <i data-lucide="building-2" class="w-3 h-3 text-slate-400"></i>
                        Cabang {{ $user->kode_cabang ?? '-' }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200/60 dark:border-emerald-800/60 text-emerald-700 dark:text-emerald-400 font-mono-num">
                        {{ count($aktifitas) }} Kunjungan
                    </span>
                </div>
            </div>
        </div>

        <div class="text-right">
            <span class="text-[11px] font-semibold text-slate-400 block">Total Log</span>
            <span class="text-sm font-black font-mono-num text-slate-800 dark:text-slate-200">{{ count($aktifitas) }} Titik</span>
        </div>
    </div>

    <!-- Activity Log Table -->
    <div class="overflow-x-auto rounded-lg border border-slate-200/80 dark:border-slate-800">
        <table class="w-full text-xs text-left border-collapse">
            <thead class="bg-[#284c9a] text-white text-[11px]">
                <tr>
                    <th class="px-3 py-2 text-center w-12 font-bold uppercase tracking-wider border-r border-white/15">No.</th>
                    <th class="px-3 py-2 text-center w-24 font-bold uppercase tracking-wider border-r border-white/15">Waktu</th>
                    <th class="px-3 py-2 font-bold uppercase tracking-wider border-r border-white/15">Keterangan</th>
                    <th class="px-3 py-2 text-center w-24 font-bold uppercase tracking-wider border-r border-white/15">Foto</th>
                    <th class="px-3 py-2 text-right w-28 font-bold uppercase tracking-wider">Jarak</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                @forelse ($aktifitas as $d)
                    @php
                        if ($loop->first) {
                            $jarak = isset($lokasi[0], $lokasi[1]) && !empty($d->latitude) && !empty($d->longitude)
                                ? hitungjarak($lokasi[0], $lokasi[1], $d->latitude, $d->longitude)
                                : 0;
                            $totaljarak = $jarak / 1000;
                        } else {
                            $jarak = !empty($lat_start) && !empty($long_start) && !empty($d->latitude) && !empty($d->longitude)
                                ? hitungjarak($lat_start, $long_start, $d->latitude, $d->longitude)
                                : 0;
                            $totaljarak = $jarak / 1000;
                        }
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-3 py-2.5 text-center font-mono-num font-bold text-slate-500 border-r border-slate-100 dark:border-slate-800">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-3 py-2.5 text-center border-r border-slate-100 dark:border-slate-800">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md font-mono-num font-bold text-[11px] bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200">
                                <i data-lucide="clock" class="w-3 h-3 text-slate-400"></i>
                                {{ !empty($d->created_at) ? date('H:i', strtotime($d->created_at)) : '-' }}
                            </span>
                        </td>
                        <td class="px-3 py-2.5 border-r border-slate-100 dark:border-slate-800">
                            <div class="font-medium text-slate-800 dark:text-slate-100 leading-snug">
                                {{ $d->keterangan ?? '-' }}
                            </div>
                            @if (!empty($d->latitude) && !empty($d->longitude))
                                <div class="text-[10px] text-slate-400 font-mono-num mt-0.5 flex items-center gap-1">
                                    <i data-lucide="map-pin" class="w-2.5 h-2.5 text-slate-400"></i>
                                    <span>{{ $d->latitude }}, {{ $d->longitude }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-3 py-2.5 text-center border-r border-slate-100 dark:border-slate-800">
                            @if (!empty($d->foto) && Storage::disk('public')->exists('/uploads/aktifitas_smm/' . $d->foto))
                                <a href="{{ getfotoAktifitias($d->foto) }}" target="_blank" class="inline-block relative group" title="Lihat foto penuh">
                                    <img src="{{ getfotoAktifitias($d->foto) }}" alt="Foto Aktifitas" class="w-10 h-10 rounded-lg object-cover border border-slate-200 dark:border-slate-700 shadow-2xs group-hover:scale-110 transition-transform">
                                    <span class="absolute inset-0 bg-black/30 rounded-lg opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity text-white">
                                        <i data-lucide="maximize-2" class="w-3.5 h-3.5"></i>
                                    </span>
                                </a>
                            @else
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-400">
                                    <i data-lucide="image-off" class="w-4 h-4"></i>
                                </span>
                            @endif
                        </td>
                        <td class="px-3 py-2.5 text-right font-mono-num">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md font-bold text-[11px] bg-brand-50 dark:bg-brand-950/50 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
                                <i data-lucide="navigation" class="w-2.5 h-2.5 text-brand-500"></i>
                                {{ formatAngka($totaljarak) }} Km
                            </span>
                        </td>
                    </tr>
                    @php
                        $lat_start = $d->latitude;
                        $long_start = $d->longitude;
                    @endphp
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">
                            <i data-lucide="inbox" class="w-8 h-8 mx-auto text-slate-300 dark:text-slate-600 mb-1.5"></i>
                            <div class="text-xs font-semibold">Tidak ada aktifitas tercatat pada tanggal ini</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="space-y-3">
    <!-- TAB NAVIGATION -->
    <div class="flex flex-wrap gap-1.5 border-b border-slate-200 dark:border-slate-800 pb-2.5" id="machineTabs">
        <button type="button" data-target="#mesin1"
            class="machine-tab-btn active px-3 py-1.5 rounded-lg bg-[#284c9a] text-white text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
            <i data-lucide="cloud" class="w-3.5 h-3.5"></i>
            <span>Mesin 1</span>
        </button>
        <button type="button" data-target="#mesin2"
            class="machine-tab-btn px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
            <i data-lucide="cloud" class="w-3.5 h-3.5"></i>
            <span>Mesin 2</span>
        </button>
        <button type="button" data-target="#adms"
            class="machine-tab-btn px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
            <i data-lucide="laptop" class="w-3.5 h-3.5"></i>
            <span>Log ADMS</span>
        </button>
        <button type="button" data-target="#logerror"
            class="machine-tab-btn px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-rose-500"></i>
            <span>Log Error Mobile</span>
        </button>
    </div>

    <!-- TAB PANES -->
    <!-- Mesin 1 -->
    <div id="mesin1" class="machine-pane rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center gap-2.5 text-white">
            <span class="px-2 py-0.5 rounded bg-white text-[#284c9a] text-[10px] font-black uppercase tracking-wider">CLOUD ID</span>
            <span class="text-xs font-mono-num font-bold">C2609075E3170B2C</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-2 px-3">PIN</th>
                        <th class="py-2 px-3">STATUS</th>
                        <th class="py-2 px-3">WAKTU SCAN</th>
                        <th class="py-2 px-3 text-center w-24">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($filtered_array as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $d->pin }}</td>
                            <td class="py-1.5 px-3">
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $d->status_scan % 2 == 0 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300' }}">
                                    {{ $d->status_scan % 2 == 0 ? 'IN' : 'OUT' }}
                                </span>
                            </td>
                            <td class="py-1.5 px-3 font-mono-num">
                                <span class="font-bold text-slate-800 dark:text-slate-100">{{ date('H:i:s', strtotime($d->scan_date)) }}</span>
                                <span class="text-slate-400 ml-1.5 text-[11px]">{{ date('d/m/y', strtotime($d->scan_date)) }}</span>
                            </td>
                            <td class="py-1.5 px-3">
                                <div class="flex items-center justify-center gap-1">
                                    <form method="POST" action="{{ route('presensi.updatefrommachine', [Crypt::encrypt($d->pin), 0]) }}" class="inline-flex m-0 p-0">
                                        @csrf
                                        <input type="hidden" name="scan_date" value="{{ date('Y-m-d H:i:s', strtotime($d->scan_date)) }}">
                                        <button type="submit" class="w-6 h-6 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center transition cursor-pointer" title="Set Masuk">
                                            <i data-lucide="log-in" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('presensi.updatefrommachine', [Crypt::encrypt($d->pin), 1]) }}" class="inline-flex m-0 p-0">
                                        @csrf
                                        <input type="hidden" name="scan_date" value="{{ date('Y-m-d H:i:s', strtotime($d->scan_date)) }}">
                                        <button type="submit" class="w-6 h-6 rounded-md bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center transition cursor-pointer" title="Set Pulang">
                                            <i data-lucide="log-out" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 px-4 text-center text-xs font-semibold text-slate-400">Data tidak ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mesin 2 -->
    <div id="mesin2" class="machine-pane hidden rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center gap-2.5 text-white">
            <span class="px-2 py-0.5 rounded bg-white text-[#284c9a] text-[10px] font-black uppercase tracking-wider">CLOUD ID</span>
            <span class="text-xs font-mono-num font-bold">C268909557211236</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-2 px-3">PIN</th>
                        <th class="py-2 px-3">STATUS</th>
                        <th class="py-2 px-3">WAKTU SCAN</th>
                        <th class="py-2 px-3 text-center w-24">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($filtered_array_2 as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $d->pin }}</td>
                            <td class="py-1.5 px-3">
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $d->status_scan % 2 == 0 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300' }}">
                                    {{ $d->status_scan % 2 == 0 ? 'IN' : 'OUT' }}
                                </span>
                            </td>
                            <td class="py-1.5 px-3 font-mono-num">
                                <span class="font-bold text-slate-800 dark:text-slate-100">{{ date('H:i:s', strtotime($d->scan_date)) }}</span>
                                <span class="text-slate-400 ml-1.5 text-[11px]">{{ date('d/m/y', strtotime($d->scan_date)) }}</span>
                            </td>
                            <td class="py-1.5 px-3">
                                <div class="flex items-center justify-center gap-1">
                                    <form method="POST" action="{{ route('presensi.updatefrommachine', [Crypt::encrypt($d->pin), 0]) }}" class="inline-flex m-0 p-0">
                                        @csrf
                                        <input type="hidden" name="scan_date" value="{{ date('Y-m-d H:i:s', strtotime($d->scan_date)) }}">
                                        <button type="submit" class="w-6 h-6 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center transition cursor-pointer" title="Set Masuk">
                                            <i data-lucide="log-in" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('presensi.updatefrommachine', [Crypt::encrypt($d->pin), 1]) }}" class="inline-flex m-0 p-0">
                                        @csrf
                                        <input type="hidden" name="scan_date" value="{{ date('Y-m-d H:i:s', strtotime($d->scan_date)) }}">
                                        <button type="submit" class="w-6 h-6 rounded-md bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center transition cursor-pointer" title="Set Pulang">
                                            <i data-lucide="log-out" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 px-4 text-center text-xs font-semibold text-slate-400">Data tidak ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Log ADMS -->
    <div id="adms" class="machine-pane hidden rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center gap-2.5 text-white">
            <span class="px-2 py-0.5 rounded bg-white text-[#284c9a] text-[10px] font-black uppercase tracking-wider">ADMS LIVE LOG</span>
            <span class="text-xs font-medium text-white/90">Data real-time dari mesin terdaftar</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-2 px-3">PIN</th>
                        <th class="py-2 px-3">MESIN</th>
                        <th class="py-2 px-3">SCAN</th>
                        <th class="py-2 px-3">KETERANGAN</th>
                        <th class="py-2 px-3 text-center w-24">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($log as $l)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $l->pin }}</td>
                            <td class="py-1.5 px-3">
                                <span class="font-bold text-[#284c9a] dark:text-blue-400">{{ $l->nama_mesin ?: 'Mesin ADMS' }}</span>
                                <span class="text-[10.5px] font-mono-num text-slate-400 ml-1">{{ $l->id_mesin }}</span>
                            </td>
                            <td class="py-1.5 px-3 font-mono-num">
                                <span class="font-bold text-slate-800 dark:text-slate-100">{{ date('H:i:s', strtotime($l->jam_absen)) }}</span>
                                <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] font-bold {{ $l->status_scan % 2 == 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                    {{ $l->status_scan % 2 == 0 ? 'IN' : 'OUT' }}
                                </span>
                            </td>
                            <td class="py-1.5 px-3">
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $l->status == 1 ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }}">
                                    {{ $l->keterangan }}
                                </span>
                            </td>
                            <td class="py-1.5 px-3">
                                <div class="flex items-center justify-center gap-1">
                                    <form method="POST" action="{{ route('presensi.updatefrommachine', [Crypt::encrypt($l->pin), 0]) }}" class="inline-flex m-0 p-0">
                                        @csrf
                                        <input type="hidden" name="scan_date" value="{{ date('Y-m-d H:i:s', strtotime($l->jam_absen)) }}">
                                        <button type="submit" class="w-6 h-6 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center transition cursor-pointer" title="Set Masuk">
                                            <i data-lucide="log-in" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('presensi.updatefrommachine', [Crypt::encrypt($l->pin), 1]) }}" class="inline-flex m-0 p-0">
                                        @csrf
                                        <input type="hidden" name="scan_date" value="{{ date('Y-m-d H:i:s', strtotime($l->jam_absen)) }}">
                                        <button type="submit" class="w-6 h-6 rounded-md bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center transition cursor-pointer" title="Set Pulang">
                                            <i data-lucide="log-out" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 px-4 text-center text-xs font-semibold text-slate-400">Data tidak ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Log Error Mobile -->
    <div id="logerror" class="machine-pane hidden rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
        <div class="px-3.5 py-2 bg-rose-600 flex items-center gap-2.5 text-white">
            <span class="px-2 py-0.5 rounded bg-white text-rose-600 text-[10px] font-black uppercase tracking-wider">MOBILE APP ERRORS</span>
            <span class="text-xs font-medium text-white/90">Log kegagalan presensi dari aplikasi mobile</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-rose-800 text-white/90 text-[11px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-2 px-3">WAKTU</th>
                        <th class="py-2 px-3">STATUS</th>
                        <th class="py-2 px-3">PESAN ERROR</th>
                        <th class="py-2 px-3 text-center w-20">PAYLOAD</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($log_error as $le)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ date('H:i:s', strtotime($le->jam)) }}</td>
                            <td class="py-1.5 px-3">
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $le->status_presensi == 'masuk' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                    {{ strtoupper($le->status_presensi) }}
                                </span>
                            </td>
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-200 max-w-[300px] whitespace-normal">{{ $le->error_message }}</td>
                            <td class="py-1.5 px-3 text-center">
                                <button type="button" payload="{{ $le->payload }}"
                                    class="btnShowPayloadModal w-6 h-6 rounded-md bg-sky-50 hover:bg-sky-100 text-sky-600 inline-flex items-center justify-center transition cursor-pointer"
                                    title="Lihat Payload">
                                    <i data-lucide="eye" class="w-3 h-3"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 px-4 text-center text-xs font-semibold text-slate-400">Tidak ada log error untuk hari ini</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $('.machine-tab-btn').on('click', function() {
            const target = $(this).data('target');
            $('.machine-tab-btn')
                .removeClass('active bg-[#284c9a] text-white')
                .addClass('bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300');
            $(this)
                .addClass('active bg-[#284c9a] text-white')
                .removeClass('bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300');

            $('.machine-pane').addClass('hidden');
            $(target).removeClass('hidden');
        });

        $('.btnShowPayloadModal').on('click', function(e) {
            e.preventDefault();
            const payload = $(this).attr('payload');
            let formatted = payload;
            try {
                formatted = JSON.stringify(JSON.parse(payload), null, 4);
            } catch (err) {}
            Swal.fire({
                title: 'Detail Payload',
                html: `<pre class="text-left p-3 bg-slate-50 rounded-lg border text-[11px] whitespace-pre-wrap break-words">${formatted}</pre>`,
                width: '600px',
                confirmButtonColor: '#284c9a',
                confirmButtonText: 'Tutup'
            });
        });
    });
</script>

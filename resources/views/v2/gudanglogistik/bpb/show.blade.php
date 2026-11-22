@php
    $totalJumlah = 0;
    $totalDiserahkan = 0;
    $totalSisa = 0;
    foreach ($detail as $d) {
        $sudahDiserahkan = $diserahkanTotal[$d->kode_barang] ?? 0;
        $sisa = $d->jumlah - $sudahDiserahkan;
        $totalJumlah += $d->jumlah;
        $totalDiserahkan += $sudahDiserahkan;
        $totalSisa += $sisa;
    }
    
    // Overall completion percentage
    $percentComplete = $totalJumlah > 0 ? round(($totalDiserahkan / $totalJumlah) * 100) : 0;
    
    // BPB Handover status
    if ($totalSisa <= 0) {
        $statusBadgeClass = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800';
        $statusText = 'Selesai';
    } elseif ($totalDiserahkan > 0) {
        $statusBadgeClass = 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 border border-amber-200 dark:border-amber-800';
        $statusText = 'Sebagian';
    } else {
        $statusBadgeClass = 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700';
        $statusText = 'Belum Diserahkan';
    }

    $numFormat = function($val) {
        return number_format((float)($val ?? 0), 2, ',', '.');
    };
@endphp

<div class="space-y-5">
    <form action="{{ route('bpb.serahterimabpbstore') }}" method="POST" id="formSerahTerima" autocomplete="off" class="space-y-4">
        @csrf

    <!-- Hidden wajib -->
    <input type="hidden" name="kode_dept" value="{{ $bpb->kode_dept }}">
    <input type="hidden" name="kode_cabang" value="{{ $bpb->kode_cabang }}">
    <input type="hidden" name="no_ref" value="{{ $bpb->no_bpb }}">

    <!-- 1. INFORMASI BPB STRIP -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 p-3 sm:p-3.5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 pb-2.5 border-b border-slate-200/60 dark:border-slate-700/60">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold font-mono-num bg-white dark:bg-slate-900 text-[#284c9a] dark:text-blue-400 border border-slate-200 dark:border-slate-700 shadow-2xs">
                    <i data-lucide="barcode" class="w-3.5 h-3.5 text-[#284c9a]"></i>
                    <span>{{ $bpb->no_bpb }}</span>
                </span>
                <span class="text-slate-300 dark:text-slate-600 hidden sm:inline">•</span>
                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-1">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span>{{ DateToIndo($bpb->tanggal) }}</span>
                </span>
                <span class="text-slate-300 dark:text-slate-600 hidden sm:inline">•</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1">
                    <i data-lucide="user" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span>Pengaju: <strong class="text-slate-800 dark:text-slate-200">{{ $bpb->nama_user }}</strong></span>
                </span>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700">
                    {{ $bpb->nama_cabang }}
                </span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700">
                    {{ $bpb->nama_dept }}
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10.5px] font-bold {{ $statusBadgeClass }}">
                    {{ $statusText }}
                </span>
            </div>
        </div>

        <!-- 2. STATISTIC & METRIC CARDS STRIP (STANDAR SECTION 4 GEMINI.MD) -->
        <div class="mt-2.5 rounded-lg overflow-hidden bg-[#284c9a] shadow-xs relative">
            <!-- Background Ornaments -->
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/60 to-transparent pointer-events-none"></div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/[0.04] rounded-full blur-xl pointer-events-none"></div>

            <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-white/15 relative z-10">
                <!-- Total Order -->
                <div class="p-2.5 sm:p-3 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Total Order</span>
                        <span class="text-base sm:text-lg font-black text-white font-mono-num block leading-tight">
                            {{ $numFormat($totalJumlah) }}
                        </span>
                        <span class="text-[10px] text-white/55 block">Jumlah seluruh barang</span>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-white/10 text-white flex items-center justify-center shrink-0">
                        <i data-lucide="package" class="w-4 h-4"></i>
                    </div>
                </div>

                <!-- Diserahkan -->
                <div class="p-2.5 sm:p-3 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Diserahkan</span>
                            <span class="px-1.5 py-0.2 rounded text-[9.5px] font-bold bg-emerald-500/30 text-emerald-200 font-mono-num">
                                {{ $percentComplete }}%
                            </span>
                        </div>
                        <span class="text-base sm:text-lg font-black text-emerald-300 font-mono-num block leading-tight">
                            {{ $numFormat($totalDiserahkan) }}
                        </span>
                        <span class="text-[10px] text-white/55 block">Sudah diterima pengaju</span>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-300 flex items-center justify-center shrink-0">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                    </div>
                </div>

                <!-- Sisa -->
                <div class="p-2.5 sm:p-3 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Sisa Permintaan</span>
                        <span class="text-base sm:text-lg font-black {{ $totalSisa > 0 ? 'text-amber-300' : 'text-white' }} font-mono-num block leading-tight">
                            {{ $numFormat($totalSisa) }}
                        </span>
                        <span class="text-[10px] text-white/55 block">{{ $totalSisa > 0 ? 'Belum diserahkan' : 'Selesai seluruhnya' }}</span>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-white/10 text-white flex items-center justify-center shrink-0">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. DETAIL BARANG PERMINTAAN TABEL (STANDAR DUA BARIS THEAD V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[350px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <!-- Row 1: Title Strip (colspan=10/9) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="{{ Auth::user()->id == '67' ? 10 : 9 }}" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="boxes" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Detail Barang Permintaan</span>
                                </div>
                                <span class="text-[11px] text-white/75 font-mono-num">{{ count($detail) }} Item Barang</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 text-center w-10 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2 px-3 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA BARANG</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-20">ORDER</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-20">DITERIMA</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-20">SISA</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-28">PROGRES</th>
                        @if (Auth::user()->id == '67')
                            <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-24">SERAHKAN</th>
                        @endif
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-16">SATUAN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($detail as $d)
                        @php
                            $sudahDiserahkan = $diserahkanTotal[$d->kode_barang] ?? 0;
                            $sisa = $d->jumlah - $sudahDiserahkan;
                            $itemProgress = $d->jumlah > 0 ? round(($sudahDiserahkan / $d->jumlah) * 100) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50">
                            <td class="py-1.5 px-3 text-center text-slate-400 font-mono-num">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 text-center font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $d->kode_barang }}</td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">{{ strtoupper($d->nama_barang) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-800 dark:text-slate-100">{{ $numFormat($d->jumlah) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold {{ $sudahDiserahkan > 0 ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ $numFormat($sudahDiserahkan) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold {{ $sisa > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                {{ $numFormat($sisa) }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <div class="w-16 bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-emerald-600 h-1.5 rounded-full" style="width: {{ $itemProgress }}%"></div>
                                    </div>
                                    <span class="text-[10.5px] font-mono-num font-bold {{ $itemProgress == 100 ? 'text-emerald-600' : 'text-slate-500' }}">
                                        {{ $itemProgress }}%
                                    </span>
                                </div>
                            </td>
                            @if (Auth::user()->id == '67')
                                <td class="py-1 px-2 text-right">
                                    <input type="number" name="diserahkan[{{ $d->kode_barang }}]"
                                        class="diserahkan w-20 h-7 text-right text-xs font-bold font-mono-num rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-2 focus:ring-1 focus:ring-emerald-500 focus:outline-hidden"
                                        data-sisa="{{ $sisa }}" min="0" placeholder="0"
                                        {{ $sisa <= 0 || $bpb->approve_gudang == 0 ? 'readonly disabled' : '' }}>
                                </td>
                            @endif
                            <td class="py-1.5 px-3 text-center text-slate-600 dark:text-slate-400 text-[11px] font-semibold">{{ strtoupper($d->satuan ?? '') }}</td>
                            <td class="py-1.5 px-3 text-slate-500 dark:text-slate-400 text-xs">{{ $d->keterangan ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->id == '67' ? 10 : 9 }}" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1">
                                    <i data-lucide="check-circle" class="w-8 h-8 text-emerald-500"></i>
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Tidak ada rincian barang</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 4. ACTION BAR PENYERAHAN BARANG (HANYA UNTUK USER 67 & BELUM SELESAI) -->
    @if (Auth::user()->id == '67' && $totalSisa > 0 && $bpb->approve_gudang == '1')
        <div class="rounded-xl border border-emerald-200/80 dark:border-emerald-800 bg-emerald-50/40 dark:bg-emerald-950/20 p-3.5 shadow-2xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 flex items-center justify-center shrink-0">
                        <i data-lucide="package-check" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Konfirmasi Penyerahan Barang</h6>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Tentukan tanggal serah terima dan simpan data penyerahan barang logistik.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <div class="relative w-44">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="tanggal_diserahkan" value="{{ date('Y-m-d') }}" readonly
                            class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition cursor-pointer">
                    </div>

                    <button type="submit"
                        class="h-[36px] inline-flex items-center justify-center gap-1.5 px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>Simpan Serah Terima</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</form>

    <!-- 5. RIWAYAT SERAH TERIMA BPB -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs space-y-3.5 bg-white dark:bg-slate-900 p-4 sm:p-5">
        <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
            <i data-lucide="history" class="w-4 h-4 text-[#284c9a]"></i>
            <h5 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-100">Riwayat Serah Terima BPB</h5>
        </div>

    @forelse ($serahTerima as $s)
        <div class="rounded-lg border border-slate-200/70 dark:border-slate-800 overflow-hidden shadow-2xs">
            <div class="px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200/70 dark:border-slate-800 flex flex-wrap items-center justify-between gap-2 text-xs">
                <div class="flex items-center gap-2">
                    <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $s->no_bukti }}</span>
                    <span class="text-slate-300 dark:text-slate-700">|</span>
                    <span class="text-slate-500 font-semibold">{{ DateToIndo($s->tanggal) }}</span>
                </div>

                <div>
                    @if ($s->diterima == 0)
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                <span>Menunggu Konfirmasi</span>
                            </span>
                            <select class="terimaSurat h-7 px-2 text-[11px] font-bold rounded-lg border border-[#284c9a] bg-white text-[#284c9a] cursor-pointer"
                                data-no_bukti="{{ $s->no_bukti }}">
                                <option value="">-- Konfirmasi --</option>
                                <option value="1" @selected($s->diterima == 1)>Terima</option>
                            </select>
                        </div>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <i data-lucide="check-circle" class="w-3 h-3"></i>
                            <span>Sudah Diterima</span>
                        </span>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                    <thead class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[11px]">
                        <tr>
                            <th class="py-1.5 px-3 text-center w-10 font-bold">NO</th>
                            <th class="py-1.5 px-3 text-center w-24 font-bold">KODE</th>
                            <th class="py-1.5 px-3 font-bold">NAMA BARANG</th>
                            <th class="py-1.5 px-3 text-right font-bold w-24">JUMLAH</th>
                            <th class="py-1.5 px-3 text-center font-bold w-16">SATUAN</th>
                            <th class="py-1.5 px-3 font-bold">KETERANGAN</th>
                            @if ($s->diterima == 0)
                                <th class="py-1.5 px-3 text-center font-bold w-14">AKSI</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                        @foreach ($historyDetail[$s->no_bukti] ?? [] as $h)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50">
                                <td class="py-1.5 px-3 text-center text-slate-400 font-mono-num">{{ $loop->iteration }}</td>
                                <td class="py-1.5 px-3 text-center font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $h->kode_barang }}</td>
                                <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">{{ strtoupper($h->nama_barang) }}</td>
                                <td class="py-1 px-2 text-right">
                                    <input type="text" class="editJumlah w-20 h-7 text-right text-xs font-bold font-mono-num rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-2 focus:ring-1 focus:ring-[#284c9a] focus:outline-hidden"
                                        value="{{ $h->jumlah }}" data-no_bukti="{{ $h->no_bukti }}"
                                        data-kode_barang="{{ $h->kode_barang }}"
                                        {{ $s->diterima != 0 || $totalSisa <= 0 ? 'readonly' : '' }}>
                                </td>
                                <td class="py-1.5 px-3 text-center text-slate-500 text-[11px]">{{ strtoupper($h->satuan ?? '') }}</td>
                                <td class="py-1 px-2">
                                    <input type="text" class="editKeterangan w-full h-7 text-xs rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-2 focus:ring-1 focus:ring-[#284c9a] focus:outline-hidden"
                                        value="{{ $h->keterangan }}" data-no_bukti="{{ $h->no_bukti }}"
                                        data-kode_barang="{{ $h->kode_barang }}" placeholder="Keterangan..."
                                        {{ $s->diterima != 0 ? 'readonly' : '' }}>
                                </td>
                                @if ($s->diterima == 0)
                                    <td class="py-1.5 px-3 text-center">
                                        <button type="button" class="deleteHistoryBtn w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 dark:text-rose-400 inline-flex items-center justify-center transition shadow-2xs cursor-pointer border-0 p-0"
                                            data-no_bukti="{{ $h->no_bukti }}"
                                            data-kode_barang="{{ $h->kode_barang }}"
                                            title="Hapus Serah Terima">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="rounded-xl border border-dashed border-slate-200 dark:border-slate-800 p-6 text-center text-slate-400 bg-slate-50/50 dark:bg-slate-900/50">
            <i data-lucide="history" class="w-7 h-7 text-slate-300 dark:text-slate-600 mx-auto mb-1.5"></i>
            <p class="text-xs font-bold text-slate-600 dark:text-slate-400">Belum Ada Riwayat Serah Terima</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Barang belum pernah diserahkan untuk pengajuan BPB ini.</p>
        </div>
    @endforelse
    </div>
</div>

<script>
    $(document).ready(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }]
        });

        $(".editJumlah").on("change", function() {
            let jumlah = $(this).val();
            let no_bukti = $(this).data("no_bukti");
            let kode_barang = $(this).data("kode_barang");

            $.post("{{ route('bpb.updateSerahTerima') }}", {
                _token: "{{ csrf_token() }}",
                no_bukti: no_bukti,
                kode_barang: kode_barang,
                jumlah: jumlah
            }, function(resp) {
                Swal.fire({
                    icon: "success",
                    title: "Jumlah diperbarui",
                    timer: 900,
                    showConfirmButton: false
                });
            });
        });

        $(".editKeterangan").on("change", function() {
            let keterangan = $(this).val();
            let no_bukti = $(this).data("no_bukti");
            let kode_barang = $(this).data("kode_barang");

            $.post("{{ route('bpb.updateSerahTerima') }}", {
                _token: "{{ csrf_token() }}",
                no_bukti: no_bukti,
                kode_barang: kode_barang,
                keterangan: keterangan
            }, function(resp) {
                Swal.fire({
                    icon: "success",
                    title: "Keterangan diperbarui",
                    timer: 900,
                    showConfirmButton: false
                });
            });
        });

        $(".terimaSurat").on("change", function() {
            let no_bukti = $(this).data("no_bukti");
            $.post("{{ route('bpb.updateSerahTerima') }}", {
                _token: "{{ csrf_token() }}",
                no_bukti: no_bukti,
                diterima: 1
            }, function(resp) {
                Swal.fire({
                    icon: "success",
                    title: "Status serah terima diperbarui",
                    timer: 900,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            });
        });

        $(".deleteHistoryBtn").on("click", function() {
            let no_bukti = $(this).data("no_bukti");
            let kode_barang = $(this).data("kode_barang");

            Swal.fire({
                title: "Hapus Data?",
                text: "Data serah terima akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e11d48",
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("{{ route('bpb.deleteSerahTerima') }}", {
                        _token: "{{ csrf_token() }}",
                        no_bukti: no_bukti,
                        kode_barang: kode_barang
                    }, function(res) {
                        location.reload();
                    });
                }
            });
        });

        $(".diserahkan").on("input", function() {
            let sisa = parseFloat($(this).data("sisa")) || 0;
            let val = parseFloat($(this).val()) || 0;

            if (val > sisa) {
                Swal.fire({
                    icon: "error",
                    title: "Tidak Valid!",
                    text: "Jumlah diserahkan (" + val + ") melebihi sisa (" + sisa + ")",
                    confirmButtonColor: "#e11d48"
                });
                $(this).val(sisa);
                $(this).addClass("border-rose-500 ring-1 ring-rose-500");
            } else {
                $(this).removeClass("border-rose-500 ring-1 ring-rose-500");
            }
        });
    });
</script>

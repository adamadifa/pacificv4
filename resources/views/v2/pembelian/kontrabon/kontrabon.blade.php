<!-- FILTER SECTION (NO-LABEL, KOMPAK ~36px) -->
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs">
    <form action="{{ request()->url() }}" id="formSearch" method="GET" class="space-y-2">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
            
            <!-- Dari Tanggal -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="dari" id="dari" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
            </div>

            <!-- Sampai Tanggal -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="sampai" id="sampai" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
            </div>

            <!-- Supplier (Select2) -->
            <div class="lg:col-span-3 relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_supplier_search" id="kode_supplier_search" class="select2Kodesupplier w-full">
                    <option value="">Semua Supplier</option>
                    @foreach ($supplier as $s)
                        <option value="{{ $s->kode_supplier }}" {{ Request('kode_supplier_search') == $s->kode_supplier ? 'selected' : '' }}>
                            {{ textUpperCase($s->nama_supplier) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Proses -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                </div>
                <select name="status_search" id="status_search"
                    class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                    <option value="">Status Proses</option>
                    <option value="SP" {{ Request('status_search') == 'SP' ? 'selected' : '' }}>Sudah di Proses</option>
                    <option value="BP" {{ Request('status_search') === 'BP' ? 'selected' : '' }}>Belum di Proses</option>
                </select>
            </div>

            <!-- Jenis Pengajuan -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kategori_search" id="kategori_search"
                    class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                    <option value="">Jenis Pengajuan</option>
                    <option value="KB" {{ Request('kategori_search') == 'KB' ? 'selected' : '' }}>Kontra BON</option>
                    <option value="IM" {{ Request('kategori_search') == 'IM' ? 'selected' : '' }}>Internal Memo</option>
                    <option value="TN" {{ Request('kategori_search') == 'TN' ? 'selected' : '' }}>Tunai</option>
                </select>
            </div>

            <!-- Tombol Cari & Reset -->
            <div class="lg:col-span-1 flex items-center gap-1.5">
                <button type="submit"
                    class="flex-1 h-[36px] px-3 bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152852] text-white font-bold rounded-lg text-xs flex items-center justify-center gap-1.5 transition shadow-2xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span class="sr-only sm:not-sr-only lg:hidden">Cari</span>
                </button>
                @if (Request('dari') || Request('sampai') || Request('kode_supplier_search') || Request('status_search') || Request('kategori_search'))
                    <a href="{{ request()->url() }}"
                        class="h-[36px] px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-lg text-xs flex items-center justify-center transition shrink-0 cursor-pointer"
                        title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    </a>
                @endif
            </div>

        </div>
    </form>
</div>

<!-- DATA TABLE CARD (KOMPAK ~36px) -->
<div class="rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs">
    <div class="overflow-x-auto min-h-[300px]">
        <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
            <thead>
                <!-- Baris 1: Strip Header Pacific Navy -->
                <tr class="bg-[#284c9a] text-white">
                    <th colspan="10" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="receipt" class="w-3.5 h-3.5 text-blue-200"></i>
                                <span class="text-xs font-bold text-white uppercase tracking-wider">Data Kontra Bon Pembelian</span>
                            </div>
                            <span class="text-[10.5px] text-white/70 font-mono-num">
                                Total: {{ method_exists($kontrabon, 'total') ? number_format($kontrabon->total(), 0, ',', '.') : number_format($kontrabon->count(), 0, ',', '.') }} Data
                            </span>
                        </div>
                    </th>
                </tr>

                <!-- Baris 2: Kolom Header -->
                <tr class="bg-[#1e3a78] text-white">
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 sticky left-0 z-20 bg-[#1e3a78] border-r border-[#284c9a]/40 min-w-[150px]">NO. KONTRA BON</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 sticky left-[150px] z-20 bg-[#1e3a78] border-r border-[#284c9a]/40 min-w-[120px]">NO DOKUMEN</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 sticky left-[270px] z-20 bg-[#1e3a78] border-r border-[#284c9a]/40 min-w-[110px]">TANGGAL</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 sticky left-[380px] z-20 bg-[#1e3a78] border-r border-[#284c9a]/40 min-w-[200px]">SUPPLIER</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40">KATEGORI</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-right border-r border-[#284c9a]/40">TOTAL BAYAR</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center border-r border-[#284c9a]/40">STATUS BAYAR</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center border-r border-[#284c9a]/40">JENIS BAYAR</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center border-r border-[#284c9a]/40">STATUS</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center sticky right-0 z-20 bg-[#1e3a78] min-w-[120px]">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse ($kontrabon as $d)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                        
                        <!-- No. Kontrabon (Freeze 1) -->
                        <td class="py-1.5 px-3 sticky left-0 z-10 bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800">
                            <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->no_kontrabon }}</span>
                        </td>

                        <!-- No. Dokumen (Freeze 2) -->
                        <td class="py-1.5 px-3 sticky left-[150px] z-10 bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800 font-mono-num text-slate-700 dark:text-slate-300">
                            {{ $d->no_dokumen ?: '-' }}
                        </td>

                        <!-- Tanggal (Freeze 3) -->
                        <td class="py-1.5 px-3 sticky left-[270px] z-10 bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800 font-mono-num text-slate-700 dark:text-slate-300">
                            {{ formatIndo($d->tanggal) }}
                        </td>

                        <!-- Supplier (Freeze 4) -->
                        <td class="py-1.5 px-3 sticky left-[380px] z-10 bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800 font-semibold text-slate-800 dark:text-slate-100">
                            {{ textUpperCase($d->nama_supplier) }}
                        </td>

                        <!-- Kategori -->
                        <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800">
                            @if ($d->kategori == 'TN')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                    Tunai
                                </span>
                            @elseif ($d->kategori == 'KB')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-blue-50 dark:bg-blue-950/40 text-[#284c9a] dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                    Kontra Bon
                                </span>
                            @elseif ($d->kategori == 'IM')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">
                                    Internal Memo
                                </span>
                            @endif
                        </td>

                        <!-- Total Bayar -->
                        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100 border-r border-slate-100 dark:border-slate-800">
                            {{ formatAngkaDesimal($d->jumlah) }}
                        </td>

                        <!-- Status Bayar -->
                        <td class="py-1.5 px-3 text-center border-r border-slate-100 dark:border-slate-800">
                            @if (empty($d->tglbayar))
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800">
                                    Belum Bayar
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 font-mono-num">
                                    <i data-lucide="check" class="w-3 h-3"></i>
                                    <span>{{ formatIndo($d->tglbayar) }}</span>
                                </span>
                            @endif
                        </td>

                        <!-- Jenis Bayar -->
                        <td class="py-1.5 px-3 text-center border-r border-slate-100 dark:border-slate-800">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold {{ $d->jenis_bayar == 'TN' ? 'bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">
                                {{ $d->jenis_bayar == 'TN' ? 'Tunai' : 'Transfer' }}
                            </span>
                        </td>

                        <!-- Status Approval -->
                        <td class="py-1.5 px-3 text-center border-r border-slate-100 dark:border-slate-800">
                            @if ($d->status == 1)
                                @if (!empty($d->tglbayar))
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                        <i data-lucide="check-circle" class="w-3 h-3 text-emerald-500"></i>
                                        <span>Selesai {{ $d->nama_bank ? '(' . $d->nama_bank . ')' : '' }}</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-blue-50 dark:bg-blue-950/40 text-[#284c9a] dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                        <span>Approved</span>
                                    </span>
                                @endif
                            @else
                                @if (!empty($d->tglbayar))
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                        <i data-lucide="check-circle" class="w-3 h-3 text-emerald-500"></i>
                                        <span>Selesai {{ $d->nama_bank ? '(' . $d->nama_bank . ')' : '' }}</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800" title="Menunggu Approval">
                                        <i data-lucide="clock" class="w-3 h-3 text-amber-500"></i>
                                        <span>Menunggu Approval</span>
                                    </span>
                                @endif
                            @endif
                        </td>

                        <!-- Aksi (Freeze Last) -->
                        <td class="py-1.5 px-3 sticky right-0 z-10 bg-white dark:bg-slate-900 text-center shadow-[-2px_0_5px_rgba(0,0,0,0.03)]">
                            <div class="flex items-center justify-center gap-1">
                                
                                <!-- Cetak -->
                                @can('kontrabonpmb.show')
                                    <a href="{{ route('kontrabonpmb.cetak', Crypt::encrypt($d->no_kontrabon)) }}" target="_blank"
                                        class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/40 text-[#284c9a] dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/50 flex items-center justify-center transition cursor-pointer"
                                        title="Cetak Kontra Bon">
                                        <i data-lucide="printer" class="w-3 h-3"></i>
                                    </a>
                                    <!-- Detail Modal -->
                                    <button type="button" no_kontrabon="{{ Crypt::encrypt($d->no_kontrabon) }}"
                                        class="btnShowModal w-6 h-6 rounded-md bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 hover:bg-sky-100 dark:hover:bg-sky-900/50 flex items-center justify-center transition cursor-pointer"
                                        title="Detail Kontra Bon">
                                        <i data-lucide="file-text" class="w-3 h-3"></i>
                                    </button>
                                @endcan

                                <!-- Edit -->
                                @can('kontrabonpmb.edit')
                                    @if ($d->kategori != 'TN' && $d->status === '0')
                                        <a href="{{ route('kontrabonpmb.edit', Crypt::encrypt($d->no_kontrabon)) }}"
                                            class="w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50 flex items-center justify-center transition cursor-pointer"
                                            title="Edit Data">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </a>
                                    @endif
                                @endcan

                                <!-- Approve / Cancel Approve -->
                                @can('kontrabonpmb.approve')
                                    @if ($d->kategori != 'TN')
                                        @if ($d->status === '0')
                                            <a href="{{ route('kontrabonpmb.approve', Crypt::encrypt($d->no_kontrabon)) }}"
                                                class="w-6 h-6 rounded-md bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 flex items-center justify-center transition cursor-pointer"
                                                title="Approve Kontra Bon">
                                                <i data-lucide="check-check" class="w-3 h-3"></i>
                                            </a>
                                        @elseif (empty($d->tglbayar))
                                            <a href="{{ route('kontrabonpmb.cancel', Crypt::encrypt($d->no_kontrabon)) }}"
                                                class="w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/50 flex items-center justify-center transition cursor-pointer"
                                                title="Batalkan Approval">
                                                <i data-lucide="x-circle" class="w-3 h-3"></i>
                                            </a>
                                        @endif
                                    @endif
                                @endcan

                                <!-- Proses Bayar -->
                                @can('kontrabonpmb.proses')
                                    @if (($d->status === '1' && $d->kategori != 'TN' && empty($d->tglbayar)) || ($d->status === '0' && $d->kategori == 'TN' && empty($d->tglbayar)))
                                        <button type="button" no_kontrabon="{{ Crypt::encrypt($d->no_kontrabon) }}"
                                            class="btnProsesModal w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50 flex items-center justify-center transition cursor-pointer"
                                            title="Proses Bayar">
                                            <i data-lucide="credit-card" class="w-3 h-3"></i>
                                        </button>
                                    @elseif (($d->status === '1' && $d->kategori != 'TN' && !empty($d->tglbayar)) || ($d->status === '0' && $d->kategori == 'TN' && !empty($d->tglbayar)))
                                        <form method="POST" class="inline-flex m-0 p-0 cancelform" action="{{ route('kontrabonpmb.cancelproses', Crypt::encrypt($d->no_kontrabon)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="cancel-confirm w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/50 flex items-center justify-center transition cursor-pointer"
                                                title="Batalkan Proses Pembayaran">
                                                <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan

                                <!-- Hapus -->
                                @can('kontrabonpmb.delete')
                                    @if ($d->kategori != 'TN' && $d->status === '0')
                                        <form method="POST" class="inline-flex m-0 p-0 deleteform" action="{{ route('kontrabonpmb.delete', Crypt::encrypt($d->no_kontrabon)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="delete-confirm w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/50 flex items-center justify-center transition cursor-pointer"
                                                title="Hapus Data">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="py-8 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i data-lucide="receipt" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                <span class="text-xs">Belum ada data Kontra Bon Pembelian.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION SECTION V2 -->
    @if ($kontrabon->hasPages())
        <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
            {{ $kontrabon->links('v2.components.pagination') }}
        </div>
    @endif
</div>

<!-- STATIC BACKDROP MODAL DETAIL KONTRABON V2 -->
<div id="modalDetail" class="fixed inset-0 z-50 hidden">
    <div id="modalDetailBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalDetailDialog"
            class="relative w-full max-w-3xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Detail Kontra Bon</h5>
                </div>
                <button type="button" id="btnCloseModalDetail"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="modalDetailContent" class="p-4 sm:p-5">
                <!-- Loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- STATIC BACKDROP MODAL PROSES KONTRABON V2 -->
<div id="modalProses" class="fixed inset-0 z-50 hidden">
    <div id="modalProsesBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalProsesDialog"
            class="relative w-full max-w-3xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="credit-card" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Proses Pembayaran Kontra Bon</h5>
                </div>
                <button type="button" id="btnCloseModalProses"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="modalProsesContent" class="p-4 sm:p-5">
                <!-- Loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- STATIC BACKDROP MODAL DETAIL PEMBELIAN V2 -->
<div id="modalDetailPembelian" class="fixed inset-0 z-50 hidden">
    <div id="modalDetailPembelianBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalDetailPembelianDialog"
            class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="shopping-cart" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Detail Pembelian</h5>
                </div>
                <button type="button" id="btnCloseModalDetailPembelian"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="modalDetailPembelianContent" class="p-4">
                <!-- Loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
    $(function() {
        // Flatpickr
        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Select2 Supplier
        const $select2Supplier = $('.select2Kodesupplier');
        if ($select2Supplier.length && $.fn.select2) {
            $select2Supplier.select2({
                placeholder: 'Semua Supplier',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        // Helpers Static Backdrop Modal
        function initModalHelper(modalId, dialogId, backdropId, closeBtnId) {
            const $m = $(modalId);
            const $d = $(dialogId);
            function open() {
                $m.removeClass('hidden');
                $('body').addClass('overflow-hidden');
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
            function close() {
                $m.addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
            function bounce() {
                $d.addClass('scale-[1.015] shadow-2xl');
                setTimeout(() => $d.removeClass('scale-[1.015] shadow-2xl'), 150);
            }
            $(backdropId).on('click', function(e) { e.stopPropagation(); bounce(); });
            $(closeBtnId).on('click', close);
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && !$m.hasClass('hidden')) bounce();
            });
            return { open, close };
        }

        const modalDetail = initModalHelper('#modalDetail', '#modalDetailDialog', '#modalDetailBackdrop', '#btnCloseModalDetail');
        const modalProses = initModalHelper('#modalProses', '#modalProsesDialog', '#modalProsesBackdrop', '#btnCloseModalProses');
        const modalPembelian = initModalHelper('#modalDetailPembelian', '#modalDetailPembelianDialog', '#modalDetailPembelianBackdrop', '#btnCloseModalDetailPembelian');

        // Trigger Detail Kontrabon Modal
        $(document).on('click', '.btnShowModal', function(e) {
            e.preventDefault();
            const no_kontrabon = $(this).attr('no_kontrabon');
            $("#modalDetailContent").html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-slate-500 font-semibold">Memuat detail kontrabon...</span>
                </div>
            `);
            modalDetail.open();
            $("#modalDetailContent").load(`/kontrabonpembelian/${no_kontrabon}/show`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Trigger Proses Kontrabon Modal
        $(document).on('click', '.btnProsesModal', function(e) {
            e.preventDefault();
            const no_kontrabon = $(this).attr('no_kontrabon');
            $("#modalProsesContent").html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-slate-500 font-semibold">Memuat form proses...</span>
                </div>
            `);
            modalProses.open();
            $("#modalProsesContent").load(`/kontrabonpembelian/${no_kontrabon}/proses`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Trigger Detail Pembelian Modal
        $(document).on('click', '.btnShowpembelian', function(e) {
            e.preventDefault();
            const no_bukti = $(this).attr('no_bukti');
            $("#modalDetailPembelianContent").html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-slate-500 font-semibold">Memuat detail pembelian...</span>
                </div>
            `);
            modalPembelian.open();
            $("#modalDetailPembelianContent").load(`/pembelian/${no_bukti}/show`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Delete Confirm
        $(document).on("click", ".delete-confirm", function(e) {
            e.preventDefault();
            const form = $(this).closest("form");
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data Kontra Bon ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#284c9a',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Cancel Proses Confirm
        $(document).on("click", ".cancel-confirm", function(e) {
            e.preventDefault();
            const form = $(this).closest("form");
            Swal.fire({
                title: 'Batalkan Proses Bayar?',
                text: "Data pembayaran kontra bon ini akan dibatalkan!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#284c9a',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush

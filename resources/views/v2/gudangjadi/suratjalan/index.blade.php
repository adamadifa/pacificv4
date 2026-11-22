{{-- Filter Section (No-Label, Icon Prefix, h-[34px], rounded-lg) --}}
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200 mb-3.5">
    <form action="{{ request()->is(['suratjalancabang']) ? route('suratjalancabang.index') : route('suratjalan.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
        
        <!-- Dari Tanggal -->
        <div class="lg:col-span-2 relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="dari" id="dari" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
        </div>

        <!-- Sampai Tanggal -->
        <div class="lg:col-span-2 relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="sampai" id="sampai" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
        </div>

        <!-- No. Dokumen -->
        <div class="lg:col-span-2 relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="no_dok_search" id="no_dok_search" value="{{ Request('no_dok_search') }}" placeholder="No. Dokumen"
                class="w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
        </div>

        <!-- Pilihan Cabang (Jika Role Show Cabang) -->
        @hasanyrole($roles_show_cabang)
            <div class="lg:col-span-2 relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_cabang_search" id="kode_cabang_search"
                    class="select2Kodecabangsearch select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                            {{ textUpperCase($c->nama_cabang) }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endrole

        <!-- Status -->
        <div class="{{ auth()->user()->hasAnyRole($roles_show_cabang) ? 'lg:col-span-2' : 'lg:col-span-4' }} relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
            </div>
            <select name="status_search" id="status_search"
                class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                <option value="">Semua Status</option>
                <option value="0" {{ Request('status_search') === '0' ? 'selected' : '' }}>Belum Diterima Cabang</option>
                <option value="1" {{ Request('status_search') === '1' ? 'selected' : '' }}>Sudah Diterima Cabang</option>
                <option value="2" {{ Request('status_search') === '2' ? 'selected' : '' }}>Transit Out</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-3 h-3"></i>
            </div>
        </div>

        <!-- Tombol Cari -->
        <div class="lg:col-span-2 flex items-center">
            <button type="submit"
                class="w-full h-[34px] inline-flex items-center justify-center gap-1.5 px-3 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                <span>Cari</span>
            </button>
        </div>
    </form>
</div>

<!-- DATA TABLE SECTION -->
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <!-- Strip Judul Tabel (Colspan Penuh = 7) -->
                <tr class="bg-[#284c9a] border-b border-[#1e3a78] text-white">
                    <th colspan="7" class="px-3.5 py-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i data-lucide="truck" class="w-4 h-4 text-white/80"></i>
                                <span class="text-xs font-bold uppercase tracking-wider text-white">Data Surat Jalan</span>
                            </div>
                            @can('suratjalan.create')
                                <a href="{{ route('permintaankiriman.index') }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-white/10 hover:bg-white/20 active:bg-white/30 text-white text-[11px] font-bold transition border border-white/20 shadow-2xs">
                                    <i data-lucide="plus" class="w-3 h-3"></i>
                                    <span>Tambah Data</span>
                                </a>
                            @endcan
                        </div>
                    </th>
                </tr>
                <!-- Header Kolom Pacific Navy Pekat (#1e3a78) -->
                <tr class="bg-[#1e3a78] text-white text-[11px] font-bold uppercase tracking-wider">
                    <th class="py-2 px-3">No. Surat Jalan</th>
                    <th class="py-2 px-3">No. Dokumen</th>
                    <th class="py-2 px-3">Tanggal</th>
                    <th class="py-2 px-3">Cabang</th>
                    <th class="py-2 px-3">Status</th>
                    <th class="py-2 px-3">Tgl Diterima</th>
                    <th class="py-2 px-3 text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs text-slate-700 dark:text-slate-300">
                @forelse ($surat_jalan as $d)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                        <!-- No Surat Jalan -->
                        <td class="py-1.5 px-3">
                            <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400">
                                {{ $d->no_mutasi }}
                            </span>
                        </td>

                        <!-- No Dokumen -->
                        <td class="py-1.5 px-3 font-medium">
                            {{ $d->no_dok }}
                        </td>

                        <!-- Tanggal -->
                        <td class="py-1.5 px-3 font-mono-num">
                            {{ date('d-m-Y', strtotime($d->tanggal)) }}
                        </td>

                        <!-- Cabang -->
                        <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-200">
                            {{ textUpperCase($d->nama_cabang) }}
                        </td>

                        <!-- Status -->
                        <td class="py-1.5 px-3">
                            @if ($d->status_surat_jalan == 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50">
                                    Belum Diterima
                                </span>
                            @elseif($d->status_surat_jalan == 1)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                                    Sudah Diterima
                                </span>
                            @elseif($d->status_surat_jalan == 2)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-600 dark:bg-sky-900/30 dark:text-sky-400 border border-sky-200 dark:border-sky-800/50">
                                    Transit Out
                                </span>
                            @endif
                        </td>

                        <!-- Tgl Diterima -->
                        <td class="py-1.5 px-3 font-mono-num">
                            @if (!empty($d->tanggal_mutasi_cabang))
                                @if ($d->status_surat_jalan == '1')
                                    @if (empty($d->tanggal_transit_in))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            {{ date('d-m-Y', strtotime($d->tanggal_mutasi_cabang)) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            {{ date('d-m-Y', strtotime($d->tanggal_transit_in)) }}
                                        </span>
                                    @endif
                                @elseif($d->status_surat_jalan == '2')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-600 dark:bg-sky-900/30 dark:text-sky-400">
                                        {{ date('d-m-Y', strtotime($d->tanggal_mutasi_cabang)) }}
                                    </span>
                                @endif
                            @else
                                <span class="text-amber-500 inline-flex items-center gap-1 text-[11px] font-medium" title="Menunggu Penerimaan">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                    <span>Pending</span>
                                </span>
                            @endif
                        </td>

                        <!-- Aksi Kompak (24x24 px) -->
                        <td class="py-1.5 px-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                {{-- Edit --}}
                                @can('suratjalan.edit')
                                    @if ($d->status_surat_jalan == '0')
                                        <button type="button" no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}" title="Edit Surat Jalan"
                                            class="btnEdit w-6 h-6 rounded-md flex items-center justify-center text-amber-600 hover:text-amber-700 hover:bg-amber-50 dark:hover:bg-amber-950/30 transition">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                        </button>
                                    @endif
                                @endcan

                                {{-- Detail --}}
                                @can('suratjalan.show')
                                    <button type="button" no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}" title="Lihat Detail"
                                        class="btnShow w-6 h-6 rounded-md flex items-center justify-center text-sky-600 hover:text-sky-700 hover:bg-sky-50 dark:hover:bg-sky-950/30 transition">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    </button>
                                @endcan

                                {{-- Terima / Cancel Penerimaan --}}
                                @can('suratjalan.approve')
                                    @if ($d->status_surat_jalan === '0')
                                        <button type="button" no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}" title="Terima Surat Jalan"
                                            class="btnApprove w-6 h-6 rounded-md flex items-center justify-center text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 transition">
                                            <i data-lucide="check-square" class="w-3.5 h-3.5"></i>
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('suratjalan.cancel', Crypt::encrypt($d->no_mutasi)) }}" class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Batalkan Penerimaan"
                                                class="cancel-confirm w-6 h-6 rounded-md flex items-center justify-center text-amber-600 hover:text-amber-700 hover:bg-amber-50 dark:hover:bg-amber-950/30 transition cursor-pointer">
                                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan

                                {{-- Hapus --}}
                                @can('suratjalan.delete')
                                    @if ($d->status_surat_jalan == '0')
                                        <form method="POST" action="{{ route('suratjalan.delete', Crypt::encrypt($d->no_mutasi)) }}" class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Surat Jalan"
                                                class="delete-confirm w-6 h-6 rounded-md flex items-center justify-center text-rose-600 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition cursor-pointer">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-1.5">
                                <i data-lucide="inbox" class="w-6 h-6 text-slate-300"></i>
                                <span class="text-xs font-medium">Belum ada data surat jalan</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION V2 STANDAR -->
    @if ($surat_jalan->hasPages())
        <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
            {{ $surat_jalan->links('v2.components.pagination') }}
        </div>
    @endif
</div>

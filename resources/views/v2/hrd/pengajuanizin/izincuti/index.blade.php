@extends('v2.layouts.app')
@section('titlepage', 'Izin Cuti')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">HRD</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Pengajuan Izin</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Izin Cuti</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full max-w-7xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Izin Cuti</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Pengajuan izin cuti karyawan.</p>
        </div>
    </div>

    <!-- NAVIGATION TABS PENGAJUAN IZIN -->
    @include('v2.hrd.pengajuanizin._nav')

    <!-- FILTER BAR (COMPACT NO-LABEL V2) -->
    <form action="{{ route('izincuti.index') }}" method="GET"
        class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
            <div class="sm:col-span-3 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_search" value="{{ Request('dari') }}"
                    placeholder="Dari Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <div class="sm:col-span-3 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_search" value="{{ Request('sampai') }}"
                    placeholder="Sampai Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <div class="sm:col-span-6 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <input type="text" name="nama_karyawan" id="nama_karyawan_search" value="{{ Request('nama_karyawan') }}"
                    placeholder="Cari Nama Karyawan..."
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
            @if (in_array($level_user, ['super admin', 'asst. manager hrd', 'spv presensi']))
                <div class="sm:col-span-3 relative">
                    <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_search"
                        class="select2KodecabangSearch w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" @selected(Request('kode_cabang') == $c->kode_cabang)>{{ strtoupper($c->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-3 relative">
                    <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="briefcase" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_dept" id="kode_dept_search"
                        class="select2KodeDeptSearch w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200">
                        <option value="">Semua Departemen</option>
                        @foreach ($departemen as $dept)
                            <option value="{{ $dept->kode_dept }}" @selected(Request('kode_dept') == $dept->kode_dept)>{{ strtoupper($dept->nama_dept) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-4 relative">
                    <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                    </div>
                    <select name="status" id="status_search"
                        class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        <option value="">Semua Status</option>
                        <option value="pending" @selected(Request('status') === 'pending')>Pending</option>
                        <option value="disetujui" @selected(Request('status') === 'disetujui')>Disetujui</option>
                        @if ($level_user == 'asst. manager hrd')
                            <option value="direktur" @selected(Request('status') === 'direktur')>Disetujui Direktur</option>
                            <option value="pendingdirektur" @selected(Request('status') === 'pendingdirektur')>Pending Direktur</option>
                        @endif
                    </select>
                </div>
            @else
                <div class="sm:col-span-10 relative">
                    <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                    </div>
                    <select name="status" id="status_search"
                        class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        <option value="">Semua Status</option>
                        <option value="pending" @selected(Request('status') === 'pending')>Pending</option>
                        <option value="disetujui" @selected(Request('status') === 'disetujui')>Disetujui</option>
                    </select>
                </div>
            @endif

            <div class="sm:col-span-2">
                <button type="submit" id="btnSearch"
                    class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>
            </div>
        </div>
    </form>

    <!-- DATA TABLE CARD (V2 COMPACT ~36PX) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="calendar-days" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Data Izin Cuti</h5>
            </div>
            @can('izincuti.create')
                <button type="button" id="btnCreateIzinCuti"
                    class="h-[28px] px-3 rounded-lg bg-white text-[#284c9a] hover:bg-blue-50 text-xs font-bold shadow-xs transition flex items-center gap-1 cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Data</span>
                </button>
            @endcan
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-2 px-3">KODE</th>
                        <th class="py-2 px-3">TANGGAL</th>
                        <th class="py-2 px-3">NIK</th>
                        <th class="py-2 px-3 min-w-[180px]">NAMA KARYAWAN</th>
                        <th class="py-2 px-3">CABANG</th>
                        <th class="py-2 px-3">JENIS CUTI</th>
                        <th class="py-2 px-3 text-center">LAMA</th>
                        <th class="py-2 px-3 text-center"><i data-lucide="paperclip" class="w-3.5 h-3.5 inline-block"></i></th>
                        <th class="py-2 px-3 text-center">POSISI</th>
                        <th class="py-2 px-3 text-center">STATUS</th>
                        <th class="py-2 px-3 text-center w-28">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($izincuti as $d)
                        @php $jmlhari = hitungHari($d->dari, $d->sampai); @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_izin_cuti }}</td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-700 dark:text-slate-300">{{ formatIndo($d->tanggal) }}</td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">{{ $d->nik }}</td>
                            <td class="py-1.5 px-3 font-bold text-slate-800 dark:text-slate-100">{{ formatName($d->nama_karyawan) }}</td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">{{ $d->kode_cabang }}</td>
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">{{ $d->nama_cuti }}</td>
                            <td class="py-1.5 px-3 text-center font-mono-num font-semibold text-slate-700 dark:text-slate-300">{{ $jmlhari }} Hari</td>
                            <td class="py-1.5 px-3 text-center">
                                @if (!empty($d->doc_cuti))
                                    @if (Storage::disk('public')->exists('/uploads/cuti/' . $d->doc_cuti))
                                        <a href="{{ getfileCuti($d->doc_cuti) }}" target="_blank" title="Lihat Dokumen"
                                            class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-blue-50 text-[#284c9a] hover:bg-blue-100 transition">
                                            <i data-lucide="paperclip" class="w-3.5 h-3.5"></i>
                                        </a>
                                    @else
                                        <span class="text-rose-500 text-[10.5px] font-bold" title="File tidak ditemukan">404</span>
                                    @endif
                                @else
                                    <i data-lucide="x" class="w-3.5 h-3.5 text-slate-300 inline-block"></i>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                @if (empty($d->head))
                                    <span class="px-2 py-0.5 rounded bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 text-[10.5px] font-bold">HEAD</span>
                                @elseif(!empty($d->head) && empty($d->hrd))
                                    <span class="px-2 py-0.5 rounded bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 text-[10.5px] font-bold">HRD</span>
                                @elseif(!empty($d->head) && !empty($d->hrd) && $d->forward_to_direktur == '0')
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[10.5px] font-bold">HRD</span>
                                @elseif(!empty($d->head) && !empty($d->hrd) && $d->forward_to_direktur == '1' && $d->direktur == '0')
                                    <span class="px-2 py-0.5 rounded bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 text-[10.5px] font-bold">DIREKTUR</span>
                                @elseif(!empty($d->head) && !empty($d->hrd) && $d->forward_to_direktur == '1' && $d->direktur == '1')
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[10.5px] font-bold">DIREKTUR</span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                @if ($level_user == 'direktur')
                                    @if ($d->direktur == '1')
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600"><i data-lucide="check-check" class="w-3.5 h-3.5"></i></span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-50 dark:bg-amber-950/60 text-amber-600"><i data-lucide="hourglass" class="w-3.5 h-3.5"></i></span>
                                    @endif
                                @else
                                    @if ($d->status == '1')
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600">
                                            <i data-lucide="{{ $d->direktur == '1' ? 'check-check' : 'check-square' }}" class="w-3.5 h-3.5"></i>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-50 dark:bg-amber-950/60 text-amber-600"><i data-lucide="hourglass" class="w-3.5 h-3.5"></i></span>
                                    @endif
                                @endif
                            </td>
                            <td class="py-1.5 px-3">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" kode_izin_cuti="{{ Crypt::encrypt($d->kode_izin_cuti) }}"
                                        class="btnShowIzinCuti w-6 h-6 rounded-md bg-blue-50 hover:bg-blue-100 text-[#284c9a] dark:bg-blue-950/50 dark:text-blue-400 flex items-center justify-center transition cursor-pointer"
                                        title="Detail">
                                        <i data-lucide="file-text" class="w-3 h-3"></i>
                                    </button>

                                    @can('izincuti.edit')
                                        @if ((in_array($level_user, $level_hrd) && $d->status == 0) || ($d->status == 0 && empty($d->head)))
                                            <button type="button" kode_izin_cuti="{{ Crypt::encrypt($d->kode_izin_cuti) }}"
                                                class="btnEditIzinCuti w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                                                title="Edit">
                                                <i data-lucide="pencil" class="w-3 h-3"></i>
                                            </button>
                                        @endif
                                    @endcan

                                    @can('izincuti.delete')
                                        @if ((in_array($level_user, $level_hrd) && $d->status == 0) || ($d->status == 0 && empty($d->head)))
                                            <form method="POST" class="inline-flex m-0 p-0 deleteform"
                                                action="{{ route('izincuti.delete', Crypt::encrypt($d->kode_izin_cuti)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                                    title="Hapus">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan

                                    @can('izincuti.approve')
                                        @if (in_array($level_user, $level_hrd))
                                            @if (!empty($d->head) && empty($d->hrd) && $d->status == 0)
                                                <button type="button" kode_izin_cuti="{{ Crypt::encrypt($d->kode_izin_cuti) }}"
                                                    class="btnApproveIzinCuti w-6 h-6 rounded-md bg-sky-50 hover:bg-sky-100 text-sky-600 dark:bg-sky-950/50 dark:text-sky-400 flex items-center justify-center transition cursor-pointer"
                                                    title="Approve">
                                                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                                                </button>
                                            @elseif (!empty($d->hrd) && empty($d->direktur))
                                                <form method="POST" class="inline-flex m-0 p-0"
                                                    action="{{ route('izincuti.cancel', Crypt::encrypt($d->kode_izin_cuti)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="cancel-confirm w-6 h-6 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400 flex items-center justify-center transition cursor-pointer"
                                                        title="Batalkan">
                                                        <i data-lucide="x-circle" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            @php
                                                $dept_access = $roles_can_approve[$level_user]['dept'] ?? [];
                                                $dept_acess_2 = $roles_can_approve[$level_user]['dept2'] ?? [];
                                                $jabatan_access = $roles_can_approve[$level_user]['jabatan'] ?? [];
                                                $jabatan_access_2 = $roles_can_approve[$level_user]['jabatan2'] ?? [];
                                            @endphp
                                            @if (in_array($d->kode_dept, $dept_access) || in_array($d->kode_dept, $dept_acess_2) || empty($dept_access) || empty($dept_acess_2))
                                                @if (in_array($d->kode_jabatan, $jabatan_access) || empty($jabatan_access) || in_array($d->kode_jabatan, $jabatan_access_2) || empty($jabatan_access_2))
                                                    @if (empty($d->head) && empty($d->hrd) && $d->status == 0)
                                                        <button type="button" kode_izin_cuti="{{ Crypt::encrypt($d->kode_izin_cuti) }}"
                                                            class="btnApproveIzinCuti w-6 h-6 rounded-md bg-sky-50 hover:bg-sky-100 text-sky-600 dark:bg-sky-950/50 dark:text-sky-400 flex items-center justify-center transition cursor-pointer"
                                                            title="Approve">
                                                            <i data-lucide="check-circle" class="w-3 h-3"></i>
                                                        </button>
                                                    @elseif (empty($d->hrd) && $d->status == 0)
                                                        <form method="POST" class="inline-flex m-0 p-0"
                                                            action="{{ route('izincuti.cancel', Crypt::encrypt($d->kode_izin_cuti)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="cancel-confirm w-6 h-6 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400 flex items-center justify-center transition cursor-pointer"
                                                                title="Batalkan">
                                                                <i data-lucide="x-circle" class="w-3 h-3"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif

                                        @if ($level_user == 'direktur')
                                            @if ($d->direktur == 0 && !empty($d->hrd) && $d->forward_to_direktur == '1')
                                                <button type="button" kode_izin_cuti="{{ Crypt::encrypt($d->kode_izin_cuti) }}"
                                                    class="btnApproveIzinCuti w-6 h-6 rounded-md bg-sky-50 hover:bg-sky-100 text-sky-600 dark:bg-sky-950/50 dark:text-sky-400 flex items-center justify-center transition cursor-pointer"
                                                    title="Approve">
                                                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                                                </button>
                                            @else
                                                <form method="POST" class="inline-flex m-0 p-0"
                                                    action="{{ route('izincuti.cancel', Crypt::encrypt($d->kode_izin_cuti)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="cancel-confirm w-6 h-6 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400 flex items-center justify-center transition cursor-pointer"
                                                        title="Batalkan">
                                                        <i data-lucide="x-circle" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-10 px-4 text-center">
                                <div class="flex flex-col items-center justify-center gap-1.5 text-slate-400">
                                    <i data-lucide="calendar-off" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Tidak ada data izin cuti ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($izincuti->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $izincuti->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- ================= MODAL IZIN CUTI (STATIC BACKDROP) ================= -->
<div id="modalIzinCuti" class="fixed inset-0 z-50 hidden">
    <div id="modalIzinCutiBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalIzinCutiDialog"
            class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 rounded-t-xl">
                <div class="flex items-center gap-2">
                    <i data-lucide="calendar-days" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalIzinCutiTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Buat Izin Cuti</h5>
                </div>
                <button type="button" id="btnCloseModalIzinCuti"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="loadmodalIzinCuti" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $('.flatpickr-date').flatpickr({ dateFormat: 'Y-m-d', allowInput: false });

        if ($.fn.select2) {
            $('.select2KodecabangSearch').select2({ placeholder: 'Semua Cabang', allowClear: true, width: '100%' });
            $('.select2KodeDeptSearch').select2({ placeholder: 'Semua Departemen', allowClear: true, width: '100%' });
        }

        const $modal = $('#modalIzinCuti');
        const $dialog = $('#modalIzinCutiDialog');
        const $backdrop = $('#modalIzinCutiBackdrop');
        const $title = $('#modalIzinCutiTitle');
        const $content = $('#loadmodalIzinCuti');

        function openModal() { $modal.removeClass('hidden'); $('body').addClass('overflow-hidden'); }
        function closeModal() { $modal.addClass('hidden'); $('body').removeClass('overflow-hidden'); $content.html(''); }
        window.closeModalIzinCuti = closeModal;

        $('#btnCloseModalIzinCuti').on('click', closeModal);
        $backdrop.on('click', function(e) {
            e.stopPropagation();
            $dialog.addClass('scale-[1.015]');
            setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
        });

        function showLoading() {
            $content.html(`<div class="py-10 flex flex-col items-center justify-center gap-2"><svg class="animate-spin h-6 w-6 text-[#284c9a]" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg><span class="text-xs font-semibold text-slate-500">Memuat data...</span></div>`);
        }

        $('#btnCreateIzinCuti').on('click', function(e) {
            e.preventDefault();
            $title.text('Buat Izin Cuti');
            openModal(); showLoading();
            $content.load('/izincuti/create', function() { if (typeof lucide !== 'undefined') lucide.createIcons(); });
        });

        $(document).on('click', '.btnEditIzinCuti', function(e) {
            e.preventDefault();
            const kode = $(this).attr('kode_izin_cuti');
            $title.text('Edit Izin Cuti');
            openModal(); showLoading();
            $content.load(`/izincuti/${kode}/edit`, function() { if (typeof lucide !== 'undefined') lucide.createIcons(); });
        });

        $(document).on('click', '.btnApproveIzinCuti', function(e) {
            e.preventDefault();
            const kode = $(this).attr('kode_izin_cuti');
            $title.text('Approve Izin Cuti');
            openModal(); showLoading();
            $content.load(`/izincuti/${kode}/approve`, function() { if (typeof lucide !== 'undefined') lucide.createIcons(); });
        });

        $(document).on('click', '.btnShowIzinCuti', function(e) {
            e.preventDefault();
            const kode = $(this).attr('kode_izin_cuti');
            $title.text('Detail Izin Cuti');
            openModal(); showLoading();
            $content.load(`/izincuti/${kode}/show`, function() { if (typeof lucide !== 'undefined') lucide.createIcons(); });
        });
    });
</script>
@endpush

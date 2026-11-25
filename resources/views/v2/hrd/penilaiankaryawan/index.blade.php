@extends('v2.layouts.app')
@section('titlepage', 'Penilaian Karyawan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">HRD</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Penilaian Karyawan</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Penilaian Karyawan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Mengelola data penilaian kinerja dan perpanjangan kontrak karyawan.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @can('penilaiankaryawan.config.index')
                <a href="{{ route('penilaiankaryawanconfig.index') }}"
                    class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold transition flex items-center gap-1.5">
                    <i data-lucide="settings" class="w-3.5 h-3.5 text-slate-500"></i>
                    <span>Config Approval</span>
                </a>
            @endcan
            @can('penilaiankaryawan.create')
                <button type="button" id="btnCreatePenilaian"
                    class="h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Buat Penilaian Karyawan</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- FILTER BAR (COMPACT NO-LABEL V2, FULL WIDTH) -->
    <form action="{{ route('penilaiankaryawan.index') }}" method="GET"
        class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
            <!-- Dari -->
            <div class="sm:col-span-2 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_search" value="{{ request('dari') }}"
                    placeholder="Dari Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <!-- Sampai -->
            <div class="sm:col-span-2 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_search" value="{{ request('sampai') }}"
                    placeholder="Sampai Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <!-- Cabang -->
            <div class="sm:col-span-4 relative select2-container-v2">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building" class="w-4 h-4"></i>
                </div>
                <select name="kode_cabang_search" id="kode_cabang_search" class="select2Kodecabangsearch w-full">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}" @selected(request('kode_cabang_search') == $d->kode_cabang)>
                            {{ textUpperCase($d->nama_cabang) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Departemen -->
            <div class="sm:col-span-4 relative select2-container-v2">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="briefcase" class="w-4 h-4"></i>
                </div>
                <select name="kode_dept_search" id="kode_dept_search" class="select2Kodedeptsearch w-full">
                    <option value="">Semua Departemen</option>
                    @foreach ($departemen as $d)
                        <option value="{{ $d->kode_dept }}" @selected(request('kode_dept_search') == $d->kode_dept)>
                            {{ textUpperCase($d->nama_dept) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
            <!-- Nama Karyawan -->
            <div class="sm:col-span-4 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <input type="text" name="nama_karyawan_search" id="nama_karyawan_search" value="{{ request('nama_karyawan_search') }}"
                    placeholder="Cari Nama Karyawan..."
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
            </div>

            <!-- Posisi Ajuan -->
            @if (!empty($listApprovepenilaian))
                <div class="sm:col-span-3 relative">
                    <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="git-branch" class="w-4 h-4"></i>
                    </div>
                    <select name="posisi_ajuan" id="posisi_ajuan_search"
                        class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        <option value="">Semua Posisi Ajuan</option>
                        @foreach ($listApprovepenilaian as $d)
                            <option value="{{ $d }}" @selected(request('posisi_ajuan') == $d)>
                                {{ textUpperCase($d) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Status -->
            <div class="{{ !empty($listApprovepenilaian) ? 'sm:col-span-3' : 'sm:col-span-6' }} relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                </div>
                <select name="status" id="status_search"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="disetujui" @selected(request('status') === 'disetujui')>Disetujui</option>
                </select>
            </div>

            <!-- Cari -->
            <div class="sm:col-span-2">
                <button type="submit"
                    class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>
            </div>
        </div>
    </form>

    <!-- LIST CARD FULL WIDTH -->
    <div class="space-y-3" id="penilaian-list">
        @forelse ($penilaiankaryawan as $d)
            @php
                $roles_approve = cekRoleapprove($d->kode_dept, $d->kode_cabang, $d->kategori_jabatan, $d->kode_jabatan);
                $end_role = end($roles_approve);
                $user_can_approve = in_array($d->posisi_ajuan, $user_role_ids);

                $is_next_of_user = false;
                foreach ($role_names as $r) {
                    $idx = array_search($r, $roles_approve);
                    if ($idx !== false && isset($roles_approve[$idx + 1])) {
                        if (getRoleID($roles_approve[$idx + 1]) == $d->posisi_ajuan) {
                            $is_next_of_user = true;
                            break;
                        }
                    }
                }

                $is_creator = $d->id_user == auth()->user()->id;
                $is_pending = $d->status === '0';
                $first_role = !empty($roles_approve) ? $roles_approve[0] : null;
                $first_role_id = $first_role ? getRoleID($first_role) : null;
                $is_at_first_step = empty($d->posisi_ajuan) || $d->posisi_ajuan == $first_role_id;
                $is_admin = auth()->user()->hasRole(['super admin', 'asst. manager hrd', 'spv presensi']);
            @endphp

            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs hover:shadow-md hover:border-[#284c9a]/50 transition-all duration-150 overflow-hidden">
                <!-- CARD HEADER -->
                <div class="px-4 py-2 bg-slate-50/90 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5">
                        <span class="px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-300 font-mono-num font-bold text-xs">
                            {{ $d->kode_penilaian }}
                        </span>
                        <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 flex items-center gap-1">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            {{ formatIndo($d->tanggal) }}
                        </span>
                    </div>

                    <div class="flex items-center gap-1.5">
                        @if ($d->status == '1')
                            <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 text-[11px] font-bold inline-flex items-center gap-1">
                                <i data-lucide="check" class="w-3 h-3"></i>
                                Disetujui
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 text-[11px] font-bold inline-flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                Pending
                            </span>
                        @endif

                        @if ($d->posisi_ajuan == null)
                            @if ($d->status == '1')
                                <span class="px-2 py-0.5 rounded-md bg-[#284c9a] text-white text-[11px] font-bold">DIREKTUR</span>
                            @else
                                <span class="px-2 py-0.5 rounded-md bg-rose-600 text-white text-[11px] font-bold">Belum di Konfigurasi</span>
                            @endif
                        @else
                            <span class="px-2 py-0.5 rounded-md bg-[#284c9a] text-white text-[11px] font-bold">
                                {{ singkatString($d->posisi_ajuan_name) == 'AMH' ? 'HRD' : singkatString($d->posisi_ajuan_name) }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- CARD BODY -->
                <div class="px-4 py-3 grid grid-cols-1 lg:grid-cols-12 gap-3 items-center">
                    <!-- Kolom 1: Avatar & Identitas -->
                    <div class="lg:col-span-4 lg:border-r lg:border-slate-100 dark:lg:border-slate-800 lg:pr-3 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 bg-blue-50 dark:bg-blue-950/50 border border-slate-200 dark:border-slate-700 flex items-center justify-center">
                            @if (!empty($d->foto) && Storage::disk('public')->exists('/karyawan/' . ($d instanceof App\Models\Karyawan ? $d->getRawOriginal('foto') : $d->foto)))
                                <img src="{{ getfotoKaryawan($d->foto) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <i data-lucide="user" class="w-5 h-5 text-[#284c9a] dark:text-blue-400"></i>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h6 class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ formatName($d->nama_karyawan) }}</h6>
                            <p class="text-[11px] font-mono-num text-slate-400 mt-0.5">{{ $d->nik }}</p>
                        </div>
                    </div>

                    <!-- Kolom 2: Jabatan, Dept, Cabang -->
                    <div class="lg:col-span-4 lg:border-r lg:border-slate-100 dark:lg:border-slate-800 lg:px-3 space-y-1">
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-200">
                            <i data-lucide="briefcase" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                            <span class="truncate">{{ !empty($d->alias_jabatan) ? $d->alias_jabatan : $d->nama_jabatan }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-[11px] text-slate-500 dark:text-slate-400">
                            <i data-lucide="building" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                            <span>{{ $d->kode_dept }} | {{ $d->kode_cabang }}</span>
                        </div>
                        @if (!empty($d->nama_group))
                            <div class="flex items-center gap-2 text-[11px] text-slate-500 dark:text-slate-400">
                                <i data-lucide="users" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                                <span class="truncate">{{ $d->nama_group }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Kolom 3: Periode Kontrak & KB -->
                    <div class="lg:col-span-4 lg:pl-3 flex items-center justify-between gap-2">
                        <div>
                            <p class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wider">Periode Kontrak</p>
                            <p class="text-xs font-bold text-[#284c9a] dark:text-blue-400 flex items-center gap-1 mt-0.5">
                                <i data-lucide="clock" class="w-3.5 h-3.5 shrink-0"></i>
                                <span>{{ formatIndo($d->kontrak_dari) }} - {{ formatIndo($d->kontrak_sampai) }}</span>
                            </p>
                        </div>

                        <div class="flex items-center gap-1.5">
                            @if ($d->status == 1)
                                @if ($d->status_pemutihan == 1 && empty($d->no_kb))
                                    @can('kb.create')
                                        <button type="button" kode_penilaian="{{ Crypt::encrypt($d->kode_penilaian) }}"
                                            class="btnCreatekb w-7 h-7 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400 flex items-center justify-center transition cursor-pointer"
                                            title="Buat KB">
                                            <i data-lucide="file-plus" class="w-3.5 h-3.5"></i>
                                        </button>
                                    @endcan
                                @elseif (!empty($d->no_kb))
                                    <a href="{{ route('kesepakatanbersama.cetak', Crypt::encrypt($d->no_kb)) }}" target="_blank"
                                        class="w-7 h-7 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400 flex items-center justify-center transition"
                                        title="Cetak KB">
                                        <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- CARD FOOTER (AKSI) -->
                <div class="px-4 py-2 bg-slate-50/50 dark:bg-slate-900/40 border-t border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-end gap-1.5">
                    @can('penilaiankaryawan.edit')
                        @if (($is_creator && $is_pending && $is_at_first_step) || ($user_can_approve && $is_pending) || $is_admin)
                            <a href="{{ route('penilaiankaryawan.edit', Crypt::encrypt($d->kode_penilaian)) }}"
                                class="w-7 h-7 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:hover:bg-emerald-900/60 dark:text-emerald-400 flex items-center justify-center transition"
                                title="Edit">
                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                            </a>
                        @endif
                        @if (auth()->user()->hasRole('super admin'))
                            <button type="button" kode_penilaian="{{ Crypt::encrypt($d->kode_penilaian) }}"
                                class="btnEditPosisi w-7 h-7 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/50 dark:hover:bg-amber-900/60 dark:text-amber-400 flex items-center justify-center transition cursor-pointer"
                                title="Edit Posisi">
                                <i data-lucide="git-branch" class="w-3.5 h-3.5"></i>
                            </button>
                        @endif
                    @endcan

                    @can('penilaiankaryawan.show')
                        <button type="button" kode_penilaian="{{ Crypt::encrypt($d->kode_penilaian) }}"
                            class="btnDetail w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 flex items-center justify-center transition cursor-pointer"
                            title="Detail">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        </button>
                        <a href="{{ route('penilaiankaryawan.cetak', Crypt::encrypt($d->kode_penilaian)) }}" target="_blank"
                            class="w-7 h-7 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#284c9a] dark:bg-blue-950/50 dark:hover:bg-blue-900/60 dark:text-blue-400 flex items-center justify-center transition"
                            title="Cetak">
                            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                        </a>
                    @endcan

                    @can('kontrakkerja.create')
                        @if ($d->status == '1' && empty($d->no_kontrak_baru) && $d->status_pemutihan === '0')
                            <button type="button" kode_penilaian="{{ Crypt::encrypt($d->kode_penilaian) }}"
                                class="btnCreatekontrak w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                title="Buat Kontrak">
                                <i data-lucide="file-plus-2" class="w-3.5 h-3.5"></i>
                            </button>
                        @endif
                    @endcan

                    @can('kontrakkerja.show')
                        @if ($d->status == '1' && !empty($d->no_kontrak_baru))
                            <a href="{{ route('kontrakkerja.cetak', Crypt::encrypt($d->no_kontrak_baru)) }}" target="_blank"
                                class="w-7 h-7 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:hover:bg-emerald-900/60 dark:text-emerald-400 flex items-center justify-center transition"
                                title="Cetak Kontrak">
                                <i data-lucide="file-check" class="w-3.5 h-3.5"></i>
                            </a>
                        @endif
                    @endcan

                    @can('penilaiankaryawan.approve')
                        @if (($user_can_approve || $is_admin) && $d->status === '0')
                            <button type="button" kode_penilaian="{{ Crypt::encrypt($d->kode_penilaian) }}"
                                class="btnApprove h-7 px-2.5 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-700 dark:bg-sky-950/50 dark:hover:bg-sky-900/60 dark:text-sky-400 text-xs font-bold inline-flex items-center gap-1 transition cursor-pointer"
                                title="Approve">
                                <i data-lucide="check-square" class="w-3.5 h-3.5"></i>
                                <span>Approve</span>
                            </button>
                        @elseif (($is_next_of_user && $d->status === '0') || ($user_can_approve && $d->status === '1'))
                            <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                action="{{ route('penilaiankaryawan.cancel', Crypt::encrypt($d->kode_penilaian)) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="cancel-confirm w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                    title="Batalkan">
                                    <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
                                </button>
                            </form>
                        @endif
                    @endcan

                    @can('penilaiankaryawan.delete')
                        @if (($is_creator && $is_pending && ($is_at_first_step || $user_can_approve)) || $is_admin)
                            <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                action="{{ route('penilaiankaryawan.delete', Crypt::encrypt($d->kode_penilaian)) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="delete-confirm w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                    title="Hapus">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </form>
                        @endif
                    @endcan
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-10 text-center">
                <div class="flex flex-col items-center justify-center gap-2 text-slate-400">
                    <i data-lucide="star-off" class="w-10 h-10 text-slate-300 dark:text-slate-600"></i>
                    <h5 class="text-sm font-bold text-slate-700 dark:text-slate-200">Data Tidak Ditemukan</h5>
                    <p class="text-xs text-slate-400">Maaf, data penilaian karyawan tidak tersedia atau tidak ditemukan sesuai filter.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    @if ($penilaiankaryawan->hasPages())
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 shadow-2xs">
            {{ $penilaiankaryawan->links('v2.components.pagination') }}
        </div>
    @endif
</div>

<!-- ================= MODAL PENILAIAN KARYAWAN (STATIC BACKDROP) ================= -->
<div id="modalPenilaian" class="fixed inset-0 z-50 hidden">
    <div id="modalPenilaianBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalPenilaianDialog"
            class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            <!-- Modal Header Bersih -->
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 rounded-t-xl">
                <div class="flex items-center gap-2">
                    <i data-lucide="star" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalPenilaianTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Penilaian Karyawan</h5>
                </div>
                <button type="button" id="btnCloseModalPenilaian"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="loadmodalPenilaian" class="p-4 sm:p-5 max-h-[85vh] overflow-y-auto"></div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        $('.select2Kodecabangsearch').select2({
            placeholder: 'Semua Cabang',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        $('.select2Kodedeptsearch').select2({
            placeholder: 'Semua Departemen',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        const $modal = $('#modalPenilaian');
        const $dialog = $('#modalPenilaianDialog');
        const $backdrop = $('#modalPenilaianBackdrop');
        const $title = $('#modalPenilaianTitle');
        const $content = $('#loadmodalPenilaian');

        function openModal(sizeClass) {
            $dialog.removeClass('max-w-lg max-w-2xl max-w-5xl').addClass(sizeClass);
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $content.html('');
        }

        window.closeModalPenilaian = closeModal;

        $('#btnCloseModalPenilaian').on('click', closeModal);

        $backdrop.on('click', function(e) {
            e.stopPropagation();
            $dialog.addClass('scale-[1.015]');
            setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
        });

        function showLoading() {
            $content.html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500">Memuat data...</span>
                </div>
            `);
        }

        $('#btnCreatePenilaian').on('click', function(e) {
            e.preventDefault();
            $title.text('Buat Penilaian Karyawan');
            openModal('max-w-lg');
            showLoading();
            $content.load('/penilaiankaryawan/create', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '.btnApprove', function(e) {
            e.preventDefault();
            const kode_penilaian = $(this).attr('kode_penilaian');
            $title.text('Approve Penilaian Karyawan');
            openModal('max-w-5xl');
            showLoading();
            $content.load(`/penilaiankaryawan/${kode_penilaian}/approve`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '.btnDetail', function(e) {
            e.preventDefault();
            const kode_penilaian = $(this).attr('kode_penilaian');
            $title.text('Detail Penilaian Karyawan');
            openModal('max-w-5xl');
            showLoading();
            $content.load(`/penilaiankaryawan/${kode_penilaian}/show`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '.btnEditPosisi', function(e) {
            e.preventDefault();
            const kode_penilaian = $(this).attr('kode_penilaian');
            $title.text('Edit Posisi Ajuan');
            openModal('max-w-lg');
            showLoading();
            $content.load(`/penilaiankaryawan/${kode_penilaian}/editposisi`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '.btnCreatekontrak', function(e) {
            e.preventDefault();
            const kode_penilaian = $(this).attr('kode_penilaian');
            $title.text('Buat Kontrak');
            openModal('max-w-lg');
            showLoading();
            $content.load(`/kesepakatanbersama/${kode_penilaian}/createkontrak`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '.btnCreatekb', function(e) {
            e.preventDefault();
            const kode_penilaian = $(this).attr('kode_penilaian');
            $title.text('Buat Kesepakatan Bersama');
            openModal('max-w-lg');
            showLoading();
            $content.load(`/kesepakatanbersama/${kode_penilaian}/create`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '.cancel-confirm', function(e) {
            e.preventDefault();
            const $form = $(this).closest('form');
            Swal.fire({
                title: 'Batalkan Approval?',
                text: 'Approval pada tahap ini akan dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#284c9a',
                cancelButtonColor: '#e11d48',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tutup'
            }).then((result) => {
                if (result.isConfirmed) {
                    $form.submit();
                }
            });
        });
    });
</script>
@endpush

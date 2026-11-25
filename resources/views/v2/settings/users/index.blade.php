@extends('layouts.app')
@section('titlepage', 'Users')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Settings</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Users</span>
    </div>
@endsection

@section('content')
@php
    $totalUsers = method_exists($users, 'total') ? $users->total() : $users->count();
@endphp

<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION BUTTONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Manajemen Users</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Mengelola data akun pengguna, penempatan cabang/departemen, role, dan hak akses sistem.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @if (auth()->user()->hasRole('super admin'))
                <button type="button" id="btncreateUser"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah User</span>
                </button>
            @endif
        </div>
    </div>

    <!-- FILTER SECTION (NO-LABEL, COMPACT H-[34PX], LUCIDE PREFIX, ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('users.index') }}" method="GET" id="formSearch">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
                
                <!-- Cari User (Col-span 3) -->
                <div class="lg:col-span-3 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="name" id="name_search" value="{{ Request('name') }}"
                        placeholder="Cari Nama / Username..."
                        class="w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>

                <!-- Cabang (Col-span 2) -->
                <div class="lg:col-span-2 relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_search" class="select2CabangSearch w-full">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>
                                {{ textUpperCase($c->nama_cabang) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Departemen (Col-span 2) -->
                <div class="lg:col-span-2 relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="briefcase" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_dept" id="kode_dept_search" class="select2DeptSearch w-full">
                        <option value="">Semua Dept</option>
                        @foreach ($departemen as $dept)
                            <option value="{{ $dept->kode_dept }}" {{ Request('kode_dept') == $dept->kode_dept ? 'selected' : '' }}>
                                {{ $dept->nama_dept }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Role (Col-span 3) -->
                <div class="lg:col-span-3 relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="role_id" id="role_id_search" class="select2RoleSearch w-full">
                        <option value="">Semua Role</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r->id }}" {{ Request('role_id') == $r->id ? 'selected' : '' }}>
                                {{ strtoupper($r->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Aksi Filter (Col-span 2) -->
                <div class="lg:col-span-2 flex items-center gap-1.5">
                    <button type="submit"
                        class="flex-1 h-[34px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>
                    <a href="{{ route('users.index') }}" title="Reset Filter"
                        class="h-[34px] w-[34px] rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/60 text-slate-600 dark:text-slate-300 flex items-center justify-center transition shadow-2xs shrink-0 cursor-pointer">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

            </div>
        </form>
    </div>

    <!-- DATA TABLE CARD (V2 STANDARD ~36PX ROW HEIGHT, NO STACKED CELLS) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto min-h-[260px]">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead>
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="9" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="users" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">Data Users</span>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/15 text-white">
                                    Total: {{ number_format($totalUsers, 0, ',', '.') }} Users
                                </span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-12">NO.</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[160px]">NAMA</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">USERNAME</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[180px]">EMAIL</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-40">ROLE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-32">CABANG</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">DEPT</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">STATUS</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-32">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($users as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">
                                {{ $loop->iteration + (method_exists($users, 'firstItem') ? $users->firstItem() - 1 : 0) }}
                            </td>
                            <td class="py-1.5 px-3 font-bold text-slate-900 dark:text-slate-100">
                                {{ $d->name }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-400">
                                {{ $d->username }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                                {{ $d->email }}
                            </td>
                            <td class="py-1.5 px-3">
                                @foreach ($d->roles as $role)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300 border border-sky-200 dark:border-sky-800/60">
                                        {{ ucwords($role->name) }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="py-1.5 px-3 font-semibold text-slate-700 dark:text-slate-300">
                                {{ textCamelCase($d->nama_cabang) }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono-num font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    {{ textUpperCase($d->kode_dept) }}
                                </span>
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status == 1)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60">
                                        Non Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @if (!empty($d->fcm_token))
                                        <button type="button"
                                            onclick="navigator.clipboard.writeText('{{ $d->fcm_token }}'); if(typeof window.showToast==='function'){window.showToast('success','FCM Token berhasil disalin!');}"
                                            class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/50 dark:text-emerald-400 inline-flex items-center justify-center transition shadow-2xs cursor-pointer"
                                            title="Copy FCM Token">
                                            <i data-lucide="smartphone" class="w-3 h-3"></i>
                                        </button>
                                    @endif

                                    <a href="{{ route('users.impersonate', Crypt::encrypt($d->id)) }}"
                                        class="w-6 h-6 rounded-md bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-950/50 dark:text-amber-400 inline-flex items-center justify-center transition shadow-2xs"
                                        title="View As User">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                    </a>

                                    @if (auth()->user()->hasRole('super admin'))
                                        <a href="{{ route('users.createuserpermission', Crypt::encrypt($d->id)) }}"
                                            class="w-6 h-6 rounded-md bg-sky-50 text-sky-600 hover:bg-sky-100 dark:bg-sky-950/50 dark:text-sky-400 inline-flex items-center justify-center transition shadow-2xs"
                                            title="Permissions">
                                            <i data-lucide="shield-alert" class="w-3 h-3"></i>
                                        </a>
                                        <button type="button" id="{{ Crypt::encrypt($d->id) }}"
                                            class="pjpAccess w-6 h-6 rounded-md bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-950/50 dark:text-indigo-400 inline-flex items-center justify-center transition shadow-2xs cursor-pointer"
                                            title="Akses PJP">
                                            <i data-lucide="banknote" class="w-3 h-3"></i>
                                        </button>
                                        <button type="button" id="{{ Crypt::encrypt($d->id) }}"
                                            class="editUser w-6 h-6 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-950/50 dark:text-blue-400 inline-flex items-center justify-center transition shadow-2xs cursor-pointer"
                                            title="Edit User">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                        <form method="POST" action="{{ route('users.delete', Crypt::encrypt($d->id)) }}"
                                            class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 inline-flex items-center justify-center transition shadow-2xs cursor-pointer"
                                                title="Hapus User">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="users" class="w-8 h-8 stroke-[1.2] text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Belum ada data User</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION STRIP -->
        @if ($users->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $users->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- MODAL USERS (STATIC BACKDROP, EXPLICIT CLOSE) -->
<div class="modal fade" id="modalUser" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="user-cog" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 modal-title">Form User</h3>
                </div>
                <button type="button" data-bs-dismiss="modal"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="p-4 sm:p-5 max-h-[85vh] overflow-y-auto" id="loadmodalUser"></div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        $('.select2CabangSearch').select2({ placeholder: 'Semua Cabang', allowClear: true, width: '100%' });
        $('.select2DeptSearch').select2({ placeholder: 'Semua Dept', allowClear: true, width: '100%' });
        $('.select2RoleSearch').select2({ placeholder: 'Semua Role', allowClear: true, width: '100%' });

        $('#modalUser').on('click', function(e) {
            if ($(e.target).is('#modalUser')) {
                const $dialog = $(this).find('.modal-content');
                $dialog.addClass('scale-[1.015]');
                setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
            }
        });

        function loading() {
            $("#loadmodalUser").html(`
                <div class="flex flex-col items-center justify-center py-12 gap-3">
                    <svg class="animate-spin h-7 w-7 text-[#284c9a] dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Memuat formulir...</span>
                </div>
            `);
        }

        $("#btncreateUser").click(function(e) {
            e.preventDefault();
            loading();
            $('#modalUser').modal("show");
            $('#modalUser').find('.modal-title').text("Tambah User Baru");
            $("#loadmodalUser").load('/users/create', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(".editUser").click(function(e) {
            e.preventDefault();
            const id = $(this).attr("id");
            loading();
            $('#modalUser').modal("show");
            $('#modalUser').find('.modal-title').text("Edit Data User");
            $("#loadmodalUser").load(`/users/${id}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(".pjpAccess").click(function(e) {
            e.preventDefault();
            const id = $(this).attr("id");
            loading();
            $('#modalUser').modal("show");
            $('#modalUser').find('.modal-title').text("Pengaturan Akses PJP");
            $("#loadmodalUser").load(`/users/${id}/pjpaccess`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush

@extends('layouts.app')
@section('titlepage', 'Set User Permissions')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Settings</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('users.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">Users</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Set Permissions</span>
    </div>
@endsection

@section('content')
<form action="{{ route('users.storeuserpermission', Crypt::encrypt($user->id)) }}" method="POST" class="space-y-4">
    @csrf

    <!-- STICKY HEADER BAR -->
    <div class="sticky top-16 z-20 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur-sm p-3.5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-[#284c9a] text-white font-black text-sm flex items-center justify-center shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white leading-tight">{{ ucwords($user->name) }}</h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-[#284c9a] dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60">
                        {{ $user->getRoleNames()[0] ?? '-' }}
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 mt-0.5">Pengaturan izin akses spesifik per modul untuk user ini.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('users.index') }}"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali</span>
            </a>
            <button type="submit"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="save" class="w-3.5 h-3.5"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </div>

    <!-- PERMISSION CARDS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3.5">
        @foreach ($permissions as $d)
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs flex flex-col">
                <div class="px-3.5 py-2 bg-[#284c9a] text-white flex items-center justify-between">
                    <div class="flex items-center gap-1.5 min-w-0">
                        <i data-lucide="folder-check" class="w-3.5 h-3.5 text-blue-200 shrink-0"></i>
                        <span class="text-xs font-bold truncate">{{ $d->group_name }}</span>
                    </div>
                    <input type="checkbox" class="select-all-group w-3.5 h-3.5 rounded-sm border-white/40 text-[#1e3a78] cursor-pointer"
                        data-group="{{ $d->id_permission_group }}" title="Pilih Semua di Grup Ini">
                </div>

                <div class="p-3 space-y-1 flex-1">
                    @php
                        $list_permissions = explode(',', $d->permissions);
                    @endphp
                    @foreach ($list_permissions as $p)
                        @php
                            $permission = explode('-', $p);
                            $permission_id = $permission[0];
                            $permission_name = $permission[1];
                            $isInherited = in_array($permission_name, $rolepermissions);
                            $isDirect = in_array($permission_name, $userpermissions);
                            $isChecked = $isInherited || $isDirect;
                        @endphp
                        <label for="p-{{ $permission_id }}"
                            class="flex items-center justify-between px-2 py-1 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/60 cursor-pointer transition">
                            <div class="flex items-center gap-2 min-w-0">
                                <input type="checkbox" name="permission[]" value="{{ $permission_name }}"
                                    id="p-{{ $permission_id }}" {{ $isChecked ? 'checked' : '' }}
                                    class="permission-checkbox group-{{ $d->id_permission_group }} w-3.5 h-3.5 rounded-sm border-slate-300 text-[#284c9a] cursor-pointer shrink-0">
                                <span class="text-xs font-medium text-slate-700 dark:text-slate-300 truncate">{{ str_replace('.', ' ', $permission_name) }}</span>
                            </div>
                            @if ($isInherited)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 shrink-0" title="Inherited from Role">
                                    Role
                                </span>
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

</form>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $('.select-all-group').on('change', function() {
            const groupId = $(this).data('group');
            $('.group-' + groupId).prop('checked', $(this).prop('checked'));
        });

        $('.permission-checkbox').on('change', function() {
            const classes = $(this).attr('class').split(' ');
            const groupClass = classes.filter(c => c.startsWith('group-'))[0];
            const groupId = groupClass.replace('group-', '');
            const total = $('.' + groupClass).length;
            const checked = $('.' + groupClass + ':checked').length;
            $('.select-all-group[data-group="' + groupId + '"]').prop('checked', total === checked);
        });
    });
</script>
@endpush

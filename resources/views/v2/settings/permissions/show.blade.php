<form action="{{ route('permissions.updateAccess', Crypt::encrypt($permission->id)) }}" id="formPermissionAccess" method="POST" class="space-y-4">
    @csrf

    <!-- Executive Summary Strip -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 p-3">
        <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Nama Permission</span>
        <span class="text-sm font-black font-mono-num text-[#284c9a] dark:text-blue-400">{{ $permission->name }}</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        
        <!-- Roles List -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 space-y-2">
            <div class="flex items-center gap-1.5 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                <i data-lucide="shield" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Roles dengan Akses</h4>
            </div>
            @if ($roles->isEmpty())
                <p class="text-xs text-slate-400 py-3 text-center">Tidak ada role yang memiliki akses.</p>
            @else
                <div class="max-h-[240px] overflow-y-auto space-y-1">
                    @foreach ($roles as $role)
                        <label class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/60 cursor-pointer transition">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" checked
                                class="w-3.5 h-3.5 rounded-sm border-slate-300 text-[#284c9a]">
                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ ucwords($role->name) }}</span>
                        </label>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Users List -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 space-y-2">
            <div class="flex items-center gap-1.5 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                <i data-lucide="users" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Users dengan Akses Langsung</h4>
            </div>
            @if ($users->isEmpty())
                <p class="text-xs text-slate-400 py-3 text-center">Tidak ada user dengan akses langsung.</p>
            @else
                <div class="max-h-[240px] overflow-y-auto space-y-1">
                    @foreach ($users as $user)
                        <label class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/60 cursor-pointer transition">
                            <input type="checkbox" name="users[]" value="{{ $user->id }}" checked
                                class="w-3.5 h-3.5 rounded-sm border-slate-300 text-[#284c9a]">
                            <div class="min-w-0">
                                <span class="block text-xs font-semibold text-slate-800 dark:text-slate-200 truncate">{{ $user->name }}</span>
                                <span class="block text-[10px] font-mono-num text-slate-400">{{ $user->username }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" data-bs-dismiss="modal"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanAccess"
            class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan Perubahan Akses</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $('#formPermissionAccess').submit(function() {
            const $btn = $('#btnSimpanAccess');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> <span>Menyimpan...</span>`);
        });
    });
</script>

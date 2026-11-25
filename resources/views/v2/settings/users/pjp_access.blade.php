<form action="{{ route('users.pjpaccess.update', Crypt::encrypt($user->id)) }}" id="formPjpAccess" method="POST" class="space-y-3">
    @csrf

    <!-- User Summary -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 p-3 flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-[#284c9a] text-white font-black text-xs flex items-center justify-center shrink-0">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <div class="text-xs font-bold text-slate-900 dark:text-slate-100">{{ $user->name }}</div>
            <div class="text-[11px] text-slate-400">Mengatur hak akses khusus modul PJP</div>
        </div>
    </div>

    <!-- Kategori Jabatan -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 space-y-2.5">
        <div class="flex items-center gap-2 pb-2 mb-2 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="lock" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Akses Khusus PJP</h4>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
            <label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200 dark:border-slate-800 cursor-pointer hover:border-[#284c9a] transition">
                <input type="checkbox" name="pjp_kategori_jabatan_access[]" value="MJ" {{ in_array('MJ', $pjp_kategori_jabatan_access) ? 'checked' : '' }}
                    class="w-4 h-4 rounded-sm border-slate-300 text-[#284c9a]">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Management (M)</span>
            </label>
            <label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200 dark:border-slate-800 cursor-pointer hover:border-[#284c9a] transition">
                <input type="checkbox" name="pjp_kategori_jabatan_access[]" value="NM" {{ in_array('NM', $pjp_kategori_jabatan_access) ? 'checked' : '' }}
                    class="w-4 h-4 rounded-sm border-slate-300 text-[#284c9a]">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Non-Management (NM)</span>
            </label>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Akses Cabang PJP</label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="pjp_cabang_access[]" multiple class="select2Pjp w-full" data-placeholder="Pilih Cabang PJP">
                        <option value="all" {{ in_array('all', $pjp_cabang_access ?? []) ? 'selected' : '' }}>Semua Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ in_array($c->kode_cabang, $pjp_cabang_access ?? []) ? 'selected' : '' }}>{{ textUpperCase($c->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Akses Departemen PJP</label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="briefcase" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="pjp_dept_access[]" multiple class="select2Pjp w-full" data-placeholder="Pilih Departemen PJP">
                        <option value="all" {{ in_array('all', $pjp_dept_access ?? []) ? 'selected' : '' }}>Semua Departemen</option>
                        @foreach ($departemen as $dept)
                            <option value="{{ $dept->kode_dept }}" {{ in_array($dept->kode_dept, $pjp_dept_access ?? []) ? 'selected' : '' }}>{{ $dept->nama_dept }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Akses Jabatan PJP</label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="award" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="pjp_jabatan_access[]" multiple class="select2Pjp w-full" data-placeholder="Pilih Jabatan PJP">
                        <option value="all" {{ in_array('all', $pjp_jabatan_access ?? []) ? 'selected' : '' }}>Semua Jabatan</option>
                        @foreach ($jabatan as $j)
                            <option value="{{ $j->kode_jabatan }}" {{ in_array($j->kode_jabatan, $pjp_jabatan_access ?? []) ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Akses Grup PJP</label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="pjp_group_access[]" multiple class="select2Pjp w-full" data-placeholder="Pilih Grup PJP">
                        @foreach ($group as $g)
                            <option value="{{ $g->kode_group }}" {{ in_array($g->kode_group, $pjp_group_access ?? []) ? 'selected' : '' }}>{{ $g->nama_group }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Akses Karyawan PJP</label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="users" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="pjp_karyawan_access[]" multiple class="select2Pjp w-full" data-placeholder="Pilih Karyawan PJP">
                        <option value="all" {{ in_array('all', $pjp_karyawan_access ?? []) ? 'selected' : '' }}>Semua Karyawan</option>
                        @foreach ($karyawan as $k)
                            <option value="{{ $k->nik }}" {{ in_array($k->nik, $pjp_karyawan_access ?? []) ? 'selected' : '' }}>{{ $k->nik }} - {{ $k->nama_karyawan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Footer Actions -->
    <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" data-bs-dismiss="modal"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanPjp"
            class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan Akses PJP</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const form = $("#formPjpAccess");
        form.find('.select2Pjp').each(function() {
            $(this).select2({ placeholder: $(this).data('placeholder') || 'Pilih Opsi', allowClear: true, width: '100%' });
        });

        form.submit(function() {
            const $btn = form.find("#btnSimpanPjp");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> <span>Menyimpan...</span>`);
        });
    });
</script>

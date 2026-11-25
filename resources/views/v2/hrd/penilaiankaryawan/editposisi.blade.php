<form action="{{ route('penilaiankaryawan.updateposisi', Crypt::encrypt($penilaian->kode_penilaian)) }}" method="POST" id="formEditPosisi" class="space-y-2.5">
    @csrf
    @method('PUT')

    <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Kode Penilaian</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="barcode" class="w-4 h-4"></i>
            </div>
            <input type="text" name="kode_penilaian" value="{{ $penilaian->kode_penilaian }}" readonly
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-mono-num font-bold text-slate-700 dark:text-slate-200">
        </div>
    </div>

    <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Nama Karyawan</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="user" class="w-4 h-4"></i>
            </div>
            <input type="text" name="nama_karyawan" value="{{ $penilaian->nama_karyawan }}" readonly
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-700 dark:text-slate-200">
        </div>
    </div>

    <div>
        <label for="status_edit_posisi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Status <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            </div>
            <select name="status" id="status_edit_posisi"
                class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                <option value="0" @selected($penilaian->status == '0')>Pending</option>
                <option value="1" @selected($penilaian->status == '1')>Disetujui</option>
                <option value="2" @selected($penilaian->status == '2')>Ditolak</option>
            </select>
        </div>
    </div>

    <div>
        <label for="posisi_ajuan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Posisi Ajuan
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="git-branch" class="w-4 h-4"></i>
            </div>
            <select name="posisi_ajuan" id="posisi_ajuan_edit"
                class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                <option value="">Pilih Posisi</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected($penilaian->posisi_ajuan == $role->id)>{{ textUpperCase($role->name) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" onclick="window.closeModalPenilaian && window.closeModalPenilaian()"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitEditPosisi"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Update Posisi</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
        $('#formEditPosisi').on('submit', function() {
            const $btn = $('#btnSubmitEditPosisi');
            $btn.prop('disabled', true)
                .addClass('opacity-75 cursor-not-allowed pointer-events-none')
                .html(`
                    <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Menyimpan...</span>
                `);
        });
    });
</script>

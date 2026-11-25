<form action="{{ route('penyupah.updatekaryawan', [Crypt::encrypt($kode_gaji), Crypt::encrypt($detail->nik)]) }}" method="POST" id="formEditKaryawanPenyupah" autocomplete="off" class="space-y-2.5">
    @csrf
    @method('PUT')

    <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Karyawan</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="user" class="w-4 h-4"></i>
            </div>
            <input type="text" value="{{ $detail->nik }} - {{ $detail->nama_karyawan }}" readonly disabled
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-600 dark:text-slate-300 cursor-not-allowed">
        </div>
    </div>

    <div>
        <label for="pengurang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Pengurang (-)</label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="minus-circle" class="w-4 h-4"></i>
            </div>
            <input type="text" name="pengurang" id="pengurang_edit" value="{{ formatAngka($detail->pengurang) }}" placeholder="0"
                class="money w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-mono-num font-medium text-right text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
        </div>
    </div>

    <div>
        <label for="penambah_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Penambah (+)</label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
            </div>
            <input type="text" name="penambah" id="penambah_edit" value="{{ formatAngka($detail->penambah) }}" placeholder="0"
                class="money w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-mono-num font-medium text-right text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
        </div>
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" onclick="window.closeModalKaryawanPenyupah && window.closeModalKaryawanPenyupah()"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnUpdateKaryawanPenyupah"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formEditKaryawanPenyupah');
        if ($.fn.maskMoney) $form.find('.money').maskMoney();

        $form.on('submit', function() {
            const $btn = $form.find('#btnUpdateKaryawanPenyupah');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg><span>Menyimpan...</span>`);
        });
    });
</script>

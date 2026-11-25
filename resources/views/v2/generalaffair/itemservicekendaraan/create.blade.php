<form action="#" id="formItemservicekendaraan" class="space-y-2.5">
    
    <!-- Kode Item (Auto) -->
    <div>
        <label for="kode_item_modal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Kode Item
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="kode_item" id="kode_item_modal" placeholder="Otomatis" disabled
                class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
        </div>
    </div>

    <!-- Nama Item -->
    <div>
        <label for="nama_item" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Nama Item <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="wrench" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="nama_item" id="nama_item" placeholder="Masukkan Nama Item / Jasa"
                class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
        </div>
    </div>

    <!-- Jenis Item -->
    <div>
        <label for="jenis_item" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Jenis Item <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="layers" class="w-3.5 h-3.5"></i>
            </div>
            <select name="jenis_item" id="jenis_item"
                class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                <option value="">Pilih Jenis Item</option>
                <option value="OLI">OLI</option>
                <option value="JASA">JASA</option>
                <option value="PART">PART</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-3 h-3"></i>
            </div>
        </div>
    </div>

    <!-- Modal Footer Actions -->
    <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" data-bs-dismiss="modal"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanItem"
            class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Item</span>
        </button>
    </div>

</form>

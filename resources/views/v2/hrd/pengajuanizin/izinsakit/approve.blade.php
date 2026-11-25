<form action="{{ route('izinsakit.storeapprove', Crypt::encrypt($izinsakit->kode_izin_sakit)) }}" method="POST" id="formApproveIzinSakit" class="space-y-3">
    @csrf

    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 p-3 sm:p-3.5 divide-y divide-dashed divide-slate-200 dark:divide-slate-700 text-xs">
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Kode</span>
            <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $izinsakit->kode_izin_sakit }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Tanggal</span>
            <span class="font-semibold text-slate-800 dark:text-slate-100">{{ DateToIndo($izinsakit->tanggal) }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">NIK</span>
            <span class="font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $izinsakit->nik }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Nama Karyawan</span>
            <span class="font-bold text-slate-800 dark:text-slate-100 text-right">{{ $izinsakit->nama_karyawan }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Jabatan</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $izinsakit->nama_jabatan }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Departemen</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $izinsakit->nama_dept }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Cabang</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ textUpperCase($izinsakit->nama_cabang) }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Periode Tanggal</span>
            <span class="font-mono-num font-semibold text-slate-700 dark:text-slate-200">{{ formatIndo($izinsakit->dari) }} - {{ formatIndo($izinsakit->sampai) }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Lama</span>
            <span class="font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ hitungHari($izinsakit->dari, $izinsakit->sampai) }} Hari</span>
        </div>
        <div class="py-1.5 flex items-start justify-between gap-2">
            <span class="text-slate-400 shrink-0">Keterangan</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $izinsakit->keterangan }}</span>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" onclick="window.closeModalIzinSakit && window.closeModalIzinSakit()"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer">
            Batal
        </button>

        @if ($level_user != 'direktur')
            @if (!in_array($level_user, $level_hrd))
                <button type="submit" id="btnSimpanApprove"
                    class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="thumbs-up" class="w-3.5 h-3.5"></i>
                    <span>Setuju, Teruskan ke HRD</span>
                </button>
            @else
                <button type="submit" id="btnSimpanApprove"
                    class="h-[36px] px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="thumbs-up" class="w-3.5 h-3.5"></i>
                    <span>Setuju</span>
                </button>
                @if ($izinsakit->kategori_jabatan == 'MJ')
                    <button type="submit" id="btnTeruskanApprove" name="direktur" value="1"
                        class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                        <i data-lucide="thumbs-up" class="w-3.5 h-3.5"></i>
                        <span>Setuju & Teruskan ke Direktur</span>
                    </button>
                @endif
            @endif
        @else
            <button type="submit" id="btnSimpanApprove"
                class="h-[36px] px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="thumbs-up" class="w-3.5 h-3.5"></i>
                <span>Setuju</span>
            </button>
        @endif
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formApproveIzinSakit');
        $form.find('#btnTeruskanApprove').on('click', function() {
            $form.find('input[name="direktur"]').remove();
            $form.append('<input type="hidden" name="direktur" value="1">');
        });
        $form.on('submit', function() {
            const $btns = $form.find('#btnSimpanApprove, #btnTeruskanApprove');
            $btns.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btns.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg><span>Memproses...</span>`);
        });
    });
</script>

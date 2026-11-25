<form action="{{ route('harilibur.storeapprove', Crypt::encrypt($harilibur->kode_libur)) }}" method="POST" id="formApproveHariLibur" class="space-y-3">
    @csrf

    <!-- Info Hari Libur -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 p-3 sm:p-3.5 divide-y divide-dashed divide-slate-200 dark:divide-slate-700 text-xs">
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Kode Libur</span>
            <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $harilibur->kode_libur }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Tanggal</span>
            <span class="font-bold text-slate-800 dark:text-slate-100">{{ DateToIndo($harilibur->tanggal) }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Kategori</span>
            <span class="px-2 py-0.5 rounded bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 text-[11px] font-bold">{{ $harilibur->nama_kategori }}</span>
        </div>
        @if (!empty($harilibur->tanggal_diganti))
            <div class="py-1.5 flex items-center justify-between gap-2">
                <span class="text-slate-400">Pengganti Tanggal</span>
                <span class="font-semibold text-slate-700 dark:text-slate-200">{{ DateToIndo($harilibur->tanggal_diganti) }}</span>
            </div>
        @endif
        @if (!empty($harilibur->tanggal_limajam))
            <div class="py-1.5 flex items-center justify-between gap-2">
                <span class="text-slate-400">Tanggal 5 Jam</span>
                <span class="font-semibold text-slate-700 dark:text-slate-200">{{ DateToIndo($harilibur->tanggal_limajam) }}</span>
            </div>
        @endif
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Cabang</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $harilibur->nama_cabang }}</span>
        </div>
        @if ($harilibur->kode_cabang == 'PST')
            <div class="py-1.5 flex items-center justify-between gap-2">
                <span class="text-slate-400">Departemen</span>
                <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $harilibur->nama_dept }}</span>
            </div>
        @endif
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Keterangan</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $harilibur->keterangan }}</span>
        </div>
    </div>

    <!-- Daftar Karyawan -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto max-h-[320px]">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider sticky top-0 z-10">
                    <tr>
                        <th class="py-2 px-3 text-center w-12">NO.</th>
                        <th class="py-2 px-3">NIK</th>
                        <th class="py-2 px-3">NAMA KARYAWAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($detail as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">{{ $d->nik }}</td>
                            <td class="py-1.5 px-3 font-bold text-slate-800 dark:text-slate-100">{{ $d->nama_karyawan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-6 px-3 text-center text-xs text-slate-400">Belum ada karyawan yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" onclick="window.closeModalHariLibur && window.closeModalHariLibur()"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnApproveSubmit"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="thumbs-up" class="w-3.5 h-3.5"></i>
            <span>Approve</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formApproveHariLibur');
        $form.on('submit', function() {
            const $btn = $form.find('#btnApproveSubmit');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses...</span>
            `);
        });
    });
</script>

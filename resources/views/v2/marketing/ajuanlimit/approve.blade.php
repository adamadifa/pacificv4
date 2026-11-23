<form action="{{ route('ajuanlimit.approvestore', Crypt::encrypt($ajuanlimit->no_pengajuan)) }}" id="formApprovelimit" method="POST" class="space-y-3.5">
    @csrf

    <!-- INFO WEWENANG / TAHAP PERSETUJUAN -->
    <div class="rounded-xl border border-blue-200 dark:border-blue-900/60 bg-blue-50/70 dark:bg-blue-950/40 p-3 flex items-start gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-blue-100 dark:bg-blue-900/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0 mt-0.5">
            <i data-lucide="shield-alert" class="w-4 h-4"></i>
        </div>
        <div class="text-xs">
            <span class="font-bold text-slate-800 dark:text-slate-100 block">Jalur Persetujuan Wewenang Limit</span>
            <p class="text-slate-600 dark:text-slate-300 mt-0.5 leading-relaxed">
                Nominal ajuan: <b class="text-[#284c9a] dark:text-blue-400 font-mono-num">Rp {{ number_format($ajuanlimit->jumlah, 0, ',', '.') }}</b>.
                @if ($is_final_approver)
                    <span class="inline-flex items-center gap-1 font-bold text-emerald-600 dark:text-emerald-400">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5 inline"></i> Anda adalah pemberi persetujuan akhir (Final Approver).
                    </span>
                @elseif (!empty($next_role))
                    Setelah disetujui, pengajuan akan diteruskan ke wewenang: <b class="uppercase text-slate-900 dark:text-white">{{ $next_role }}</b>.
                @endif
            </p>
        </div>
    </div>

    <!-- FOTO TOKO & OWNER PREVIEW -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="flex items-center gap-3 p-2 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800">
                <div class="w-16 h-16 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0">
                    @if (!empty($ajuanlimit->foto) && Storage::disk('public')->exists('/pelanggan/' . $ajuanlimit->foto))
                        <img src="{{ getfotoPelanggan($ajuanlimit->foto) }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Foto Toko</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100 block mt-0.5">{{ $ajuanlimit->nama_pelanggan }}</span>
                    <span class="text-[11px] text-slate-500 block truncate max-w-[200px]">{{ $ajuanlimit->alamat_pelanggan }}</span>
                </div>
            </div>

            <div class="flex items-center gap-3 p-2 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800">
                <div class="w-16 h-16 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0">
                    @if (!empty($ajuanlimit->foto_owner) && Storage::disk('public')->exists('/pelanggan/owner/' . $ajuanlimit->foto_owner))
                        <img src="{{ getfotoPelangganowner($ajuanlimit->foto_owner) }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Foto Owner</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100 block mt-0.5">{{ $ajuanlimit->nama_pelanggan }}</span>
                    <span class="text-[11px] font-mono-num text-slate-500 block">NIK: {{ $ajuanlimit->nik ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- DETAIL INFORMASI UTAMA & PENDUKUNG (2 COLUMNS) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 items-start">
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 bg-white dark:bg-slate-900 space-y-1.5 text-xs">
            <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100">
                <i data-lucide="file-text" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <span>Data Pengajuan</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                <span class="text-slate-400">No. Pengajuan</span>
                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $ajuanlimit->no_pengajuan }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                <span class="text-slate-400">Nama Pelanggan</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">{{ $ajuanlimit->nama_pelanggan }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                <span class="text-slate-400">Cabang & Sales</span>
                <span class="text-slate-700 dark:text-slate-300">{{ textUpperCase($ajuanlimit->nama_cabang) }} - {{ textUpperCase($ajuanlimit->nama_salesman) }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                <span class="text-slate-400">Nominal Ajuan</span>
                <span class="font-mono-num font-black text-emerald-600 dark:text-emerald-400 text-sm">Rp {{ number_format($ajuanlimit->jumlah, 0, ',', '.') }}</span>
            </div>
            @if (!empty($ajuanlimit->jumlah_rekomendasi))
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-400">Rekomendasi Adjust</span>
                    <span class="font-mono-num font-black text-[#284c9a] dark:text-blue-400 text-sm">Rp {{ number_format($ajuanlimit->jumlah_rekomendasi, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between py-1">
                <span class="text-slate-400">LJT Tenor</span>
                <span class="font-mono-num font-bold text-slate-800 dark:text-slate-200">{{ $ajuanlimit->ljt }} Hari</span>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 bg-white dark:bg-slate-900 space-y-1.5 text-xs">
            <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100">
                <i data-lucide="sliders" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <span>Profil & Kelayakan</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                <span class="text-slate-400">Kepemilikan Toko</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $kepemilikan[$ajuanlimit->kepemilikan] ?? '-' }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                <span class="text-slate-400">Lama Usaha</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $lama_berjualan[$ajuanlimit->lama_berjualan] ?? '-' }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                <span class="text-slate-400">Status Outlet</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $status_outlet[$ajuanlimit->status_outlet] ?? '-' }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
                <span class="text-slate-400">Skor Kelayakan</span>
                <span class="font-mono-num font-black text-[#284c9a] dark:text-blue-400 text-sm">{{ formatAngkaDesimal($ajuanlimit->skor) }}</span>
            </div>
            <div class="flex justify-between py-1">
                <span class="text-slate-400">Omset Toko</span>
                <span class="font-mono-num font-bold text-slate-900 dark:text-slate-100">Rp {{ number_format($ajuanlimit->omset_toko ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- RIWAYAT DISPOSISI -->
    @if ($disposisi->isNotEmpty())
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 bg-white dark:bg-slate-900 space-y-2">
            <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100">
                <i data-lucide="git-commit" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <span>Catatan Approval Sebelumnya</span>
            </div>
            <div class="relative pl-5 space-y-2.5 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200 dark:before:bg-slate-800 text-xs">
                @foreach ($disposisi as $disp)
                    <div class="relative">
                        <div class="absolute -left-5 top-1 w-2.5 h-2.5 rounded-full {{ $disp->status == '1' ? 'bg-emerald-500' : ($disp->status == '2' ? 'bg-rose-500' : 'bg-[#284c9a]') }}"></div>
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-800 dark:text-slate-100">{{ $disp->username }} ({{ textUpperCase($disp->role) }})</span>
                            <span class="text-[10.5px] font-mono-num text-slate-400">{{ date('d M Y H:i', strtotime($disp->created_at)) }}</span>
                        </div>
                        @if (!empty($disp->uraian_analisa))
                            <p class="mt-0.5 text-slate-600 dark:text-slate-300 text-[11px] bg-slate-50 dark:bg-slate-800/60 p-1.5 rounded border border-slate-100 dark:border-slate-800">
                                {{ $disp->uraian_analisa }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- CATATAN / ANALISA APPROVAL -->
    <div class="field-group">
        <label for="uraian_analisa_app" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Catatan / Uraian Analisa Disposisi
        </label>
        <textarea name="uraian_analisa" id="uraian_analisa_app" rows="2" placeholder="Tuliskan catatan, rekomendasi, atau alasan keputusan persetujuan/penolakan..."
            class="w-full p-2.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition"></textarea>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-2">
        <button type="submit" name="decline" value="1" id="btnDecline"
            class="h-[34px] px-4 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/50 dark:text-rose-400 border border-rose-200 dark:border-rose-900 text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
            <span>Tolak Pengajuan</span>
        </button>

        <div class="flex items-center gap-2">
            <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
                Batal
            </button>
            <button type="submit" name="approve" value="1" id="btnApproveSubmit"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span id="btnApproveText">
                    {{ $is_final_approver ? 'Setujui (Final Approval)' : 'Setujui & Teruskan' }}
                </span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $('#formApprovelimit');
        let isDecline = false;

        $('#btnDecline').on('click', function(e) {
            isDecline = true;
            if (!confirm('Apakah Anda yakin ingin MENOLAK pengajuan limit kredit ini?')) {
                e.preventDefault();
                isDecline = false;
                return false;
            }
        });

        $('#btnApproveSubmit').on('click', function() {
            isDecline = false;
        });

        $form.on('submit', function() {
            $('#btnApproveSubmit, #btnDecline').prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            if (!isDecline) {
                $('#btnApproveText').html(`
                    <svg class="animate-spin -ml-1 mr-2 h-3.5 w-3.5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                `);
            }
        });
    });
</script>

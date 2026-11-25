<form action="{{ route('ticket.store') }}" method="POST" id="formTicketCreate" enctype="multipart/form-data" class="space-y-3">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="tanggal_ticket_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal Pengajuan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal_ticket_create" value="{{ date('Y-m-d') }}" readonly class="flatpickr-date w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="Pilih Tanggal">
            </div>
        </div>

        <div>
            <label for="priority_ticket_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tingkat Prioritas
            </label>
            <div class="relative">
                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="flag" class="w-3.5 h-3.5"></i>
                </div>
                <select name="priority" id="priority_ticket_create" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
                    <option value="Sedang" selected>Sedang (Normal)</option>
                    <option value="Rendah">Rendah (Low)</option>
                    <option value="Tinggi">Tinggi (High)</option>
                    <option value="Urgent">Urgent (Immediate)</option>
                </select>
            </div>
        </div>
    </div>

    <div>
        <label for="id_kategori_ticket_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Jenis Tiket Ajuan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="tags" class="w-3.5 h-3.5"></i>
            </div>
            <select name="id_kategori" id="id_kategori_ticket_create" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
                <option value="">-- Pilih Jenis Tiket Ajuan --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Category Dynamic Notice Card -->
    <div id="categoryNoticeCard" class="hidden rounded-xl bg-sky-50 dark:bg-sky-950/40 border border-sky-200 dark:border-sky-800 p-3">
        <div class="flex items-center justify-between gap-2 mb-1.5">
            <span class="text-xs font-bold text-sky-700 dark:text-sky-300 flex items-center gap-1.5">
                <i data-lucide="info" class="w-3.5 h-3.5"></i>
                <span>Informasi & Persyaratan Kategori Tiket</span>
            </span>
            <a href="#" id="btnDownloadTemplate" target="_blank" class="hidden inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-sky-600 hover:bg-sky-700 text-white text-[11px] font-bold transition">
                <i data-lucide="download" class="w-3 h-3"></i>
                <span>Download Template</span>
            </a>
        </div>
        <p id="categoryKeteranganText" class="text-[11px] text-slate-600 dark:text-slate-300"></p>
        <div id="lampiranNotice" class="hidden mt-1.5 text-[11px] font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Kategori ini WAJIB melampirkan file dokumen format hasil isi template!</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="judul_ticket_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Judul / Ringkasan Tiket <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="heading" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="judul" id="judul_ticket_create" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="Contoh: Tambah Menu Laporan Baru">
            </div>
        </div>

        <div>
            <label for="no_bukti_ticket_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. Faktur / No. Bukti (Opsional)
            </label>
            <div class="relative">
                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="no_bukti" id="no_bukti_ticket_create" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="Contoh: FK2026070001">
            </div>
        </div>
    </div>

    <div>
        <label for="keterangan_ticket_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Detail Keterangan & Alasan Pengajuan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="field-icon absolute top-2.5 left-0 pl-2.5 flex items-start pointer-events-none text-slate-400 z-10">
                <i data-lucide="align-left" class="w-3.5 h-3.5"></i>
            </div>
            <textarea name="keterangan" id="keterangan_ticket_create" rows="3" class="w-full pl-8 pr-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="Jelaskan secara rinci detail kebutuhan atau perbaikan yang diajukan..."></textarea>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="lampiran_ticket_create" id="labelLampiran" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                File Lampiran Dokumen
            </label>
            <div class="relative">
                <input type="file" name="lampiran" id="lampiran_ticket_create" accept=".xlsx,.xls,.doc,.docx,.pdf,.jpg,.jpeg,.png,.zip,.rar" class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#284c9a] file:text-white hover:file:bg-[#1e3a78] bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg cursor-pointer">
            </div>
            <p class="text-[10px] text-slate-400 mt-1">Excel, Word, PDF, Gambar, Zip (Maks 10MB)</p>
        </div>

        <div>
            <label for="link_ticket_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Link URL / Referensi (Opsional)
            </label>
            <div class="relative">
                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="link" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="link" id="link_ticket_create" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="https://...">
            </div>
        </div>
    </div>

    <!-- Approval Flow Card -->
    <div class="rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5">
        <div class="flex items-center gap-1.5 mb-2.5">
            <i data-lucide="git-merge" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Kustomisasi Alur Persetujuan</h4>
        </div>
        @if (auth()->user()->kode_cabang != 'PST')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
                <div>
                    <label for="id_smm_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Persetujuan SMM</label>
                    <div class="relative">
                        <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="id_smm" id="id_smm_create" class="w-full h-[36px] pl-8 pr-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100">
                            @foreach ($smmList as $u)
                                <option value="{{ $u->id }}" {{ $u->id == auth()->user()->id ? '' : 'selected' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label for="id_gm_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Persetujuan GM</label>
                    <div class="relative">
                        <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="id_gm" id="id_gm_create" class="w-full h-[36px] pl-8 pr-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100">
                            @foreach ($gmList as $u)
                                <option value="{{ $u->id }}" {{ $u->id == 20 ? 'selected' : '' }}>
                                    {{ $u->name }} ({{ $u->roles->pluck('name')->first() == 'gm marketing' ? 'GM Mkt' : ($u->roles->pluck('name')->first() == 'gm operasional' ? 'GM Ops' : 'GM Adm') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @else
            <div>
                <label for="id_manager_dept_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Persetujuan Manager Departemen</label>
                <div class="relative">
                    <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="id_manager_dept" id="id_manager_dept_create" class="w-full h-[36px] pl-8 pr-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100">
                        @foreach ($managerList as $u)
                            <option value="{{ $u->id }}" {{ $u->id == auth()->user()->id ? '' : 'selected' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
    </div>

    <div class="rounded-lg bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 p-2.5 flex items-center gap-2 text-amber-800 dark:text-amber-300 text-[11px] font-medium">
        <i data-lucide="shield-check" class="w-4 h-4 shrink-0 text-amber-600 dark:text-amber-400"></i>
        <span>Tiket akan diproses sesuai alur persetujuan bertingkat cabang/departemen Anda sebelum dieksekusi oleh tim IT Admin.</span>
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" data-bs-dismiss="modal" class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition cursor-pointer">Batal</button>
        <button type="submit" id="btnSimpanTicket" class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold flex items-center gap-1.5 transition cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Kirim Tiket Ajuan Baru</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formTicketCreate');
        $form.find('.flatpickr-date').flatpickr({ dateFormat: 'Y-m-d' });

        let wajibLampiran = false;

        function clearErr($el) {
            $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $el.closest('.relative').find('.field-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $el.closest('.relative').parent().find('.field-error-msg').remove();
        }

        function setErr($el, msg) {
            clearErr($el);
            $el.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $el.closest('.relative').find('.field-icon').removeClass('text-slate-400').addClass('text-rose-500');
            $el.closest('.relative').after(`<p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1"><svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span>${msg}</span></p>`);
        }

        $form.find('input, select, textarea').on('input change', function() {
            clearErr($(this));
        });

        $form.find('#id_kategori_ticket_create').change(function() {
            let categoryId = $(this).val();
            if (categoryId !== '') {
                $.ajax({
                    url: "/ticket/category-detail/" + categoryId,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        if (response.status == "success") {
                            let cat = response.data;
                            $form.find("#categoryNoticeCard").removeClass("hidden");
                            $form.find("#categoryKeteranganText").text(cat.keterangan || "Tidak ada keterangan tambahan.");

                            if (cat.template_file) {
                                $form.find("#btnDownloadTemplate").attr("href", "/ticket/download-template/" + cat.id).removeClass("hidden");
                            } else {
                                $form.find("#btnDownloadTemplate").addClass("hidden");
                            }

                            if (cat.wajib_lampiran) {
                                wajibLampiran = true;
                                $form.find("#lampiranNotice").removeClass("hidden");
                                $form.find("#labelLampiran").html('File Lampiran Dokumen <span class="text-rose-500">* (Wajib Upload)</span>');
                            } else {
                                wajibLampiran = false;
                                $form.find("#lampiranNotice").addClass("hidden");
                                $form.find("#labelLampiran").html('File Lampiran Dokumen (Opsional)');
                            }
                        }
                    }
                });
            } else {
                wajibLampiran = false;
                $form.find("#categoryNoticeCard").addClass("hidden");
                $form.find("#labelLampiran").html('File Lampiran Dokumen');
            }
        });

        $form.on('submit', function(e) {
            const $kategori = $form.find('#id_kategori_ticket_create');
            const $judul = $form.find('#judul_ticket_create');
            const $ket = $form.find('#keterangan_ticket_create');
            const $lampiran = $form.find('#lampiran_ticket_create');
            let firstErr = null;

            if (!$kategori.val()) {
                setErr($kategori, 'Jenis tiket ajuan wajib dipilih!');
                firstErr = firstErr || $kategori;
            }
            if (!$judul.val().trim()) {
                setErr($judul, 'Judul tiket wajib diisi!');
                firstErr = firstErr || $judul;
            }
            if (!$ket.val().trim()) {
                setErr($ket, 'Keterangan pengajuan wajib diisi!');
                firstErr = firstErr || $ket;
            }
            if (wajibLampiran && !$lampiran.val()) {
                setErr($lampiran, 'Kategori ini wajib melampirkan dokumen!');
                firstErr = firstErr || $lampiran;
            }

            if (firstErr) {
                e.preventDefault();
                firstErr.focus();
                return false;
            }

            const $btn = $form.find('#btnSimpanTicket');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none')
                .html('<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg><span>Mengirim Tiket...</span>');
        });
    });
</script>

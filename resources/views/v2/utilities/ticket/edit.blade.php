<form action="{{ route('ticket.update', Crypt::encrypt($ticket->kode_pengajuan)) }}" method="POST" id="formTicketEdit" enctype="multipart/form-data" class="space-y-3">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="tanggal_ticket_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal Pengajuan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal_ticket_edit" value="{{ $ticket->tanggal }}" readonly class="flatpickr-date w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="Pilih Tanggal">
            </div>
        </div>

        <div>
            <label for="priority_ticket_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tingkat Prioritas
            </label>
            <div class="relative">
                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="flag" class="w-3.5 h-3.5"></i>
                </div>
                <select name="priority" id="priority_ticket_edit" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
                    <option value="Sedang" {{ $ticket->priority == 'Sedang' ? 'selected' : '' }}>Sedang (Normal)</option>
                    <option value="Rendah" {{ $ticket->priority == 'Rendah' ? 'selected' : '' }}>Rendah (Low)</option>
                    <option value="Tinggi" {{ $ticket->priority == 'Tinggi' ? 'selected' : '' }}>Tinggi (High)</option>
                    <option value="Urgent" {{ $ticket->priority == 'Urgent' ? 'selected' : '' }}>Urgent (Immediate)</option>
                </select>
            </div>
        </div>
    </div>

    <div>
        <label for="id_kategori_ticket_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Jenis Tiket Ajuan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="tags" class="w-3.5 h-3.5"></i>
            </div>
            <select name="id_kategori" id="id_kategori_ticket_edit" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
                <option value="">-- Pilih Jenis Tiket Ajuan --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $ticket->id_kategori == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="judul_ticket_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Judul / Ringkasan Tiket <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="heading" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="judul" id="judul_ticket_edit" value="{{ $ticket->judul }}" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
            </div>
        </div>

        <div>
            <label for="no_bukti_ticket_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. Faktur / No. Bukti (Opsional)
            </label>
            <div class="relative">
                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="no_bukti" id="no_bukti_ticket_edit" value="{{ $ticket->no_bukti }}" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
            </div>
        </div>
    </div>

    <div>
        <label for="keterangan_ticket_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Detail Keterangan & Alasan Pengajuan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="field-icon absolute top-2.5 left-0 pl-2.5 flex items-start pointer-events-none text-slate-400 z-10">
                <i data-lucide="align-left" class="w-3.5 h-3.5"></i>
            </div>
            <textarea name="keterangan" id="keterangan_ticket_edit" rows="3" class="w-full pl-8 pr-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">{{ $ticket->keterangan }}</textarea>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="lampiran_ticket_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                File Lampiran Baru (Opsional)
            </label>
            <input type="file" name="lampiran" id="lampiran_ticket_edit" accept=".xlsx,.xls,.doc,.docx,.pdf,.jpg,.jpeg,.png,.zip,.rar" class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#284c9a] file:text-white hover:file:bg-[#1e3a78] bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg cursor-pointer">
            @if ($ticket->lampiran)
                <p class="text-[11px] text-emerald-600 dark:text-emerald-400 mt-1 flex items-center gap-1">
                    <i data-lucide="paperclip" class="w-3 h-3"></i>
                    <span>Lampiran saat ini: <a href="{{ asset($ticket->lampiran) }}" target="_blank" class="underline font-bold">Unduh Lampiran</a></span>
                </p>
            @endif
        </div>

        <div>
            <label for="link_ticket_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Link URL / Referensi (Opsional)
            </label>
            <div class="relative">
                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="link" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="link" id="link_ticket_edit" value="{{ $ticket->link }}" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
            </div>
        </div>
    </div>

    <!-- Approval Flow -->
    <div class="rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5">
        <div class="flex items-center gap-1.5 mb-2.5">
            <i data-lucide="git-merge" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Kustomisasi Alur Persetujuan</h4>
        </div>
        @if ($ticket->kode_cabang != 'PST')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2.5">
                <!-- SMM -->
                <div class="p-2.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 space-y-1.5">
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox" name="perlu_smm" id="perlu_smm_edit" value="1" {{ $ticket->id_smm ? 'checked' : '' }} {{ !empty($ticket->smm_approved_at) ? 'disabled' : '' }} class="w-3.5 h-3.5 rounded text-[#284c9a]">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Persetujuan SMM</span>
                    </label>
                    <select name="id_smm" id="id_smm_edit" {{ ($ticket->id_smm && empty($ticket->smm_approved_at)) ? '' : 'disabled' }} class="w-full h-[34px] px-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 disabled:opacity-60">
                        <option value="">-- Pilih SMM --</option>
                        @foreach ($smmList as $u)
                            <option value="{{ $u->id }}" {{ $ticket->id_smm == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                    @if (!empty($ticket->smm_approved_at))
                        <p class="text-[10px] font-bold text-emerald-600 flex items-center gap-1"><i data-lucide="check-circle-2" class="w-3 h-3"></i> Sudah Disetujui</p>
                        <input type="hidden" name="perlu_smm" value="1">
                        <input type="hidden" name="id_smm" value="{{ $ticket->id_smm }}">
                    @endif
                </div>

                <!-- RSM -->
                <div class="p-2.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 space-y-1.5">
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox" name="perlu_rsm" id="perlu_rsm_edit" value="1" {{ $ticket->id_rsm ? 'checked' : '' }} {{ !empty($ticket->rsm_approved_at) ? 'disabled' : '' }} class="w-3.5 h-3.5 rounded text-[#284c9a]">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Persetujuan RSM</span>
                    </label>
                    <select name="id_rsm" id="id_rsm_edit" {{ ($ticket->id_rsm && empty($ticket->rsm_approved_at)) ? '' : 'disabled' }} class="w-full h-[34px] px-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 disabled:opacity-60">
                        <option value="">-- Pilih RSM --</option>
                        @foreach ($rsmList as $u)
                            <option value="{{ $u->id }}" {{ $ticket->id_rsm == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                    @if (!empty($ticket->rsm_approved_at))
                        <p class="text-[10px] font-bold text-emerald-600 flex items-center gap-1"><i data-lucide="check-circle-2" class="w-3 h-3"></i> Sudah Disetujui</p>
                        <input type="hidden" name="perlu_rsm" value="1">
                        <input type="hidden" name="id_rsm" value="{{ $ticket->id_rsm }}">
                    @endif
                </div>

                <!-- GM -->
                <div class="p-2.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 space-y-1.5">
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox" name="perlu_gm" id="perlu_gm_edit" value="1" {{ $ticket->id_gm ? 'checked' : '' }} {{ !empty($ticket->gm_approved_at) ? 'disabled' : '' }} class="w-3.5 h-3.5 rounded text-[#284c9a]">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Persetujuan GM</span>
                    </label>
                    <select name="id_gm" id="id_gm_edit" {{ ($ticket->id_gm && empty($ticket->gm_approved_at)) ? '' : 'disabled' }} class="w-full h-[34px] px-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 disabled:opacity-60">
                        <option value="">-- Pilih GM --</option>
                        @foreach ($gmList as $u)
                            <option value="{{ $u->id }}" {{ $ticket->id_gm == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->roles->pluck('name')->first() == 'gm marketing' ? 'GM Mkt' : ($u->roles->pluck('name')->first() == 'gm operasional' ? 'GM Ops' : 'GM Adm') }})</option>
                        @endforeach
                    </select>
                    @if (!empty($ticket->gm_approved_at))
                        <p class="text-[10px] font-bold text-emerald-600 flex items-center gap-1"><i data-lucide="check-circle-2" class="w-3 h-3"></i> Sudah Disetujui</p>
                        <input type="hidden" name="perlu_gm" value="1">
                        <input type="hidden" name="id_gm" value="{{ $ticket->id_gm }}">
                    @endif
                </div>
            </div>
        @else
            <div class="p-2.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 space-y-1.5">
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="checkbox" name="perlu_manager_dept" id="perlu_manager_dept_edit" value="1" {{ $ticket->id_manager_dept ? 'checked' : '' }} {{ !empty($ticket->manager_approved_at) ? 'disabled' : '' }} class="w-3.5 h-3.5 rounded text-[#284c9a]">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Persetujuan Manager Departemen</span>
                </label>
                <select name="id_manager_dept" id="id_manager_dept_edit" {{ ($ticket->id_manager_dept && empty($ticket->manager_approved_at)) ? '' : 'disabled' }} class="w-full h-[34px] px-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 disabled:opacity-60">
                    <option value="">-- Pilih Manager --</option>
                    @foreach ($managerList as $u)
                        <option value="{{ $u->id }}" {{ $ticket->id_manager_dept == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
                @if (!empty($ticket->manager_approved_at))
                    <p class="text-[10px] font-bold text-emerald-600 flex items-center gap-1"><i data-lucide="check-circle-2" class="w-3 h-3"></i> Sudah Disetujui</p>
                    <input type="hidden" name="perlu_manager_dept" value="1">
                    <input type="hidden" name="id_manager_dept" value="{{ $ticket->id_manager_dept }}">
                @endif
            </div>
        @endif
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" data-bs-dismiss="modal" class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition cursor-pointer">Batal</button>
        <button type="submit" id="btnUpdateTicket" class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold flex items-center gap-1.5 transition cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Update Tiket Ajuan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formTicketEdit');
        $form.find('.flatpickr-date').flatpickr({ dateFormat: 'Y-m-d' });

        $form.find('#perlu_smm_edit').change(function() { $form.find('#id_smm_edit').prop('disabled', !$(this).is(':checked')); });
        $form.find('#perlu_rsm_edit').change(function() { $form.find('#id_rsm_edit').prop('disabled', !$(this).is(':checked')); });
        $form.find('#perlu_gm_edit').change(function() { $form.find('#id_gm_edit').prop('disabled', !$(this).is(':checked')); });
        $form.find('#perlu_manager_dept_edit').change(function() { $form.find('#id_manager_dept_edit').prop('disabled', !$(this).is(':checked')); });

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

        $form.on('submit', function(e) {
            const $kategori = $form.find('#id_kategori_ticket_edit');
            const $judul = $form.find('#judul_ticket_edit');
            const $ket = $form.find('#keterangan_ticket_edit');
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

            if (firstErr) {
                e.preventDefault();
                firstErr.focus();
                return false;
            }

            const $btn = $form.find('#btnUpdateTicket');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none')
                .html('<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg><span>Memperbarui...</span>');
        });
    });
</script>

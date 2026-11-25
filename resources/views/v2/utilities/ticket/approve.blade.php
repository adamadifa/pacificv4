<form action="{{ route('ticket.storeapprove', Crypt::encrypt($ticket->kode_pengajuan)) }}" method="POST" id="formApproveTicket" class="space-y-3">
    @csrf

    <!-- Ticket Information Summary Strip -->
    <div class="rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 space-y-2.5">
        <div class="flex items-start justify-between gap-2 pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 font-mono-num font-bold text-xs border border-blue-200 dark:border-blue-900 mb-1">
                    {{ $ticket->kode_pengajuan }}
                </span>
                <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $ticket->judul ?? 'Pengajuan Tiket' }}</h4>
            </div>
            <div class="flex flex-col items-end gap-1 shrink-0">
                @php
                    $prioClass = match($ticket->priority) {
                        'Urgent' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-900',
                        'Tinggi' => 'bg-orange-50 dark:bg-orange-950/60 text-orange-600 dark:text-orange-400 border-orange-200 dark:border-orange-900',
                        'Sedang' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-900',
                        default  => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700',
                    };
                @endphp
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md border text-[10px] font-bold uppercase {{ $prioClass }}">
                    {{ $ticket->priority ?? 'Rendah' }}
                </span>

                @if ($ticket->status == '1')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900 text-[10px] font-bold">
                        <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                        <span>Selesai</span>
                    </span>
                @elseif ($ticket->status == '2')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900 text-[10px] font-bold">
                        <i data-lucide="x-circle" class="w-3 h-3"></i>
                        <span>Ditolak</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900 text-[10px] font-bold">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        <span>Menunggu</span>
                    </span>
                @endif

                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 border border-blue-200/80 dark:border-blue-900 text-[10px] font-bold">
                    {{ $ticket->status == '1' ? 'Selesai (IT Admin)' : ($ticket->status == '2' ? 'Ditolak' : ($ticket->posisi_approval == 'ADMIN' ? 'Diproses IT Admin' : 'Menunggu ' . strtoupper($ticket->posisi_approval ?? '-'))) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 text-xs">
            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">Pembuat</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">{{ $ticket->user->name ?? '-' }}</span>
                <span class="text-[11px] text-slate-500">({{ $ticket->kode_cabang ?? '-' }} / {{ $ticket->kode_dept ?? '-' }})</span>
            </div>
            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">Tanggal</span>
                <span class="font-semibold text-slate-700 dark:text-slate-200 inline-flex items-center gap-1">
                    <i data-lucide="calendar" class="w-3 h-3 text-slate-400"></i>
                    {{ date('d-m-Y', strtotime($ticket->tanggal)) }}
                </span>
            </div>
            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">Kategori</span>
                <span class="font-semibold text-slate-700 dark:text-slate-200 inline-flex items-center gap-1">
                    <i data-lucide="tag" class="w-3 h-3 text-slate-400"></i>
                    {{ $ticket->category->nama_kategori ?? 'Umum' }}
                </span>
            </div>
        </div>

        <div>
            <span class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Keterangan / Detail Pengajuan</span>
            <div class="p-2.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">{{ $ticket->keterangan }}</div>
        </div>

        @if ($ticket->lampiran || $ticket->link)
            <div class="flex items-center gap-2 flex-wrap pt-1">
                @if ($ticket->lampiran)
                    <a href="{{ asset($ticket->lampiran) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white border border-rose-200 dark:border-rose-900 text-xs font-bold transition">
                        <i data-lucide="paperclip" class="w-3.5 h-3.5"></i>
                        <span>Unduh File Lampiran</span>
                    </a>
                @endif
                @if ($ticket->link)
                    <a href="{{ $ticket->link }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white border border-blue-200 dark:border-blue-900 text-xs font-bold transition">
                        <i data-lucide="link" class="w-3.5 h-3.5"></i>
                        <span>Buka Link Referensi</span>
                    </a>
                @endif
            </div>
        @endif
    </div>

    <!-- Approval Stepper Timeline -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5">
        <div class="flex items-center gap-1.5 mb-2.5">
            <i data-lucide="git-merge" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Alur Status Persetujuan</h4>
        </div>

        <div class="flex items-stretch gap-2 flex-wrap">
            @php
                $currentPosisi = $ticket->posisi_approval;
                $status = $ticket->status;
                $foundCurrent = false;
            @endphp

            @foreach ($configRoles as $role)
                @php
                    $stepState = 'pending';
                    $isCurrent = ($currentPosisi == $role && $status == '0');
                    if ($isCurrent) {
                        $stepState = 'active';
                        $foundCurrent = true;
                    } elseif ($status == '1' || $status == '2' || !$foundCurrent) {
                        $stepState = 'approved';
                    }
                    $boxClass = match($stepState) {
                        'approved' => 'border-emerald-300 dark:border-emerald-800 bg-emerald-50/70 dark:bg-emerald-950/30',
                        'active'   => 'border-amber-300 dark:border-amber-800 bg-amber-50/70 dark:bg-amber-950/30',
                        default    => 'border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/30',
                    };
                    $circleClass = match($stepState) {
                        'approved' => 'bg-emerald-500 text-white',
                        'active'   => 'bg-amber-500 text-white',
                        default    => 'bg-slate-300 dark:bg-slate-700 text-slate-600 dark:text-slate-300',
                    };
                @endphp
                <div class="flex-1 min-w-[115px] rounded-lg border p-2.5 flex flex-col items-center text-center gap-1 {{ $boxClass }}">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold {{ $circleClass }}">
                        @if ($stepState == 'approved')
                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                        @else
                            {{ $loop->iteration }}
                        @endif
                    </div>
                    <div class="text-[11px] font-bold text-slate-800 dark:text-slate-100">{{ strtoupper($role) }}</div>
                    @if ($isCurrent)
                        <span class="px-1.5 py-0.5 rounded bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 text-[10px] font-bold">Menunggu</span>
                    @elseif ($stepState == 'approved')
                        <span class="px-1.5 py-0.5 rounded bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-[10px] font-bold">Selesai</span>
                    @else
                        <span class="text-[10px] text-slate-400">-</span>
                    @endif
                </div>
            @endforeach

            <!-- IT Exec Final -->
            @php
                $adminState = 'pending';
                if ($status == '1') {
                    $adminState = 'approved';
                } elseif ($currentPosisi == 'ADMIN' && $status == '0') {
                    $adminState = 'it';
                }
                $adminBox = match($adminState) {
                    'approved' => 'border-emerald-300 dark:border-emerald-800 bg-emerald-50/70 dark:bg-emerald-950/30',
                    'it'       => 'border-blue-300 dark:border-blue-800 bg-blue-50/70 dark:bg-blue-950/30',
                    default    => 'border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/30',
                };
                $adminCircle = match($adminState) {
                    'approved' => 'bg-emerald-500 text-white',
                    'it'       => 'bg-[#284c9a] text-white',
                    default    => 'bg-slate-300 dark:bg-slate-700 text-slate-600 dark:text-slate-300',
                };
            @endphp
            <div class="flex-1 min-w-[115px] rounded-lg border p-2.5 flex flex-col items-center text-center gap-1 {{ $adminBox }}">
                <div class="w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold {{ $adminCircle }}">
                    <i data-lucide="{{ $status == '1' ? 'check' : 'settings' }}" class="w-3.5 h-3.5"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-800 dark:text-slate-100">IT Admin</div>
                @if ($status == '1')
                    <span class="px-1.5 py-0.5 rounded bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-[10px] font-bold">Selesai</span>
                @elseif ($currentPosisi == 'ADMIN' && $status == '0')
                    <span class="px-1.5 py-0.5 rounded bg-blue-100 dark:bg-blue-900/50 text-[#284c9a] dark:text-blue-300 text-[10px] font-bold">Proses IT</span>
                @else
                    <span class="text-[10px] text-slate-400">-</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Decline Notes -->
    <div id="declineNotesArea" class="hidden">
        <label for="catatan_decline" class="block text-xs font-bold text-rose-600 dark:text-rose-400 mb-1">
            Alasan Penolakan Tiket <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="field-icon absolute top-2.5 left-0 pl-2.5 flex items-start pointer-events-none text-rose-400 z-10">
                <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
            </div>
            <textarea name="catatan_decline" id="catatan_decline" rows="3" class="w-full pl-8 pr-3 py-2 bg-rose-50/30 dark:bg-rose-950/20 border border-rose-300 dark:border-rose-800 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500" placeholder="Masukkan alasan penolakan..."></textarea>
        </div>
    </div>

    @hasrole('super admin')
        <div class="rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 p-3 sm:p-3.5">
            <div class="flex items-center gap-1.5 mb-1">
                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400"></i>
                <h4 class="text-xs font-bold text-amber-800 dark:text-amber-300">Super Admin Panel Override</h4>
            </div>
            <p class="text-[11px] text-amber-700/80 dark:text-amber-400/80 mb-2">Sebagai super admin, Anda dapat mengalihkan posisi persetujuan tiket ini ke role lain.</p>
            <div class="relative">
                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-amber-600 z-10">
                    <i data-lucide="arrow-right-circle" class="w-3.5 h-3.5"></i>
                </div>
                <select name="manual_posisi_approval" class="w-full h-[36px] pl-8 pr-3 bg-white dark:bg-slate-900 border border-amber-300 dark:border-amber-700 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100">
                    <option value="">-- Tetap Ikuti Alur Approval Normal --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" {{ $ticket->posisi_approval == $role ? 'selected' : '' }}>Pindahkan ke: {{ strtoupper($role) }}</option>
                    @endforeach
                    <option value="ADMIN" {{ $ticket->posisi_approval == 'ADMIN' ? 'selected' : '' }}>Pindahkan ke: ADMIN IT (Proses IT)</option>
                    <option value="SELESAI" {{ $ticket->posisi_approval == 'SELESAI' ? 'selected' : '' }}>Pindahkan ke: SELESAI (Selesaikan Tiket)</option>
                </select>
            </div>
        </div>
    @endhasrole

    @if ($ticket->status == '0')
        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
            <button type="button" id="btnToggleDecline" class="h-[36px] px-4 rounded-lg border border-rose-300 dark:border-rose-800 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-600 hover:text-white text-rose-600 dark:text-rose-400 text-xs font-bold flex items-center gap-1.5 transition cursor-pointer">
                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                <span>Tolak (Decline)</span>
            </button>
            <button type="submit" name="decline" id="btnSubmitDecline" class="hidden h-[36px] px-4 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold flex items-center gap-1.5 transition cursor-pointer">
                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                <span>Konfirmasi Penolakan</span>
            </button>
            <button type="submit" name="approve" id="btnSubmitApprove" class="h-[36px] px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center gap-1.5 transition cursor-pointer">
                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                <span>Setujui Pengajuan Tiket</span>
            </button>
        </div>
    @else
        <div class="rounded-lg bg-slate-100 dark:bg-slate-800 p-2.5 text-center text-xs font-semibold text-slate-600 dark:text-slate-300 flex items-center justify-center gap-1.5">
            <i data-lucide="info" class="w-3.5 h-3.5"></i>
            <span>Tiket ini sudah memiliki status akhir ({{ $ticket->status == '1' ? 'Selesai' : 'Ditolak' }}).</span>
        </div>
    @endif
</form>

<script>
    $(function() {
        const $form = $('#formApproveTicket');
        const $declineArea = $form.find('#declineNotesArea');
        const $declineNote = $form.find('#catatan_decline');

        $form.find('#btnToggleDecline').click(function() {
            $declineArea.removeClass('hidden');
            $form.find('#btnSubmitApprove').addClass('hidden');
            $(this).addClass('hidden');
            $form.find('#btnSubmitDecline').removeClass('hidden');
            $declineNote.focus();
        });

        $declineNote.on('input change', function() {
            $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            $(this).closest('.relative').parent().find('.field-error-msg').remove();
        });

        $form.submit(function(e) {
            if (!$form.find('#btnSubmitDecline').hasClass('hidden') && $declineNote.val().trim() === '') {
                e.preventDefault();
                $declineNote.addClass('border-rose-500 ring-1 ring-rose-500');
                $declineNote.closest('.relative').parent().find('.field-error-msg').remove();
                $declineNote.closest('.relative').after('<p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1"><svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span>Alasan penolakan wajib diisi!</span></p>');
                $declineNote.focus();
                return false;
            }
        });
    });
</script>

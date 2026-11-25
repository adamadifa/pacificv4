<div class="rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden flex flex-col">
    <!-- Chat List Container -->
    <div id="listmessage" class="p-4 max-h-[420px] overflow-y-auto flex flex-col gap-3 scrollbar-thin">
        @if (count($ticketmessage) > 0)
            @foreach ($ticketmessage as $d)
                @if ($d->id_user == auth()->user()->id)
                    <div class="flex flex-col items-end self-end max-w-[78%]">
                        <div class="px-3.5 py-2 rounded-2xl rounded-br-xs bg-[#284c9a] text-white text-xs leading-relaxed shadow-2xs">
                            {{ $d->message }}
                        </div>
                        <div class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                            <span class="font-bold text-slate-600 dark:text-slate-300">Anda</span>
                            <span>•</span>
                            <span>{{ $d->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-start self-start max-w-[78%]">
                        <div class="px-3.5 py-2 rounded-2xl rounded-bl-xs bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 border border-slate-200 dark:border-slate-700 text-xs leading-relaxed shadow-2xs">
                            {{ $d->message }}
                        </div>
                        <div class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                            <span class="font-bold text-slate-600 dark:text-slate-300">{{ $d->name }}</span>
                            <span>•</span>
                            <span>{{ $d->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div id="emptyChatState" class="py-10 px-4 text-center text-slate-400">
                <i data-lucide="message-square" class="w-9 h-9 mx-auto mb-2 text-slate-300 dark:text-slate-700"></i>
                <h5 class="text-xs font-bold text-slate-600 dark:text-slate-300">Belum Ada Diskusi</h5>
                <p class="text-[11px] mt-0.5">Mulai kirimkan pesan pertama untuk mendiskusikan tiket ini.</p>
            </div>
        @endif
    </div>

    <!-- Chat Input Area -->
    <div class="p-3 bg-white dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3 py-1.5 focus-within:border-[#284c9a] focus-within:ring-2 focus-within:ring-[#284c9a]/15">
            <textarea name="message" id="chatMessageInput" rows="1" class="w-full bg-transparent border-0 focus:outline-none text-xs text-slate-800 dark:text-slate-100 resize-none py-1.5" placeholder="Tulis pesan diskusi di sini..."></textarea>
            <button type="button" id="btnSimpanMessage" title="Kirim Pesan" class="w-8 h-8 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white flex items-center justify-center shrink-0 transition cursor-pointer">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
            </button>
        </div>
        <p id="chatErrorMsg" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1"></p>
    </div>
</div>

<script>
    $(function() {
        const chatContainer = $('#listmessage');
        chatContainer.scrollTop(chatContainer[0].scrollHeight);

        $('#btnSimpanMessage').click(function() {
            const $input = $('#chatMessageInput');
            const $err = $('#chatErrorMsg');
            const message = $input.val().trim();
            if (message === '') return;

            $err.addClass('hidden').text('');

            $.ajax({
                url: '{{ route('ticket.storemessage', Crypt::encrypt($kode_pengajuan)) }}',
                type: 'POST',
                data: {
                    message: message,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status == 'success') {
                        $('#emptyChatState').remove();
                        const nowStr = new Date().toLocaleDateString('id-ID', {day: '2-digit', month: '2-digit', year: 'numeric'}) + ' ' + new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
                        const safeMsg = $('<div>').text(message).html().replace(/\n/g, '<br>');
                        const messageHtml = `
                            <div class="flex flex-col items-end self-end max-w-[78%]">
                                <div class="px-3.5 py-2 rounded-2xl rounded-br-xs bg-[#284c9a] text-white text-xs leading-relaxed shadow-2xs">
                                    ${safeMsg}
                                </div>
                                <div class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                    <span class="font-bold text-slate-600 dark:text-slate-300">Anda</span>
                                    <span>•</span>
                                    <span>${nowStr}</span>
                                </div>
                            </div>
                        `;
                        chatContainer.append(messageHtml);
                        chatContainer.scrollTop(chatContainer[0].scrollHeight);
                        $input.val('');
                    }
                },
                error: function(xhr) {
                    const errText = xhr.responseJSON?.errors?.message?.[0] || xhr.responseJSON?.message || 'Gagal mengirim pesan';
                    $err.removeClass('hidden').text(errText);
                }
            });
        });

        $('#chatMessageInput').keydown(function(e) {
            if (e.keyCode === 13 && !e.shiftKey) {
                e.preventDefault();
                $('#btnSimpanMessage').click();
            }
        });
    });
</script>

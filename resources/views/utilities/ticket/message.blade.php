<style>
    .chat-wrapper {
        display: flex;
        flex-direction: column;
        background: #f8fafc;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: inset 0 2px 4px 0 rgba(0,0,0,0.02);
    }

    .chat-container {
        padding: 20px;
        max-height: 420px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    .chat-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .chat-container::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    
    .chat-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .chat-container::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    .chat-message-group {
        display: flex;
        flex-direction: column;
        max-width: 75%;
        animation: chatBubbleIn 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    @keyframes chatBubbleIn {
        from {
            opacity: 0;
            transform: translateY(12px) scale(0.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .chat-message-group.my-message {
        align-self: flex-end;
        align-items: flex-end;
    }
    
    .chat-message-group.other-message {
        align-self: flex-start;
        align-items: flex-start;
    }
    
    .chat-bubble {
        padding: 12px 18px;
        font-size: 0.925rem;
        line-height: 1.5;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    }
    
    .my-message .chat-bubble {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: #ffffff;
        border-radius: 18px 18px 0px 18px;
    }
    
    .other-message .chat-bubble {
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        border-radius: 18px 18px 18px 0px;
    }
    
    .message-meta {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .my-message .message-meta {
        justify-content: flex-end;
    }

    .message-sender {
        font-weight: 600;
        color: #475569;
    }
    
    .chat-input-area {
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        padding: 14px 20px;
    }

    .chat-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        border: 1.5px solid #cbd5e1;
        border-radius: 28px;
        padding: 4px 6px 4px 18px;
        background: #ffffff;
        transition: all 0.2s ease;
    }

    .chat-input-wrapper:focus-within {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }
    
    .chat-input-wrapper textarea {
        border: none;
        outline: none;
        width: 100%;
        resize: none;
        height: 38px;
        padding: 8px 0;
        font-size: 0.925rem;
        color: #1e293b;
        background: transparent;
    }
    
    .chat-input-wrapper textarea::placeholder {
        color: #94a3b8;
    }
    
    .btn-send {
        background: #4f46e5;
        color: #ffffff;
        border: none;
        border-radius: 50%;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        cursor: pointer;
        flex-shrink: 0;
    }

    .btn-send:hover {
        background: #4338ca;
        transform: scale(1.05);
    }

    .btn-send:active {
        transform: scale(0.95);
    }

    .chat-empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
    }

    .chat-empty-state i {
        font-size: 2.5rem;
        color: #cbd5e1;
        margin-bottom: 12px;
    }
</style>

<div class="chat-wrapper mb-3">
    <!-- Chat List Container -->
    <div class="chat-container" id="listmessage">
        @if(count($ticketmessage) > 0)
            @foreach ($ticketmessage as $d)
                @if($d->id_user == auth()->user()->id)
                    <div class="chat-message-group my-message">
                        <div class="chat-bubble">
                            {{ $d->message }}
                        </div>
                        <div class="message-meta">
                            <span class="message-sender">Anda</span>
                            <span>•</span>
                            <span>{{ $d->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                @else
                    <div class="chat-message-group other-message">
                        <div class="chat-bubble">
                            {{ $d->message }}
                        </div>
                        <div class="message-meta">
                            <span class="message-sender">{{ $d->name }}</span>
                            <span>•</span>
                            <span>{{ $d->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="chat-empty-state" id="emptyChatState">
                <i class="ti ti-message-dots"></i>
                <h6 class="fw-bold mb-1">Belum Ada Diskusi</h6>
                <p class="small mb-0">Mulai kirimkan pesan pertama untuk mendiskusikan tiket ini.</p>
            </div>
        @endif
    </div>

    <!-- Chat Input Area -->
    <div class="chat-input-area">
        <div class="chat-input-wrapper">
            <textarea name="message" id="chatMessageInput" placeholder="Tulis pesan diskusi di sini..." rows="1"></textarea>
            <button type="button" class="btn-send" id="btnSimpan" title="Kirim Pesan">
                <i class="ti ti-send fs-5"></i>
            </button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Auto scroll to bottom
        const chatContainer = $('#listmessage');
        chatContainer.scrollTop(chatContainer[0].scrollHeight);

        // Submit message handler
        $('#btnSimpan').click(function () {
            let messageInput = $('#chatMessageInput');
            let message = messageInput.val().trim();
            if (message === '') return;

            $.ajax({
                url: '{{ route('ticket.storemessage', Crypt::encrypt($kode_pengajuan)) }}',
                type: 'POST',
                data: {
                    message: message,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status == 'success') {
                        // Remove empty state if active
                        $('#emptyChatState').remove();

                        const messageHtml = `
                            <div class="chat-message-group my-message">
                                <div class="chat-bubble">
                                    ${message.replace(/\n/g, '<br>')}
                                </div>
                                <div class="message-meta">
                                    <span class="message-sender">Anda</span>
                                    <span>•</span>
                                    <span>${new Date().toLocaleDateString('id-ID', {day: 'numeric', month: 'numeric', year: 'numeric'})} ${new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}</span>
                                </div>
                            </div>
                        `;
                        chatContainer.append(messageHtml);
                        chatContainer.scrollTop(chatContainer[0].scrollHeight);
                        messageInput.val('');
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: xhr.responseJSON.message || 'Gagal mengirim pesan',
                        icon: 'error',
                        text: xhr.responseJSON.errors?.message ? xhr.responseJSON.errors.message[0] : ''
                    });
                }
            });
        });

        // Submit message on Enter (and newline with Shift+Enter)
        $('#chatMessageInput').keydown(function (e) {
            if (e.keyCode === 13 && !e.shiftKey) {
                e.preventDefault();
                $('#btnSimpan').click();
            }
        });
    });
</script>
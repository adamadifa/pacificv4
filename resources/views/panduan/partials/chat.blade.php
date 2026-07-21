<!-- Chat Widget Floating Button -->
<button type="button" id="chat-widget-toggle" class="btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center" style="position: fixed; bottom: 85px; right: 25px; width: 60px; height: 60px; z-index: 1050; border: 2px solid #fff; box-shadow: 0 8px 24px rgba(0, 46, 101, 0.25) !important;">
    <i class="ti ti-messages fs-2"></i>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="chat-notification-badge" style="display: none;">1</span>
</button>

<!-- Chat Window Container (Sidebar Style: Full Height Right Side) -->
<div id="chat-widget-window" class="card shadow-lg border-0 d-none" style="position: fixed; top: 0; right: 0; bottom: 0; width: 25%; min-width: 380px; max-width: 500px; height: 100vh; z-index: 9999 !important; border-radius: 16px 0 0 16px; overflow: hidden; transition: all 0.3s ease; box-shadow: -10px 0 30px rgba(0,0,0,0.15) !important;">
    
    <!-- Chat Header -->
    <div class="card-header d-flex justify-content-between align-items-center py-3 text-white" style="background: linear-gradient(135deg, #002e65 0%, #00408a 100%); border-radius: 0; height: 70px;">
        <div class="d-flex align-items-center gap-2">
            <div class="bg-success rounded-circle" style="width: 12px; height: 12px;"></div>
            <div>
                <h6 class="m-0 fw-bold text-white" style="font-size: 16px !important;">Q&A Assistant</h6>
                <small class="text-white-50" style="font-size: 12px !important;">Online</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button type="button" class="btn p-0 text-white border-0" id="chat-widget-clear" title="Hapus Histori Chat" style="background: transparent; outline: none;">
                <i class="ti ti-trash fs-4"></i>
            </button>
            <button type="button" class="btn-close btn-close-white" id="chat-widget-close" aria-label="Close"></button>
        </div>
    </div>

    <!-- Chat Body -->
    <div class="card-body p-3 d-flex flex-column gap-3" id="chat-widget-body" style="background-color: #f8fafc; height: calc(100vh - 145px); overflow-y: auto !important;">
    </div>

    <!-- Chat Footer -->
    <div class="card-footer p-3 bg-white border-top" style="height: 75px;">
        <form id="chat-widget-form" class="d-flex gap-2">
            <input type="text" id="chat-widget-input" class="form-control border shadow-none" placeholder="Tulis pertanyaan Anda..." autocomplete="off" style="border-radius: 24px; padding-left: 18px; height: 44px; font-size: 15px;">
            <button type="submit" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 44px; height: 44px; flex-shrink: 0;">
                <i class="ti ti-send fs-5"></i>
            </button>
        </form>
    </div>
</div>

<style>
    #chat-widget-body {
        overflow-y: auto !important;
        height: calc(100vh - 145px) !important;
        font-size: 15px !important;
    }
    
    /* Styling for custom scrollbar */
    #chat-widget-body::-webkit-scrollbar {
        width: 6px;
    }
    #chat-widget-body::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    #chat-widget-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    #chat-widget-body::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    .chat-bubble-user {
        border-radius: 12px 0 12px 12px !important;
        background: #002e65;
        color: #fff;
        font-size: 15px !important;
        padding: 10px 14px !important;
        box-shadow: 0 2px 6px rgba(0,46,101,0.1);
    }
    
    .chat-bubble-bot {
        border-radius: 0 12px 12px 12px !important;
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        font-size: 15px !important;
        padding: 10px 14px !important;
        line-height: 1.6 !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }

    /* Loading dots animation */
    .typing-indicator {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 4px 8px;
    }
    .typing-indicator span {
        width: 6px;
        height: 6px;
        background: #94a3b8;
        border-radius: 50%;
        animation: typing 1s infinite alternate;
    }
    .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
    .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing {
        from { transform: translateY(0); opacity: 0.4; }
        to { transform: translateY(-5px); opacity: 1; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('chat-widget-toggle');
    const chatWindow = document.getElementById('chat-widget-window');
    const closeBtn = document.getElementById('chat-widget-close');
    const clearBtn = document.getElementById('chat-widget-clear');
    const chatForm = document.getElementById('chat-widget-form');
    const chatInput = document.getElementById('chat-widget-input');
    const chatBody = document.getElementById('chat-widget-body');
    const badge = document.getElementById('chat-notification-badge');

    const STORAGE_KEY = 'portal_chat_history';

    // Load existing history or welcome message
    loadHistory();

    // Toggle chat window open/close
    toggleBtn.addEventListener('click', function() {
        chatWindow.classList.toggle('d-none');
        badge.style.display = 'none';
        if (!chatWindow.classList.contains('d-none')) {
            chatInput.focus();
            scrollToBottom();
        }
    });

    closeBtn.addEventListener('click', function() {
        chatWindow.classList.add('d-none');
    });

    // Clear chat history with SweetAlert
    clearBtn.addEventListener('click', function() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Histori?',
                text: "Semua riwayat chat Anda dengan asisten akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#002e65',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    clearChatHistory();
                }
            });
        } else {
            if (confirm('Hapus seluruh histori chat?')) {
                clearChatHistory();
            }
        }
    });

    function clearChatHistory() {
        localStorage.removeItem(STORAGE_KEY);
        renderWelcomeMessage();
        scrollToBottom();
    }

    // Helper to scroll to the bottom of the chat body with a slight delay for layout rendering
    function scrollToBottom() {
        setTimeout(function() {
            chatBody.scrollTop = chatBody.scrollHeight;
        }, 50);
    }

    // Submit user message
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (!message) return;

        // Render user message bubble & save
        appendUserMessage(message, true);
        chatInput.value = '';

        // Show typing indicator
        const typingId = appendTypingIndicator();
        scrollToBottom();

        // AJAX POST to controller
        fetch('{{ route("panduan.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            // Remove typing indicator
            document.getElementById(typingId).remove();
            
            if (data.status === 'success') {
                appendBotMessage(data.reply, true);
            } else {
                appendBotMessage("Maaf, terjadi kesalahan koneksi. Silakan coba lagi.", true);
            }
            scrollToBottom();
        })
        .catch(err => {
            document.getElementById(typingId).remove();
            appendBotMessage("Maaf, terjadi gangguan server. Silakan hubungi IT.", true);
            scrollToBottom();
        });
    });

    function appendUserMessage(text, save = false) {
        const bubble = `
            <div class="d-flex align-items-start justify-content-end max-w-75 ms-auto">
                <div class="chat-bubble-user p-2_5 text-white small shadow-xs" style="line-height: 1.5; max-width: 85%;">
                    ${text}
                </div>
            </div>
        `;
        chatBody.insertAdjacentHTML('beforeend', bubble);
        if (save) {
            saveToHistory('user', text);
        }
    }

    function appendBotMessage(htmlContent, save = false) {
        const bubble = `
            <div class="d-flex align-items-start gap-2 max-w-75">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center p-1" style="width: 28px; height: 28px; font-size: 12px; flex-shrink: 0;">🤖</div>
                <div class="chat-bubble-bot p-2_5 text-dark small" style="line-height: 1.5; max-width: 85%;">
                    ${htmlContent}
                </div>
            </div>
        `;
        chatBody.insertAdjacentHTML('beforeend', bubble);
        if (save) {
            saveToHistory('bot', htmlContent);
        }
    }

    function appendTypingIndicator() {
        const id = 'typing-' + Date.now();
        const indicator = `
            <div class="d-flex align-items-start gap-2 max-w-75" id="${id}">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center p-1" style="width: 28px; height: 28px; font-size: 12px; flex-shrink: 0;">🤖</div>
                <div class="chat-bubble-bot p-2_5 text-dark small" style="max-width: 85%;">
                    <div class="typing-indicator">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        `;
        chatBody.insertAdjacentHTML('beforeend', indicator);
        return id;
    }

    function loadHistory() {
        let history = localStorage.getItem(STORAGE_KEY);
        if (history) {
            try {
                history = JSON.parse(history);
                chatBody.innerHTML = '';
                history.forEach(item => {
                    if (item.sender === 'user') {
                        appendUserMessage(item.content, false);
                    } else {
                        appendBotMessage(item.content, false);
                    }
                });
            } catch (e) {
                localStorage.removeItem(STORAGE_KEY);
                renderWelcomeMessage();
            }
        } else {
            renderWelcomeMessage();
        }
    }

    function renderWelcomeMessage() {
        chatBody.innerHTML = '';
        const welcomeHtml = `
            Halo! Saya adalah <b>Asisten Buku Panduan</b>. Ada yang bisa saya bantu terkait fitur-fitur di sistem ini? (Misal: <i>bagaimana cara buat BPB?</i>)
        `;
        appendBotMessage(welcomeHtml, false);
    }

    function saveToHistory(sender, content) {
        let history = localStorage.getItem(STORAGE_KEY);
        if (history) {
            try {
                history = JSON.parse(history);
            } catch(e) {
                history = [];
            }
        } else {
            history = [];
        }
        history.push({ sender: sender, content: content });
        localStorage.setItem(STORAGE_KEY, JSON.stringify(history));
    }
});
</script>

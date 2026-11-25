<!-- Chat Widget Floating Button -->
<button type="button" id="chat-widget-toggle" class="fixed bottom-6 right-6 w-13 h-13 rounded-full bg-[#284c9a] hover:bg-[#1e3a78] text-white border-2 border-white shadow-lg flex items-center justify-center transition transform hover:scale-105 cursor-pointer" style="z-index: 1050;">
    <i data-lucide="messages-square" class="w-6 h-6"></i>
    <span id="chat-notification-badge" class="hidden absolute -top-1 -right-1 w-5 h-5 rounded-full bg-rose-600 text-white text-[10px] font-bold flex items-center justify-center">1</span>
</button>

<!-- Chat Window Container -->
<div id="chat-widget-window" class="hidden fixed top-0 right-0 bottom-0 w-full sm:w-[390px] h-screen bg-white dark:bg-slate-900 border-l border-slate-200 dark:border-slate-800 shadow-2xl flex flex-col" style="z-index: 9999;">
    <!-- Chat Header -->
    <div class="px-4 py-3.5 bg-[#284c9a] text-white flex items-center justify-between shrink-0">
        <div class="flex items-center gap-2.5">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <div>
                <h6 class="text-xs font-bold text-white leading-tight">Q&amp;A Assistant</h6>
                <p class="text-[10px] text-white/70 leading-none mt-0.5">Online</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" id="chat-widget-clear" title="Hapus Histori Chat" class="w-7 h-7 rounded-lg hover:bg-white/15 text-white flex items-center justify-center transition cursor-pointer">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
            <button type="button" id="chat-widget-close" aria-label="Close" class="w-7 h-7 rounded-lg hover:bg-white/15 text-white flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Chat Body -->
    <div id="chat-widget-body" class="flex-1 p-3.5 overflow-y-auto bg-slate-50 dark:bg-slate-950 flex flex-col gap-3 scrollbar-thin"></div>

    <!-- Chat Footer -->
    <div class="p-3 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 shrink-0">
        <form id="chat-widget-form" class="flex items-center gap-2">
            <input type="text" id="chat-widget-input" class="flex-1 h-[38px] px-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="Tulis pertanyaan Anda..." autocomplete="off">
            <button type="submit" class="w-9 h-9 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white flex items-center justify-center shrink-0 transition cursor-pointer">
                <i data-lucide="send" class="w-4 h-4"></i>
            </button>
        </form>
    </div>
</div>

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

    if (!toggleBtn || !chatWindow) return;

    const STORAGE_KEY = 'portal_chat_history';
    loadHistory();

    toggleBtn.addEventListener('click', function() {
        chatWindow.classList.toggle('hidden');
        if (badge) badge.classList.add('hidden');
        if (!chatWindow.classList.contains('hidden')) {
            chatInput.focus();
            scrollToBottom();
        }
    });

    closeBtn.addEventListener('click', function() {
        chatWindow.classList.add('hidden');
    });

    clearBtn.addEventListener('click', function() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Histori?',
                text: "Semua riwayat chat Anda dengan asisten akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#284c9a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    clearChatHistory();
                }
            });
        } else if (confirm('Hapus seluruh histori chat?')) {
            clearChatHistory();
        }
    });

    function clearChatHistory() {
        localStorage.removeItem(STORAGE_KEY);
        renderWelcomeMessage();
        scrollToBottom();
    }

    function scrollToBottom() {
        setTimeout(function() {
            chatBody.scrollTop = chatBody.scrollHeight;
        }, 50);
    }

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (!message) return;

        appendUserMessage(message, true);
        chatInput.value = '';

        const typingId = appendTypingIndicator();
        scrollToBottom();

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
            document.getElementById(typingId)?.remove();
            if (data.status === 'success') {
                appendBotMessage(data.reply, true);
            } else {
                appendBotMessage("Maaf, terjadi kesalahan koneksi. Silakan coba lagi.", true);
            }
            scrollToBottom();
        })
        .catch(() => {
            document.getElementById(typingId)?.remove();
            appendBotMessage("Maaf, terjadi gangguan server. Silakan hubungi IT.", true);
            scrollToBottom();
        });
    });

    function appendUserMessage(text, save = false) {
        const bubble = `
            <div class="flex justify-end">
                <div class="max-w-[85%] px-3.5 py-2 rounded-2xl rounded-br-xs bg-[#284c9a] text-white text-xs leading-relaxed shadow-2xs">
                    ${text}
                </div>
            </div>
        `;
        chatBody.insertAdjacentHTML('beforeend', bubble);
        if (save) saveToHistory('user', text);
    }

    function appendBotMessage(htmlContent, save = false) {
        const bubble = `
            <div class="flex items-start gap-2">
                <div class="w-7 h-7 rounded-full bg-[#284c9a] text-white flex items-center justify-center text-xs shrink-0">🤖</div>
                <div class="max-w-[85%] px-3.5 py-2 rounded-2xl rounded-bl-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-xs leading-relaxed shadow-2xs">
                    ${htmlContent}
                </div>
            </div>
        `;
        chatBody.insertAdjacentHTML('beforeend', bubble);
        if (save) saveToHistory('bot', htmlContent);
    }

    function appendTypingIndicator() {
        const id = 'typing-' + Date.now();
        const indicator = `
            <div class="flex items-start gap-2" id="${id}">
                <div class="w-7 h-7 rounded-full bg-[#284c9a] text-white flex items-center justify-center text-xs shrink-0">🤖</div>
                <div class="px-3.5 py-2 rounded-2xl rounded-bl-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-400 text-xs">
                    Mengetik...
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
                    if (item.sender === 'user') appendUserMessage(item.content, false);
                    else appendBotMessage(item.content, false);
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
        appendBotMessage('Halo! Saya adalah <b>Asisten Buku Panduan</b>. Ada yang bisa saya bantu terkait fitur-fitur di sistem ini? (Misal: <i>bagaimana cara buat BPB?</i>)', false);
    }

    function saveToHistory(sender, content) {
        let history = localStorage.getItem(STORAGE_KEY);
        try { history = history ? JSON.parse(history) : []; } catch(e) { history = []; }
        history.push({ sender: sender, content: content });
        localStorage.setItem(STORAGE_KEY, JSON.stringify(history));
    }
});
</script>

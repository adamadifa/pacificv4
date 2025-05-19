<style>
    .chat-container {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        max-height: 400px;
        overflow-y: auto;
    }
    
    .chat-container::-webkit-scrollbar {
        width: 8px;
    }
    
    .chat-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .chat-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    .chat-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    .chat-message {
        border-radius: 15px;
        padding: 15px;
        margin: 5px 0;
        max-width: 80%;
        word-wrap: break-word;
        display: block;
    }
    
    .chat-message.my-message {
        background-color: #007bff;
        color: white;
        margin-left: auto;
        border-radius: 15px 15px 0 15px;
    }
    
    .chat-message.other-message {
        background-color: #e9ecef;
        color: #212529;
        border-radius: 15px 15px 15px 0;
    }
    
    .chat-message .message-time {
        font-size: 0.8em;
        opacity: 0.8;
    }
    
    .chat-message .message-author {
        font-size: 0.9em;
        font-weight: 500;
        margin-bottom: 5px;
    }
    
    .input-group {
        border: 1px solid #dee2e6;
        border-radius: 25px;
        padding: 10px;
        margin-bottom: 15px;
    }
    
    .input-group textarea {
        border: none;
        border-radius: 0;
        padding: 10px;
        resize: none;
        height: 100px;
    }
    
    .input-group textarea:focus {
        box-shadow: none;
        border: none;
    }
    
    .btn-send {
        border-radius: 25px;
        padding: 10px 20px;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="input-group">
            <textarea name="message" class="form-control" id="message" placeholder="Tulis pesan..."></textarea>
            <button type="button" class="btn btn-primary btn-send" id="btnSimpan">
                <i class="ti ti-send"></i>
                Kirim
            </button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="chat-container" id="listmessage">
            @if(count($ticketmessage) > 0)
                @foreach ($ticketmessage as $d)
                    @if($d->id_user == auth()->user()->id)
                        <div class="chat-message my-message">
                            <p class="message-author">Anda</p>
                            <p class="mb-1">{{ $d->message }}</p>
                            <small class="message-time">{{ $d->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    @else
                        <div class="chat-message other-message">
                            <p class="message-author">{{ $d->name }}</p>
                            <p class="mb-1">{{ $d->message }}</p>
                            <small class="message-time">{{ $d->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="alert alert-info">
                    <i class="ti ti-info-circle"></i>
                    Belum ada pesan
                </div>
            @endif
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#btnSimpan').click(function () {
            let message = $('textarea[name=message]').val();
            $.ajax({
                url: '{{ route('ticket.storemessage', Crypt::encrypt($kode_pengajuan)) }}',
                type: 'POST',
                data: {
                    message: message,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status == 'success') {
                        const messageHtml = `
                            <div class="chat-message my-message">
                                <p class="message-author">Anda</p>
                                <p class="mb-1">${message}</p>
                                <small class="message-time">${new Date().toLocaleString()}</small>
                            </div>
                        `;
                        $('#listmessage').append(messageHtml);
                        // Scroll ke pesan terbaru
                        $('#listmessage').scrollTop($('#listmessage')[0].scrollHeight);
                        // Kosongkan textarea
                        $('textarea[name=message]').val('');
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: xhr.responseJSON.message,
                        icon: 'error',
                        text: xhr.responseJSON.errors.message[0]
                    });
                }
            });
        });
    });
</script>
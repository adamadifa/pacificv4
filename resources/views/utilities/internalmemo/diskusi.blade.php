<div class="chat-box mb-3" style="max-height:400px; overflow-y:auto">
    @foreach ($chats as $chat)
        <div class="mb-2 {{ $chat->user_id == auth()->id() ? 'text-end' : '' }}">
            <div class="small fw-bold">{{ $chat->user_name }}</div>
            <div class="d-inline-block p-2 rounded border">
                {{ $chat->message }}
            </div>
            <div class="text-muted small">
                {{ \Carbon\Carbon::parse($chat->created_at)->diffForHumans() }}
            </div>
        </div>
    @endforeach
</div>

<form id="formDiskusi">
    @csrf
    <textarea name="message" class="form-control" rows="2" placeholder="Tuliskan alasan / pertanyaan..."></textarea>

    <button class="btn btn-primary mt-2 w-100">
        Kirim
    </button>
</form>

<script>
    // auto scroll ke bawah
    const box = document.querySelector('.chat-box');
    box.scrollTop = box.scrollHeight;

    // submit chat
    $('#formDiskusi').submit(function(e) {
        e.preventDefault();

        $.post('/internalmemo/{{ $id }}/diskusi', $(this).serialize(), function() {
            $('#loadBelumPaham').load('/internalmemo/{{ $id }}/diskusi');
        });
    });
</script>

@extends('layouts.frontapp')


@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h5>🤖 OpenAI Chatbot</h5>
                </div>
                <div class="card-body">
                    <div id="chat-box" style="height: 300px; overflow-y: auto; border:1px solid #ddd; padding:10px;">
                        <p class="text-muted text-center">Start chatting below 👇</p>
                    </div>
                    <form id="chat-form" class="mt-3">
                        @csrf
                        <div class="input-group">
                            <input type="text" id="message" name="message" class="form-control"
                                placeholder="Type your message..." required>
                            <button class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Simple Chat Script -->
<script>
document.getElementById('chat-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const msgBox = document.getElementById('message');
    const chatBox = document.getElementById('chat-box');
    const userMsg = msgBox.value.trim();
    if (userMsg === '') return;

    // Show user message
    chatBox.innerHTML += `<div><strong>You:</strong> ${userMsg}</div>`;
    msgBox.value = '';
    chatBox.scrollTop = chatBox.scrollHeight;

    // Send message to Laravel route
    fetch("{{ route('chatbot.send') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message: userMsg
            })
        })
        .then(res => res.json())
        .then(data => {
            chatBox.innerHTML += `<div class="text-primary"><strong>AI:</strong> ${data.reply}</div>`;
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(err => {
            chatBox.innerHTML += `<div class="text-danger">⚠️ Error sending message!</div>`;
        });
});
</script>

@endsection
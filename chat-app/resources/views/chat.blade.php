@extends('layouts.app')

@section('content')
<div class="container">
    <div id="chat-box" style="border:1px solid #ccc;height:300px;overflow-y:scroll;padding:10px;">
        @foreach ($messages as $message)
            <div><strong>{{ $message->user->name }}</strong>: {{ $message->content }}</div>
        @endforeach
    </div>
    <form id="chat-form">
        @csrf
        <input type="text" name="message" id="message" placeholder="Votre message" required>
        <button type="submit">Envoyer</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="/js/app.js"></script>
<script>
    const chatBox = document.getElementById('chat-box');
    const form = document.getElementById('chat-form');
    const messageInput = document.getElementById('message');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        axios.post("{{ route('chat.send') }}", {
            message: messageInput.value,
        }).then(() => {
            messageInput.value = '';
        });
    });

    window.Echo.channel('chat')
        .listen('.message.sent', (e) => {
            const msg = document.createElement('div');
            msg.innerHTML = `<strong>${e.message.user.name}</strong>: ${e.message.content}`;
            chatBox.appendChild(msg);
            chatBox.scrollTop = chatBox.scrollHeight;
        });
</script>
@endsection

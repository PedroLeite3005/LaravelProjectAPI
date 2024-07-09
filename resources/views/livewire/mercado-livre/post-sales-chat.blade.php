<div>
    <div class="card col-12">
        <div class="card-title bg-warning">
            <div class="row d-flex justify-content-center">
                <h1 class="mx-2">Conversa com {{ $buyerName }}</h1>
            </div>
        </div>
        <div class="card-body">
            <div class="row d-flex flex-column">
                @foreach ($orderChat['data'] as $message)
                    <div class="p-1 border rounded mt-3
                            @if ($message['seller'] == true) bg-success border-success ml-auto @else bg-secondary border-secondary mr-auto @endif">
                        <p class="my-0">{{ $message['text'] }}</p>
                    </div>
                    <span class="my-0 @if ($message['seller'] == true) text-right @endif">{{ $message['message_date'] }}</span>
                @endforeach
            </div>
            <textarea class="col-12 my-1" placeholder="Escreva sua mensagem" wire:model.defer="message"></textarea>
        </div>
        <div class="card-footer bg-gray disabled color-palette d-flex justify-content-between align-items-center">
            <p class="col-11">Status da conversa: {{ $orderChat['conversation_status'] }}</p>
            <button class="btn btn-primary col-1" wire:click="sendMessage">Enviar</button>
        </div>
    </div>
</div>

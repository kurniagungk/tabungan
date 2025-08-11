<div class="flex flex-col gap-4">

    <x-card title="Lembaga" separator>

        @role('admin')
            <x-select label="Lembaga" wire:model.live="saldo_id" :options="$dataSaldo" option-value="id" option-label="nama" />
        @endrole

    </x-card>


    <x-card title="Chat" separator>

        <div class="grid grid-cols-10 gap-3">
            <div class="col-span-3">



                <ul
                    class="menu bg-base-200 rounded-box w-full p-2 h-[60vh] overflow-y-auto whitespace-normal break-words">
                    @foreach ($contacts as $contact)
                        <li wire:click="setContact('{{ $contact['id'] }}')" class="w-full cursor-pointer">
                            <div
                                class="w-full grid grid-cols-6 items-center gap-2 {{ $contactId == $contact['id'] ? 'menu-active' : '' }}">
                                <div><img class="size-10 rounded-box" src="{{ $contact['imgUrl'] }}" /></div>
                                <div class="list-col-grow col-span-4">
                                    <div class="break-words">
                                        {{ $contact['name'] ?? explode('@', $contact['id'])[0] }}
                                    </div>
                                </div>
                                @if ($contact['unreadCount'] > 0)
                                    <div class="badge badge-error">{{ $contact['unreadCount'] }}</div>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>



            </div>
            <div class="col-span-7 bg-base-100">

                <div id="chat-container"
                    class="h-[50vh] overflow-y-auto p-4 flex flex-col-reverse space-y-2 space-y-reverse">
                    @foreach ($chats as $chat)
                        @if (!empty($chat['message']['conversation']))
                            @if ($chat['key']['fromMe'])
                                <div class="chat chat-end">
                                    <div class="chat-bubble">{!! nl2br(e($chat['message']['conversation'])) !!}</div>
                                </div>
                            @else
                                <div class="chat chat-start">
                                    <div class="chat-bubble">{!! nl2br(e($chat['message']['conversation'])) !!}</div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                </div>


                <div class="flex items-end gap-2 mt-10">
                    <textarea wire:model.defer="message" rows="1" placeholder="Tulis pesan..."
                        x-on:keydown.enter="
        if (!event.shiftKey) {
            event.preventDefault();
            $wire.sendMessage();
        }
    "
                        class="flex-1 p-2 border rounded resize-none"></textarea>

                    <x-button icon="o-paper-airplane" class="btn-circle" type="button" aria-label="Kirim pesan"
                        wire:click="sendMessage" />
                </div>





            </div>
        </div>



    </x-card>
</div>

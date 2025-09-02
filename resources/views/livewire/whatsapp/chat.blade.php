<div class="flex flex-col gap-4">

    <x-card title="Lembaga" separator>

        @role('admin')
        <x-select label="Lembaga" wire:model.live="saldo_id" :options="$dataSaldo" option-value="id"
            option-label="nama" />
        @endrole

    </x-card>

    @if ($contacts)
    <x-card title="Chat" separator>
        <div class="grid grid-cols-10 gap-3">

            <!-- Sidebar kontak -->
            <div class="col-span-3">
                <div class="bg-base-200 rounded-box p-2">
                    <!-- Wrapper scroll -->
                    <div class="max-h-[60vh] overflow-y-auto overflow-x-hidden">
                        <ul class="menu w-full">
                            @foreach ($contacts as $contact)
                            <li wire:click="setContact('{{ $contact['id'] }}')" class="w-full cursor-pointer min-w-0">
                                <div
                                    class="w-full grid grid-cols-[auto,1fr,auto] items-center gap-2 min-w-0 {{ $contactId == $contact['id'] ? 'menu-active' : '' }}">

                                    <!-- Foto -->
                                    <div>
                                        <img class="size-10 rounded-box" src="{{ $contact['imgUrl'] }}"
                                            alt="Contact Image" />
                                    </div>

                                    <!-- Nama -->
                                    <div class="min-w-0">
                                        <div class="truncate">
                                            {{ $contact['name'] ?? explode('@', $contact['id'])[0] }}
                                        </div>
                                    </div>

                                    <!-- Badge -->
                                    @if ($contact['unreadCount'] > 0)
                                    <div class="badge badge-error shrink-0">
                                        {{ $contact['unreadCount'] }}
                                    </div>
                                    @endif
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Area chat -->
            <div class="col-span-7 bg-base-100">

                <!-- Pesan -->
                <div id="chat-container"
                    class="h-[50vh] overflow-y-auto p-4 flex flex-col-reverse space-y-2 space-y-reverse">
                    @foreach ($chats as $chat)
                    @if (!empty($chat['message']['conversation']))
                    @if ($chat['key']['fromMe'])
                    <div class="chat chat-end">
                        <div class="chat-bubble">
                            {!! nl2br(e($chat['message']['conversation'])) !!}
                        </div>
                        <div class="chat-footer opacity-50">{{ date('d-m-Y H:i', $chat['messageTimestamp']) }}</div>
                    </div>
                    @else
                    <div class="chat chat-start">
                        <div class="chat-bubble">
                            {!! nl2br(e($chat['message']['conversation'])) !!}
                        </div>
                        <div class="chat-footer opacity-50">{{ date('d-m-Y H:i', $chat['messageTimestamp']) }}</div>
                    </div>
                    @endif
                    @endif
                    @endforeach
                </div>

                <!-- Input pesan -->
                <div class="flex items-end gap-2 mt-10">
                    <textarea wire:model.defer="message" rows="1" placeholder="Tulis pesan..." x-on:keydown.enter="
                        if (!event.shiftKey) {
                            event.preventDefault();
                            $wire.sendMessage();
                        }
                    " class="flex-1 p-2 border rounded resize-none"></textarea>

                    <x-button icon="o-paper-airplane" class="btn-circle" type="button" aria-label="Kirim pesan"
                        wire:click="sendMessage" />
                </div>

            </div>
        </div>
    </x-card>
    @endif



</div>
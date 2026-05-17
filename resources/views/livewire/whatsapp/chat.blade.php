<div class="flex gap-2 md:flex-row flex-col overflow-hidden h-[90vh]">

    <div class="flex flex-col md:w-64 shrink-0 bg-[#0f131a] border border-[rgba(255,255,255,0.05)] rounded-2xl">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-5">
            <h2 class="text-white font-bold text-xl">Chats</h2>
            <button class="hover:text-gray-300 transition-colors" style="color:#6b7280">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M6 10a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm6 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm6 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0z" />
                </svg>
            </button>
        </div>

        {{-- Search --}}
        <div class="flex gap-2 px-4 pb-3">
            <x-button icon="o-bars-3" class="md:hidden blok btn-ghost h-9 rounded-xl px-3 my-auto bg-[#1a2030]" />

            <div class="w-full">
                <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-[#1a2030]">
                    <x-icon name="o-magnifying-glass" class="cursor-pointer h-3 w-3" />

                    <input type="text" placeholder="Search..." class="bg-transparent text-sm outline-none w-full"
                        style="color:#9ca3af; caret-color:#9ca3af" placeholder-style="color:#4b5563">
                </div>
            </div>
        </div>

        {{-- Contact List --}}
        <div class="flex-1 overflow-y-auto px-2 space-y-0.5 md:block hidden">
            @foreach ($contacts as $contact)
                <button wire:click="setActiveChat({{ $contact['id'] }})"
                    class="flex items-center gap-3 w-full px-3 py-3 rounded-xl transition-all text-left hover:bg-white/5 {{ $activeChat === $contact['id'] ? 'bg-[#1e2a3a]' : '' }}">
                    <div class="relative shrink-0">
                        <img src="{{ $contact['avatar'] }}" alt="{{ $contact['name'] }}"
                            class="w-10 h-10 rounded-full object-cover">
                        <span
                            class="absolute bottom-0 right-0 w-3 h-3 rounded-full border-2
                    {{ $contact['status'] === 'online' ? 'bg-green-400' : ($contact['status'] === 'away' ? 'bg-yellow-400' : 'bg-gray-500') }}"
                            style="border-color:#0f131a">
                        </span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate" style="color:#e2e8f0">{{ $contact['name'] }}</p>
                        <p class="text-xs truncate" style="color:#6b7280">{{ $contact['role'] }}</p>
                    </div>
                    <span class="text-xs shrink-0" style="color:#4b5563">{{ $contact['time'] }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <div class="flex flex-col flex-1 overflow-hidden rounded-2xl" style="background:#141921">

        {{-- Chat Header --}}
        <div class="flex items-center justify-between px-6 py-4"
            style="border-bottom: 1px solid rgba(255,255,255,0.05)">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <img src="{{ $activeContact['avatar'] ?? '' }}" alt="{{ $activeContact['name'] ?? '' }}"
                        class="w-10 h-10 rounded-full object-cover">
                    <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-green-400 border-2"
                        style="border-color:#141921"></span>
                </div>
                <div>
                    <p class="font-semibold text-sm" style="color:#e2e8f0">{{ $activeContact['name'] ?? '' }}</p>
                    <p class="text-xs" style="color:#4ade80">Online</p>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <button class="p-2 rounded-full hover:bg-white/5 transition-colors" style="color:#6b7280">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>
                </button>
                <button class="p-2 rounded-full hover:bg-white/5 transition-colors" style="color:#6b7280">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </button>
                <button class="p-2 rounded-full hover:bg-white/5 transition-colors" style="color:#6b7280">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M6 10a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm6 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm6 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0z" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Messages Area --}}
        <div x-ref="msgContainer" class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
            @foreach ($this->activeMessages as $msg)
                <div>
                    @if ($msg['sender'] === 'them')
                        <div class="flex items-end gap-2">
                            <img src="{{ $msg['avatar'] }}" class="w-8 h-8 rounded-full object-cover shrink-0 mb-5">
                            <div class="max-w-xs lg:max-w-md">
                                @if (!empty($msg['image']))
                                    <div class="rounded-2xl overflow-hidden" style="background:#1e2535">
                                        <img src="{{ $msg['image'] }}" class="w-full max-w-xs object-cover">
                                        <p class="text-sm px-3 py-2" style="color:#e2e8f0">{{ $msg['caption'] }}</p>
                                    </div>
                                @else
                                    <div class="px-4 py-3 rounded-2xl rounded-bl-sm" style="background:#1e2535">
                                        <p class="text-sm leading-relaxed" style="color:#e2e8f0">{{ $msg['text'] }}
                                        </p>
                                    </div>
                                @endif
                                <p class="text-xs mt-1 ml-1" style="color:#4b5563">{{ $msg['time'] }}</p>
                            </div>
                        </div>
                    @else
                        <div class="flex justify-end">
                            <div class="max-w-xs lg:max-w-md">
                                <div class="px-4 py-3 rounded-2xl rounded-br-sm" style="background:#4f46e5">
                                    <p class="text-sm leading-relaxed text-white">{{ $msg['text'] }}</p>
                                </div>
                                @if ($msg['time'])
                                    <p class="text-xs mt-1 text-right mr-1" style="color:#4b5563">{{ $msg['time'] }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Input Area --}}
        <div class="px-6 py-4" style="border-top: 1px solid rgba(255,255,255,0.05)">
            <div class="flex items-center gap-3 px-4 py-3 rounded-2xl" style="background:#1a2030">
                <button class="shrink-0 hover:text-yellow-400 transition-colors" style="color:#6b7280">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                    </svg>
                </button>

                <input type="text" wire:model.live="newMessage" wire:keydown.enter="sendMessage"
                    placeholder="Type a message" class="flex-1 bg-transparent text-sm outline-none"
                    style="color:#e2e8f0">

                <button class="shrink-0 hover:text-gray-300 transition-colors" style="color:#6b7280">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                    </svg>
                </button>

                <button class="shrink-0 hover:text-gray-300 transition-colors" style="color:#6b7280">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z" />
                    </svg>
                </button>

                <button wire:click="sendMessage"
                    class="shrink-0 w-9 h-9 rounded-xl flex items-center justify-center transition-all hover:opacity-90 active:scale-95"
                    style="background:#4f46e5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

</div>

 <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

     {{-- BRAND --}}
     <x-app-brand class="px-5 pt-4" />

     {{-- MENU --}}
     <x-menu activate-by-route>

         {{-- User --}}
         @if ($user = auth()->user())
             <x-menu-separator />

             <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover
                 class="-mx-2 !-my-2 rounded">
                 <x-slot:actions>
                     <livewire:auth.logout />
                 </x-slot:actions>
             </x-list-item>

             <x-menu-separator />
         @endif

         <x-menu-item title="Dashboard" icon="o-squares-plus" link="/dashboard" />
         <x-menu-item title="Ai Assistant" icon="o-sparkles" link="/ai" />

         <x-menu-separator />

         <x-menu-item title="WhatsApp" icon="o-chat-bubble-oval-left" link="/whatsapp/chats" />

         {{-- <x-menu-sub title="Settings" icon="o-cog-6-tooth">
             <x-menu-item title="Wifi" icon="o-wifi" link="####" />
             <x-menu-item title="Archives" icon="o-archive-box" link="####" />
         </x-menu-sub> --}}
     </x-menu>
 </x-slot:sidebar>

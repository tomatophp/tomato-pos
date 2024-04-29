<div class="flex flex-col items-center p-4 justify-center bg-primary-500 dark:bg-zinc-800 rounded-lg">
    <ul class="flex gap-2">
        <li>
            <x-splade-link href="{{route('admin')}}"
                           class="flex items-center">
                <x-tomato-admin-tooltip :text="__('Home')">
                        <span
                            class="flex items-center justify-center h-12 w-12 rounded-2xl hover:bg-primary-400 text-primary-100 dark:hover:bg-zinc-400 dark:text-zinc-100"

                        >
                            <x-heroicon-s-home class="w-6 h-6" />
                        </span>
                </x-tomato-admin-tooltip>
            </x-splade-link>
        </li>
        <li>
            <x-splade-link href="{{route('admin.pos.index')}}"
                           class="flex items-center">
                <x-tomato-admin-tooltip :text="__('POS')">
                        <span
                            class="flex items-center justify-center h-12 w-12 rounded-2xl"
                            :class="{
                                      'hover:bg-primary-400 text-primary-100 dark:hover:bg-zinc-400 dark:text-zinc-100': @js(url()->current() !== route('admin.pos.index')),
                                      'bg-primary-300 dark:bg-zinc-700 shadow-lg text-white': @js(url()->current() === route('admin.pos.index')),
                                  }"
                        >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                              </span>
                </x-tomato-admin-tooltip>
            </x-splade-link>
        </li>
        <li>
            <x-splade-link href="{{route('admin.pos.orders.index')}}" class="flex items-center"
            >
                <x-tomato-admin-tooltip :text="__('POS Orders')">
                        <span
                            class="flex items-center justify-center h-12 w-12 rounded-2xl"
                            :class="{
                                      'hover:bg-primary-400 text-primary-100 dark:hover:bg-zinc-400 dark:text-zinc-100': @js(url()->current() !== route('admin.pos.orders.index')),
                                      'bg-primary-300 dark:bg-zinc-700 shadow-lg text-white': @js(url()->current() === route('admin.pos.orders.index')),
                                  }"
                        >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                              </span>
                </x-tomato-admin-tooltip>
            </x-splade-link>
        </li>
        <li>
            <x-splade-link href="{{route('admin.pos.inventory')}}"
                           class="flex items-center">
                <x-tomato-admin-tooltip :text="__('POS Inventory Request')">
                        <span
                            class="flex items-center justify-center h-12 w-12 rounded-2xl"
                            :class="{
                                      'hover:bg-primary-400 text-primary-100 dark:hover:bg-zinc-400 dark:text-zinc-100': @js(url()->current() !== route('admin.pos.inventory')),
                                      'bg-primary-300 dark:bg-zinc-700 shadow-lg text-white': @js(url()->current() === route('admin.pos.inventory')),
                                  }"
                        >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                              </span>
                </x-tomato-admin-tooltip>
            </x-splade-link>
        </li>
        <li>
            <x-splade-link href="{{route('admin.pos.settings')}}"
                           class="flex items-center">
                <x-tomato-admin-tooltip :text="__('POS Settings')">
                        <span
                            class="flex items-center justify-center h-12 w-12 rounded-2xl"
                            :class="{
                                      'hover:bg-primary-400 text-primary-100 dark:hover:bg-zinc-400 dark:text-zinc-100': @js(url()->current() !== route('admin.pos.settings')),
                                      'bg-primary-300 dark:bg-zinc-700 shadow-lg text-white': @js(url()->current() === route('admin.pos.settings')),
                                  }"
                        >
                                <svg class="w-6 h-6"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                  <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                  <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                              </span>
                </x-tomato-admin-tooltip>
            </x-splade-link>
        </li>
    </ul>
</div>

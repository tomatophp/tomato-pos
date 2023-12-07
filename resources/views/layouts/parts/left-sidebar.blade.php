<div class="flex flex-row w-auto pl-4 pr-2 py-4">
    <div class="flex flex-col items-center py-4 flex-shrink-0 w-20 bg-primary-500 rounded-3xl">
        <x-splade-link href="{{route('admin')}}"
                       class="flex items-center justify-center h-12 w-12 bg-cyan-50 text-cyan-700 rounded-full">
            @if(setting('site_logo'))
                <img src="{{setting('site_logo')}}" class="p-2" alt="{{setting('site_name')}}" />
            @else
                <x-application-logo class="w-6 h-6 p-2" />
            @endif
        </x-splade-link>
        <ul class="flex flex-col space-y-2 mt-12">
            <li>
                <x-splade-link href="{{route('admin.pos.index')}}"
                               class="flex items-center">
                              <span
                                  class="flex items-center justify-center h-12 w-12 rounded-2xl"
                                  :class="{
                                      'hover:bg-primary-400 text-primary-100': @js(url()->current() !== route('admin.pos.index')),
                                      'bg-primary-300 shadow-lg text-white': @js(url()->current() === route('admin.pos.index')),
                                  }"
                              >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                              </span>
                </x-splade-link>
            </li>
            <li>
                <x-splade-link href="{{route('admin.pos.orders.index')}}" class="flex items-center"
                >
                              <span
                                  class="flex items-center justify-center h-12 w-12 rounded-2xl"
                                    :class="{
                                      'hover:bg-primary-400 text-primary-100': @js(url()->current() !== route('admin.pos.orders.index')),
                                      'bg-primary-300 shadow-lg text-white': @js(url()->current() === route('admin.pos.orders.index')),
                                  }"
                              >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                              </span>
                </x-splade-link>
            </li>
            <li>
                <x-splade-link href="{{route('admin.pos.inventory')}}"
                               class="flex items-center">
                              <span
                                  class="flex items-center justify-center h-12 w-12 rounded-2xl"
                                    :class="{
                                      'hover:bg-primary-400 text-primary-100': @js(url()->current() !== route('admin.pos.inventory')),
                                      'bg-primary-300 shadow-lg text-white': @js(url()->current() === route('admin.pos.inventory')),
                                  }"
                              >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                              </span>
                </x-splade-link>
            </li>
            <li>
                <x-splade-link href="{{route('admin.pos.settings')}}"
                               class="flex items-center">
                              <span
                                  class="flex items-center justify-center h-12 w-12 rounded-2xl"
                                    :class="{
                                      'hover:bg-primary-400 text-primary-100': @js(url()->current() !== route('admin.pos.settings')),
                                      'bg-primary-300 shadow-lg text-white': @js(url()->current() === route('admin.pos.settings')),
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
                </x-splade-link>
            </li>
        </ul>
    </div>
</div>

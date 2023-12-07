<!-- right sidebar -->
<div class="w-5/12 flex flex-col bg-blue-gray-50 h-full bg-white pr-4 pl-2 py-4">
    <div class="bg-white rounded-3xl flex flex-col h-full shadow">
        <!-- empty cart -->
        @if(!count($cart))
            <div  class="flex-1 w-full p-4 opacity-25 select-none flex flex-col flex-wrap content-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p>
                    {{__('CART EMPTY')}}
                </p>
            </div>
        @else
            <!-- cart items -->
            <div  class="flex-1 flex flex-col overflow-auto">
                <div class="h-16 text-center flex justify-center">
                    <div class="pl-8 text-left text-lg py-4 relative">
                        <!-- cart icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <div  class="text-center absolute bg-cyan-500 text-white w-5 h-5 text-xs p-0 leading-5 rounded-full -right-2 top-3">{{$cart->count()}}</div>
                    </div>
                    <div class="flex-grow px-8 text-right text-lg py-4 relative">
                        <!-- trash button -->
                        <x-splade-link confirm method="delete" href="{{route('admin.pos.cart.clear')}}"  class="qty text-blue-gray-300 hover:text-pink-500 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </x-splade-link>
                    </div>
                </div>

                <div class="flex-1 w-full px-4 overflow-auto">
                    @foreach($cart as $item)
                        <x-splade-form preserve-scroll submit-on-change method="POST" action="{{route('admin.pos.cart.update', $item->id)}}" :default="$item->toArray()">
                            <div class="flex flex-col gap-4 justify-center select-none mb-3 bg-blue-gray-50 rounded-lg w-full text-blue-gray-700 py-2 px-2 ">
                                <div class="flex justify-start gap-2">
                                    <img src="{{$item->product->getMedia('featured_image')?->first()->getUrl() ?? url('placeholder.webp')}}" alt="" class="rounded-lg h-10 w-10 bg-white shadow mr-2">
                                    <div class="flex-grow">
                                        <div class="flex justify-start gap-2">
                                            <h5 class="text-sm">{{$item->item}}</h5>
                                            @foreach($item->options ??[] as $option)
                                                <div class="flex flex-col items-center justify-center">
                                                    <h4 class="text-xs block text-gray-400">{{str($option)->title}}</h4>
                                                </div>
                                            @endforeach
                                        </div>
                                        <p class="text-xs block">{!! dollar($item->total) !!}</p>
                                    </div>
                                </div>
                                <div>
                                    <div class="grid grid-cols-3 gap-2 ml-2">
                                        <button @click.prevent="form.qty=parseFloat(form.qty) - 1" class="qty rounded-lg text-center py-1 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <x-splade-input name="qty"   type="number" class="bg-white text-black rounded-lg text-center shadow focus:outline-none focus:shadow-lg text-sm" />
                                        <button @click.prevent="form.qty=parseFloat(form.qty) + 1" class="qty rounded-lg text-center py-1 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </x-splade-form>
                    @endforeach

                </div>
            </div>
            <!-- end of cart items -->

            <!-- payment info -->
            <x-splade-form  :default="['payment_method'=>'cash','cash' => 0, 'total' => $cart->sum('total'), 'account_id' => request()->get('account_id') ? config('tomato-crm.model')::find(request()->get('account_id')) : null, 'attachCustomer' => request()->get('account_id') ? true : false]" method="POST" action="{{route('admin.pos.place')}}">

            <div class="select-none h-auto w-full text-center pt-3 pb-4 px-4">
                <div v-show="!form.attachCustomer">
                    <div class="flex mb-3 text-lg font-semibold text-blue-gray-700">
                        <div>{{__('TOTAL')}}</div>
                        <div class="text-right w-full">
                            {!! dollar($cart->sum('total')) !!}
                        </div>
                    </div>
                    <div  class="mb-3 text-blue-gray-700 px-3 pt-2 pb-3 rounded-lg bg-blue-gray-50" v-if="form.payment_method === 'cash'">
                        <div class="flex text-lg font-semibold">
                            <div class="flex-grow text-left">{{__('CASH')}}</div>
                            <div class="flex text-right">
                                <input v-model="form.cash" type="text" class="w-28 text-right bg-white shadow rounded-lg focus:bg-white focus:shadow-lg px-2 focus:outline-none">
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="grid grid-cols-2 gap-1 mt-2">
                            <button @click.prevent="form.cash = parseFloat(form.cash)+5" class="qty bg-white rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">
                                <div class="flex justify-center gap-2">
                                    <span>+</span>
                                    <span>
                                    {!! dollar(5) !!}
                                </span>
                                </div>
                            </button>
                            <button @click.prevent="form.cash = parseFloat(form.cash)+10" class="qty bg-white rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">
                                <div class="flex justify-center gap-2">
                                    <span>+</span>
                                    <span>
                                    {!! dollar(10) !!}
                                </span>
                                </div>
                            </button>
                            <button @click.prevent="form.cash = parseFloat(form.cash)+20" class="qty bg-white rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">
                                <div class="flex justify-center gap-2">
                                    <span>+</span>
                                    <span>
                                    {!! dollar(20) !!}
                                </span>
                                </div>
                            </button>
                            <button @click.prevent="form.cash += 50" class="qty bg-white rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">
                                <div class="flex justify-center gap-2">
                                    <span>+</span>
                                    <span>
                                    {!! dollar(50) !!}
                                </span>
                                </div>
                            </button>
                            <button @click.prevent="form.cash = parseFloat(form.cash)+100" class="qty bg-white rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">
                                <div class="flex justify-center gap-2">
                                    <span>+</span>
                                    <span>
                                    {!! dollar(100) !!}
                                </span>
                                </div>
                            </button>
                            <button @click.prevent="form.cash = parseFloat(form.cash)+200" class="qty bg-white rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">
                                <div class="flex justify-center gap-2">
                                    <span>+</span>
                                    <span>
                                    {!! dollar(200) !!}
                                </span>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div
                        class="flex mb-3 text-lg font-semibold bg-primary-50 text-primary-700 rounded-lg py-2 px-3"
                    >
                        <div class="text-primary-800">{{__('CHANGE')}}</div>
                        <div
                            class="text-right flex-grow text-primary-600">
                            @{{ form.total - form.cash }}<small class="font-bold">{{setting('local_currency')}}</small>
                        </div>
                    </div>
                </div>
                <div v-show="form.attachCustomer" class="flex flex-col gap-4 my-4">
                    <h1>{{__('Attach Account Data')}}</h1>
                    <x-tomato-search
                        :remote-url="route('admin.orders.user')"
                        remote-root="data"
                        name="account_id"
                        placeholder="{{__('Search By Account Phone / Email / Name')}}"
                        label="{{__('Select Account')}}"
                    />
                    <x-tomato-admin-button modal href="{{route('admin.pos.account')}}">
                        {{__('Or create account')}}
                    </x-tomato-admin-button>
                    <div v-if="form.errors.account_id"
                         class="text-danger-500 mt-2 text-xs font-chakra flex gap-2 mb-[6px]">
                        <p v-text="form.errors.account_id"> </p>
                    </div>
                    <div v-if="form.account_id">
                        <div class="text-lg font-bold mt-2">
                            @{{form.account_id.name}}
                        </div>
                        <div class="text-sm">
                            @{{form.account_id.email}}
                        </div>
                        <div class="text-sm">
                            @{{form.account_id.phone}}
                        </div>
                        <div class="text-sm">
                            @{{form.account_id.address}}
                        </div>
                        <div class="text-sm">
                            @{{form.account_id.zip}} @{{form.account_id.city}}
                        </div>
                        <div class="text-sm">
                            @{{form.account_id.country?form.account_id.country.name:''}}
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <x-splade-select v-if="form.account_id" choices name="payment_method" label="{{__('Payment Method')}}" placeholder="{{__('Select Payment Method')}}">
                        <option value="cash">{{__('Cash')}}</option>
                        <option value="credit">{{__('Credit')}}</option>
                        <option value="wallet">{{__('Wallet')}}</option>
                    </x-splade-select>
                    <x-splade-select v-if="!form.account_id"  choices name="payment_method" label="{{__('Payment Method')}}" placeholder="{{__('Select Payment Method')}}">
                        <option value="cash">{{__('Cash')}}</option>
                        <option value="credit">{{__('Credit')}}</option>
                    </x-splade-select>
                    <x-tomato-admin-button warning type="button" @click.prevent="form.attachCustomer = !form.attachCustomer">
                        {{__('Attach Account')}}
                    </x-tomato-admin-button>
                    <x-tomato-admin-submit spinner>
                        {{__('Place Order')}}
                    </x-tomato-admin-submit>
                </div>
            </div>
            </x-splade-form>

            <!-- end of payment info -->
        @endif

    </div>
</div>
<audio id="qty" style="{display: none}" src="{{url('sound/button-21.mp3')}}" preload="auto"></audio>
<x-splade-script>
    document.querySelectorAll(".qty").forEach((button) =>  button.addEventListener("click", () => document.getElementById("qty").play(), false));
</x-splade-script>

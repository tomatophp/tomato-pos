
<!-- right sidebar -->
<div class="w-5/12 flex flex-col h-full pr-4 pl-2 py-4">
    <div class="bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-lg flex flex-col h-full shadow">
        <!-- empty cart -->
        @if(!count($cart))
            <div  class="flex-1 w-full p-4 opacity-25 select-none flex flex-col flex-wrap content-center justify-center">
                <i class="bx bx-cart bx-lg text-center"></i>
                <p class="font-bold">
                    {{__('Cart is empty')}}
                </p>
            </div>
        @else
            <!-- cart items -->
            <div  class="flex-1 flex flex-col overflow-auto">
                <div class="flex justify-between p-4">
                    <div class="relative w-full ">
                        <!-- cart icon -->
                        <i class="bx bx-cart bx-sm"></i>
                        <div  class="text-center absolute bg-primary-500 text-white w-5 h-5 text-xs p-0 leading-5 rounded-full left-5 -top-1">{{$cart->count()}}</div>
                    </div>
                    <div class="relative">
                        <!-- trash button -->
                        <x-splade-link confirm method="delete" href="{{route('admin.pos.cart.clear')}}"  class="qty hover:text-danger-500 focus:outline-none">
                            <i class="bx bx-trash bx-sm"></i>
                        </x-splade-link>
                    </div>
                </div>

                <div class="flex-1 w-full px-4 overflow-auto">
                    @foreach($cart as $item)
                        <x-splade-form preserve-scroll submit-on-change method="POST" action="{{route('admin.pos.cart.update', $item->id)}}" :default="$item->toArray()">
                            <div class="flex flex-col gap-4 justify-center select-none mb-3 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg w-full text-primary-700 dark:text-gray-200 p-3 ">
                                <div class="flex justify-start gap-2">
                                    <img src="{{$item->product->getMedia('featured_image')->first()?->getUrl() ?? url('placeholder.webp')}}" alt="" class="rounded-lg h-10 w-10 bg-white shadow mr-2">
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
                                        <button @click.prevent="form.qty=parseFloat(form.qty) - 1" class="qty rounded-lg text-center py-1 text-white bg-danger-600 hover:bg-danger-700 focus:outline-none">
                                            <i class="bx bx-minus"></i>
                                        </button>
                                        <x-splade-input name="qty" type="number" />
                                        <button @click.prevent="form.qty=parseFloat(form.qty) + 1" class="qty rounded-lg text-center py-1 text-white bg-primary-600 hover:bg-primary-700 focus:outline-none">
                                            <i class="bx bx-plus"></i>
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
                    <div class="flex mb-3 text-lg font-semibold text-primary-700 dark:text-gray-200">
                        <div>{{__('TOTAL')}}</div>
                        <div class="text-right w-full font-bold">
                            {!! dollar($cart->sum('total')) !!}
                        </div>
                    </div>
                    <div  class="mb-3 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-primary-700 dark:text-gray-200 px-3 py-4 rounded-lg " v-if="form.payment_method === 'cash'">
                        <div class="flex justify-between text-lg font-semibold">
                            <div class="flex flex-col justify-center items-center">{{__('CASH')}}</div>
                            <div>
                                <input v-model="form.cash" type="text" class="fi-input block w-full border-none bg-white dark:bg-gray-800 py-1.5 text-base text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 ps-3 pe-3 focus:ring-2 ring-primary-500 focus:ring-2 focus:ring-primary-500 rounded-lg">
                            </div>
                        </div>
                        <div class="my-2 border-t border-gray-200 dark:border-gray-700"></div>
                        <div class="grid grid-cols-2 gap-1 mt-2">
                            <button @click.prevent="form.cash = parseFloat(form.cash)+5" class="qty bg-white dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 border border-gray-200 rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">
                                <div class="flex justify-center gap-2">
                                    <span>+</span>
                                    <span>
                                    {!! dollar(5) !!}
                                </span>
                                </div>
                            </button>
                            <button @click.prevent="form.cash = parseFloat(form.cash)+10" class="qty bg-white dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 border border-gray-200 rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">
                                <div class="flex justify-center gap-2">
                                    <span>+</span>
                                    <span>
                                    {!! dollar(10) !!}
                                </span>
                                </div>
                            </button>
                            <button @click.prevent="form.cash = parseFloat(form.cash)+20" class="qty bg-white dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 border border-gray-200 rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">
                                <div class="flex justify-center gap-2">
                                    <span>+</span>
                                    <span>
                                    {!! dollar(20) !!}
                                </span>
                                </div>
                            </button>
                            <button @click.prevent="form.cash += 50" class="qty bg-white rounded-lg shadow dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 border border-gray-200 hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">
                                <div class="flex justify-center gap-2">
                                    <span>+</span>
                                    <span>
                                    {!! dollar(50) !!}
                                </span>
                                </div>
                            </button>
                            <button @click.prevent="form.cash = parseFloat(form.cash)+100" class="qty bg-white dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 border border-gray-200 rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">
                                <div class="flex justify-center gap-2">
                                    <span>+</span>
                                    <span>
                                    {!! dollar(100) !!}
                                </span>
                                </div>
                            </button>
                            <button @click.prevent="form.cash = parseFloat(form.cash)+200" class="qty bg-white dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 border border-gray-200 rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">
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
                        class="flex justify-between mb-3 text-lg font-semibold bg-gray-100 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 text-primary-700 rounded-lg py-2 px-3"
                    >
                        <div>{{__('CHANGE')}}</div>
                        <div
                            class="font-bold">
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

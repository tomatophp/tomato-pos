<x-splade-modal>
    <x-slot:title>
        {{__('Create Inventory Request')}}
    </x-slot:title>

    <x-splade-form class="flex flex-col space-y-4" action="{{route('admin.pos.inventory.store')}}" method="post" :default="[
        'type' => 'in',
        'status' => 'pending',
         'items' => [
            [
                'item' => '',
                'price' => 0,
                'discount' => 0,
                'qty' => 1,
                'tax' => 0,
                'total' => 0,
                'options' => (object)[]
            ]
        ],
        'uuid' => 'INVENTORY-'.\Illuminate\Support\Str::random(6).'-'.date('YmdHis')
    ]">

        <x-splade-input disabled name="uuid" placeholder="{{__('UUID')}}" label="{{__('UUID')}}" />
        <x-splade-select :label="__('Request From Branch')" :placeholder="__('Request From Branch')" name="branch_id" remote-url="/admin/branches/api" remote-root="data" option-label="name" option-value="id" choices/>
        <div>
            <x-tomato-items :options="['item'=>'', 'price'=>0, 'discount'=>0, 'tax'=>0, 'qty'=>1,'total'=>0, 'options' =>(object)[]]" name="items">
                <div class="grid grid-cols-12 gap-4 border-b dark:border-zinc-700 py-4 my-4">
                    <div class="col-span-5">
                        {{__('Item')}}
                    </div>
                    <div class="col-span-5">
                        {{__('QTY')}}
                    </div>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="grid grid-cols-12 gap-4" v-for="(item, key) in items.main">
                        <div class="col-span-5 flex justify-between gap-4">
                            <x-tomato-search
                                @change="
                                            items.main[key].price = items.main[key].item?.price;
                                            items.main[key].discount = items.main[key].item?.discount;
                                            items.main[key].tax = items.main[key].item?.vat;
                                            items.updateTotal(key)
                                        "
                                :remote-url="route('admin.orders.product')"
                                option-label="name?.{{app()->getLocale()}}"
                                remote-root="data"
                                v-model="items.main[key].item"
                                placeholder="{{__('Select Item')}}"
                                label="{{__('Product')}}"
                            />
                        </div>
                        <x-splade-input
                            class="col-span-5"
                            type="number"
                            placeholder="QTY"
                            v-model="items.main[key].qty"
                            @input="items.updateTotal(key)"
                        />
                        <button @click.prevent="items.addItem" class="filament-button inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm shadow-sm focus:ring-white filament-page-button-action bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 text-white border-transparent">
                            <i class="bx bx-plus"></i>
                        </button>
                        <button @click.prevent="items.removeItem(item)" class="filament-button inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm shadow-sm focus:ring-white filament-page-button-action bg-danger-600 hover:bg-danger-500 focus:bg-danger-700 focus:ring-offset-danger-700 text-white border-transparent">
                            <i class="bx bx-trash"></i>
                        </button>
                        <div class="col-span-3" v-if="items.main[key].item.has_options" v-for="(option, optionIndex) in items.main[key].item.product_metas[0].value">
                            <div >
                                <label for="">
                                    @{{ optionIndex.charAt(0).toUpperCase() + optionIndex.slice(1) }}
                                </label>
                                <x-splade-select v-model="items.main[key].options[optionIndex]">
                                    <option v-for="(value, valueIndex) in option" :value="value">
                                        @{{ value.charAt(0).toUpperCase() + value.slice(1) }}
                                    </option>
                                </x-splade-select>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="form.errors.items"
                     class="text-danger-500 mt-2 text-xs font-chakra flex gap-2 mb-[6px]">
                    <p v-text="form.errors.items"> </p>
                </div>
                <div class="flex flex-col gap-4 mt-4">
                    <div class="flex justify-between gap-4 py-4 border-b dark:border-zinc-700">
                        <div>
                            {{__('Tax')}}
                        </div>
                        <div>
                            @{{ items.tax }}
                        </div>
                    </div>
                    <div class="flex justify-between gap-4 py-4 border-b dark:border-zinc-700">
                        <div>
                            {{__('Sub Total')}}
                        </div>
                        <div>
                            @{{ items.price }}
                        </div>
                    </div>
                    <div class="flex justify-between gap-4 py-4 border-b dark:border-zinc-700">
                        <div>
                            {{__('Discount')}}
                        </div>
                        <div>
                            @{{ items.discount }}
                        </div>
                    </div>
                    <div class="flex justify-between gap-4 py-4 border-b dark:border-zinc-700">
                        <div>
                            {{__('Total')}}
                        </div>
                        <div>
                            @{{ items.total }}
                        </div>
                    </div>
                </div>
            </x-tomato-items>
        </div>

        <x-splade-textarea :label="__('Notes')" name="notes" :placeholder="__('Notes')" autosize />


        <div class="flex justify-start gap-2 pt-3">
            <x-tomato-admin-submit  label="{{__('Save')}}" :spinner="true" />
            <x-tomato-admin-button secondary :href="route('admin.inventories.index')" label="{{__('Cancel')}}"/>
        </div>
    </x-splade-form>
</x-splade-modal>

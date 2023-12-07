<x-splade-modal>
    <x-slot:title>
        {{__('Select Product Options')}}
    </x-slot:title>
    <x-splade-form class="flex flex-col gap-4" method="POST" action="{{route('admin.pos.cart.index')}}" :default="['product_id' => $product->id, 'options' => (object)[]]">
        @foreach($product->productMetas[0]->value ?? [] as $key=>$options)
            <x-splade-select name="options[{{$key}}]" label="{{str($key)->title()}}">
                @foreach($options as $option)
                    <option value="{{$option}}">{{str($option)->title()}}</option>
                @endforeach
            </x-splade-select>
        @endforeach
        <x-tomato-admin-submit spinner label="{{__('Select Options')}}" />
    </x-splade-form>
</x-splade-modal>

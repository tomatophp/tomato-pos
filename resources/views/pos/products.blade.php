<!-- store menu -->
<div class="flex flex-col  h-full w-full py-4">
    @include('tomato-pos::pos.search-form')
    <div class="h-full overflow-hidden mt-4">
        <div class="grid grid-cols-6 gap-4 pb-3 my-2 px-2">
            @foreach($categories as $category)
                <x-splade-link :href="route('admin.pos.index') . '?category_id=' . $category->id" :class="request()->get('category_id') == $category->id ? 'ring-2 ring-primary-500 flex flex-col justify-center items-center rounded-lg text-gray-800 dark:text-gray-200 border p-4 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900' : 'flex flex-col justify-center items-center rounded-lg text-gray-800 dark:text-gray-200 border p-4 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900'">
                    <div>
                        <i class="{{$category->icon}} bx-lg" style="color: {{$category->color}}"></i>
                    </div>
                    <div class="font-bold">
                        {{$category->name}}
                    </div>
                </x-splade-link>
            @endforeach
        </div>
        <div class="h-full overflow-y-auto px-2">
            @if(!count($products))
                @include('tomato-pos::pos.empty-state')
            @else
                <div class="grid grid-cols-4 gap-4 pb-3">
                    @foreach($products as $product)
                       @include('tomato-pos::pos.product-card')
                    @endforeach
                    <div>
                        {!! $products->links('tomato-admin::components.pagination') !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- end of store menu -->

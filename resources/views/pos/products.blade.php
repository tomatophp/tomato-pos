<!-- store menu -->
<div class="flex flex-col w-full py-4 col-span-1 md:col-span-8">
    @include('tomato-pos::pos.search-form')
    <div class="overflow-hidden mt-2">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pb-3 my-2 ">
            @foreach($categories as $category)
                <x-splade-link :href="route('admin.pos.index') . '?category_id=' . $category->id" :class="request()->get('category_id') == $category->id ? 'ring-2 ring-primary-500 flex flex-col justify-center items-center rounded-lg text-zinc-800 dark:text-zinc-200 border p-4 border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900' : 'flex flex-col justify-center items-center rounded-lg text-zinc-800 dark:text-zinc-200 border p-4 border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900'">
                    <div>
                        <i class="{{$category->icon}} bx-lg" style="color: {{$category->color}}"></i>
                    </div>
                    <div class="font-bold text-center">
                        {{$category->name}}
                    </div>
                </x-splade-link>
            @endforeach
        </div>
        <div>
            @if(!count($products))
                @include('tomato-pos::pos.empty-state')
            @else
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pb-3">
                    @foreach($products as $product)
                       @include('tomato-pos::pos.product-card')
                    @endforeach

                </div>
                <div>
                    {!! $products->links('tomato-admin::components.pagination') !!}
                </div>
            @endif
        </div>
    </div>
</div>
<!-- end of store menu -->

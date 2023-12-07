<!-- store menu -->
<div class="flex flex-col bg-primary-50 h-full w-full py-4">
    @include('tomato-pos::pos.search-form')
    <div class="h-full overflow-hidden mt-4">
        <div class="h-full overflow-y-auto px-2">
            @if(!count($products))
                @include('tomato-pos::pos.empty-state')
            @else
                <div class="grid grid-cols-4 gap-4 pb-3">
                    @foreach($products as $product)
                       @include('tomato-pos::pos.product-card')
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
<!-- end of store menu -->

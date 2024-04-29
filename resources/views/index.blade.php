@extends('tomato-pos::layouts.master')

@section('content')
    <div class="grid mt-4 grid-cols-1 md:grid-cols-2 gap-4">
        @php $orders = \TomatoPHP\TomatoOrders\Models\Order::query()->where('created_at', '<', \Carbon\Carbon::now())->where('source', 'pos'); @endphp
        <div class="bg-white dark:bg-zinc-800 dark:border-zinc-700 shadow-sm rounded-xl w-full text-center flex flex-col justify-center p-4 border">
            <i class="bx bx-rocket bx-md"></i>
            <h1 class="text-xl font-bold">{{$orders->count()}}</h1>
            <h1 class="text-md">{{__('Today Orders')}}</h1>
        </div>
        <div class="bg-white dark:bg-zinc-800 dark:border-zinc-700 shadow-sm rounded-xl w-full text-center flex flex-col justify-center p-4 border">
            <i class="bx bx-money bx-md"></i>
            <h1 class="text-xl font-bold">{!! dollar($orders->sum('total')) !!}</h1>
            <h1 class="text-md">{{__('Today Total Money')}}</h1>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
        @include('tomato-pos::pos.products')
        @include('tomato-pos::pos.cart')
    </div>
@endsection

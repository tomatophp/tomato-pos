@extends('tomato-pos::layouts.master')

@section('content')
   <div class="h-full w-full">
       <div class="border dark:text-gray-200 bg-gray-100 border-gray-200 dark:bg-gray-900 dark:border-gray-700 rounded-xl my-4 mx-4">
           <div class="flex justify-between gap-4 my-4 mx-4">
               <div>
                   <h1 class="text-xl font-bold">{{__('Today POS Orders')}} [{{__('Today')}}: {{request()->get('date') ?: \Carbon\Carbon::now()->toDateString()}}]</h1>
               </div>
               <div class="flex justify-start gap-4">
                   <x-tomato-admin-button warning :href="route('admin.pos.orders.index') . '?date=' . (request()->get('date') ? \Carbon\Carbon::parse(request()->get('date')) : \Carbon\Carbon::now())->addDays(-1)->toDateString()">{{__('<-')}}</x-tomato-admin-button>
                   <x-tomato-admin-button danger :href="route('admin.pos.orders.index') . '?date=' . (request()->get('date') ? \Carbon\Carbon::parse(request()->get('date')) : \Carbon\Carbon::now())->addDays(1)->toDateString()">{{__('->')}}</x-tomato-admin-button>
               </div>
           </div>
           <div class="my-4 mx-4 grid grid-cols-2 gap-4">
               <div class="bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm rounded-xl w-full text-center flex flex-col justify-center p-4 border">
                   <i class="bx bx-rocket bx-md"></i>
                   <h1 class="text-xl font-bold">{{$table->query->count()}}</h1>
                   <h1 class="text-md">{{__('Today Orders')}}</h1>
               </div>
               <div class="bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm rounded-xl w-full text-center flex flex-col justify-center p-4 border">
                   <i class="bx bx-money bx-md"></i>
                   <h1 class="text-xl font-bold">{!! dollar($table->query->sum('total')) !!}</h1>
                   <h1 class="text-md">{{__('Today Total Money')}}</h1>
               </div>
           </div>
       </div>

       <div class="my-4 mx-4">
           <x-splade-table striped :for="$table">
               <x-splade-cell account.name>
                   <x-tomato-admin-row table type="badge" href="{{route('admin.accounts.show', $item->account?->id)}}" :value="$item->account?->name" />
               </x-splade-cell>
               <x-splade-cell created_at>
                   <x-tomato-admin-row table type="text" :value="$item->created_at->diffForHumans()" />
               </x-splade-cell>
               <x-splade-cell phone>
                   <x-tomato-admin-row table type="tel" :value="$item->phone" />
               </x-splade-cell>
               <x-splade-cell total>
                   {!! dollar($item->total) !!}
               </x-splade-cell>
               <x-splade-cell actions>
                   <div class="flex justify-start gap-2">
                       <a href="{{route('admin.pos.orders.show', $item->id)}}" target="_blank" class="px-2 text-primary-500">
                           <x-heroicon-s-printer class="h-6 w-6"/>
                       </a>
                       <x-tomato-admin-button success type="icon" :href="route('admin.orders.show', $item->id)">
                           <x-heroicon-s-eye class="h-6 w-6"/>
                       </x-tomato-admin-button>
                   </div>
               </x-splade-cell>
           </x-splade-table>
       </div>
   </div>


@endsection

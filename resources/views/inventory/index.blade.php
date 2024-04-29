@extends('tomato-pos::layouts.master')

@section('content')
    <div class="h-full w-full">
        <div class="flex flex-col justify-center md:flex-row md:justify-between gap-4 my-4 mx-4">
            <div>
                <h1 class="text-xl font-bold">{{__('Inventory Requests')}} [{{__('Today')}}: {{request()->get('date') ?: \Carbon\Carbon::now()->toDateString()}}]</h1>
            </div>
            <div class="flex justify-start gap-4">
                <x-tomato-admin-button warning :href="route('admin.pos.inventory') . '?date=' . (request()->get('date') ? \Carbon\Carbon::parse(request()->get('date')) : \Carbon\Carbon::now())->addDays(-1)->toDateString()">
                    <i class='bx bx-chevron-right'></i>
                </x-tomato-admin-button>
                <x-tomato-admin-button danger :href="route('admin.pos.inventory') . '?date=' . (request()->get('date') ? \Carbon\Carbon::parse(request()->get('date')) : \Carbon\Carbon::now())->addDays(1)->toDateString()">
                    <i class='bx bx-chevron-left'></i>
                </x-tomato-admin-button>
                <x-tomato-admin-button modal :href="route('admin.pos.inventory.create')">{{__('New Request')}}</x-tomato-admin-button>
            </div>
        </div>
        <div class="my-4 mx-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-zinc-800 dark:border dark:border-zinc-700-zinc-700 shadow-sm rounded-xl w-full text-center flex flex-col justify-center p-4 border dark:border-zinc-700">
                <i class="bx bxs-truck bx-md"></i>
                <h1 class="text-xl font-bold">{{$table->query->count()}}</h1>
                <h1 class="text-md">{{__('Today Requests')}}</h1>
            </div>
            <div class="bg-white dark:bg-zinc-800 dark:border dark:border-zinc-700-zinc-700 shadow-sm rounded-xl w-full text-center flex flex-col justify-center p-4 border dark:border-zinc-700">
                <i class="bx bx-money bx-md"></i>
                <h1 class="text-xl font-bold">{!! dollar($table->query->sum('total')) !!}</h1>
                <h1 class="text-md">{{__('Today Total Money')}}</h1>
            </div>
        </div>

        <div class="my-4 mx-4">
            <x-splade-table striped :for="$table">
                <x-slot:actions>
                    <x-tomato-admin-table-action modal :href="route('admin.inventories.report')" secondary icon="bx bx-chart">
                        {{__('Product Inventory Report')}}
                    </x-tomato-admin-table-action>
                    <x-tomato-admin-table-action modal :href="route('admin.inventories.barcodes')" secondary icon="bx bx-barcode">
                        {{__('Print Product Barcodes')}}
                    </x-tomato-admin-table-action>
                </x-slot:actions>
                <x-splade-cell items>
                    <table class="border dark:border-zinc-700 min-w-full divide-y divide-zinc-200 dark:divide-zinc-600 bg-white dark:bg-zinc-700">
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-600 bg-white dark:bg-zinc-800">
                        @foreach($item->inventoryItems as $invItem)
                            <tr class="hover:bg-zinc-100 dark:hover:bg-zinc-600">
                                <td class="border dark:border-zinc-700 p-2">
                                    <div>{{$invItem->item}}</div>
                                    <div class="text-zinc-400 flex justify-start gap-2">
                                        @foreach($invItem->options ?? [] as $option)
                                            <div>
                                                {{ str($option)->upper() }}
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="border dark:border-zinc-700 p-2 font-bold">{{$invItem->qty}}</td>
                                @if($item->status !== 'canceled')
                                    <td class="border dark:border-zinc-700 p-2">
                                        @if(!$invItem->is_activated)
                                            <x-tomato-admin-tooltip text="{{__('Item Pending')}}">
                                                <i class="bx bx-x text-danger-500"></i>
                                            </x-tomato-admin-tooltip>
                                        @else
                                            <x-tomato-admin-tooltip text="{{__('Item Approved')}}">
                                                <i class="bx bx-check text-success-500"></i>
                                            </x-tomato-admin-tooltip>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </x-splade-cell>
                <x-splade-cell is_activated>
                    <x-tomato-admin-row table type="bool" :value="$item->is_activated" />
                </x-splade-cell>
                <x-splade-cell order.uuid>
                    <x-tomato-admin-row table type="badge" href="{{$item->order?->id ? route('admin.orders.show', $item->order?->id) : null}}" :value="$item->order?->uuid" />
                </x-splade-cell>
                <x-splade-cell total>
                    <x-tomato-admin-row table  :value="dollar($item->total)" />
                </x-splade-cell>
                <x-splade-cell actions>
                    <div class="flex justify-start gap-2">
                        <x-tomato-admin-button success type="icon" :href="route('admin.inventories.show', $item->id)">
                            <x-heroicon-s-eye class="h-6 w-6"/>
                        </x-tomato-admin-button>
                    </div>
                </x-splade-cell>
            </x-splade-table>
        </div>
    </div>


@endsection

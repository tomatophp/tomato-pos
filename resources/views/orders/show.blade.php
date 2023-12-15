<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <title>{{__('Order')}}: {{$order->uuid}}</title>

        <style>
            body, html {
                margin: 0 !important;
                padding: 0 !important;
            }
            table {
                border-collapse: collapse;
            }

            table, th, td {
                border: 1px solid #000000;
                padding: 5px;
            }
        </style>
    </head>
    <body style="text-align: center" onload="window.print()">
        <div dir="rtl"  style="margin-left: auto; margin-right:auto; display:block">
        <div>
            <img style="width: 100px" src="{{setting('site_logo')}}"></div><br>
        <h3 style="border: 1px solid #000000; text-align: center; padding: 5px;">{{__('Order')}} {{$order->uuid}}</h3>
        <br>
        <p style="margin-top: -15px;">{{__('Printed At')}}: {{\Carbon\Carbon::now()->format('d/m/Y g:i A')}}</p>
        <p style="margin-top: -15px;">{{__('Cashier')}}: {{ \TomatoPHP\TomatoPos\Models\PosSetting::where('key', 'cashier_name')->where('user_id', auth('web')->user()->id)->first()?->value }}
        </p>
        <table border="0" style="width: 100%">
            <tbody>
            @if($order->name || $order->phone)
                <tr>
                    <th>{{__('Bill To')}}</th>
                    <td>
                        @if($order->name)<span>{{$order->name}}</span>@endif
                        @if($order->phone)<br><span>{{$order->phone}}</span>@endif
                        @if($order->address)<br><span>{{$order->address}}</span>@endif
                        @if($order->city)<br><span>{{$order->city?->name}}</span>@endif
                        @if($order->area)<br><span>{{$order->area?->name}}</span>@endif
                    </td>
                </tr>
            @endif
            </tbody>
        </table><br>
        <table style="width: 100%">
            <thead>
            <tr>
                <th>{{__('Item')}}</th>
                <th>{{__('Qty')}}</th>
                <th>{{__('Total')}}</th>

            </tr>
            </thead>
            <tbody>
            @php $Count = 1 @endphp
            @foreach($order->ordersItems ?? [] as $item)
                <tr>
                    <td>
                        <b>{{$item->product->name}} - [{{$item->product->sku}}]
                            @if($item->options)
                                @php $counter = 0; @endphp
                                @foreach($item->options as $op)
                                    {{str($op)->title}}
                                    @if($counter+1 != sizeof($item->options))
                                        {{' - '}}
                                    @endif
                                    @php $counter++; @endphp
                                @endforeach
                            @endif
                        </b>
                    </td>
                    <td>
                        {{$item->qty}}
                    </td>
                    <td>
                        {!! dollar($item->total) !!}
                    </td>
                </tr>
                @php $Count++ @endphp
            @endforeach
            </tbody>
        </table><br>
        <table border="0" style="width: 100%">
            <tbody>
            <tr>
                <th>{{__('Sub Total')}}</th>
                <td>{!! dollar(($order->total + $order->discount) - ($order->vat)) !!}</td>
            </tr>
            @if($order->shipping)
                <tr>
                    <th>{{__('Shipping')}}</th>
                    <td>{!! dollar($order->shipping) !!}</td>
                </tr>
            @endif
            @if($order->vat)
                <tr>
                    <th>{{__('Vat')}}</th>
                    <td>{!! dollar($order->vat) !!}</td>
                </tr>
            @endif
            @if($order->discount)
                <tr>
                    <th>{{__('Discount')}}</th>
                    <td>
                        {!! dollar($order->discount) !!}
                    </td>
                </tr>
            @endif
            <tr>
                <th>{{__('Total')}}</th>
                <td>
                    <b>
                        {!! dollar($order->total) !!}
                    </b>
                </td>
            </tr>

            @if($order->notes)
                <tr>
                    <th>{{__('g.print.notes')}}</th>
                    <td>{{$order->notes}}</td>
                </tr>
            @endif

            </tbody>
        </table>
            <br>
            @php $branches = \TomatoPHP\TomatoBranches\Models\Branch::where('company_id', $order->branch->company_id)->get() @endphp
        @foreach($branches as $key=>$branch)
            <div>{{$branch->name}}</div>
            <div>{{$branch->address}}</div>
            <div><b>{{$branch->phone}}</b></div>
            @if($key != $branches->count()-1)
                <hr>
            @endif
        @endforeach
        <hr style="width: 100%">
        <img src="data:image/png;base64,{{\DNS1D::getBarcodePNG((string)$order->uuid, 'C128',1,44,array(1,1,1), true)}}" alt="barcode"  />
            <br />
            <h4>
                {{ \TomatoPHP\TomatoPos\Models\PosSetting::where('key', 'branch_note')->where('user_id', auth('web')->user()->id)->first()?->value }}
            </h4>

    </div>
    </body>
</html>

<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Menus</title>
    <style>
        body {
            font-family: 'Arial Black';
            font-size: 18px;
        }
        h5 {
            text-transform: uppercase;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        tr {
            border-bottom: 1px solid #ccc;
        }
        th, td {
            text-align: left;
            padding: 4px;
        }
        @page { margin: 15px; }

        .page-break {
            page-break-after: always;
        }

        .order-container {
            display:inline-block;
            width: 50%;
        }
    </style>
</head>
<body>


@foreach ($orders as $user_id => $user_orders)

    @if(!empty($user_orders))

        @php
            $orders_grouped_by_type = $user_orders->groupBy('order_type_id');
        @endphp

        @foreach($orders_grouped_by_type as $order_type_id => $orders_in_type)
            @php
                $order_type = $order_types->where('id', $order_type_id)->first();
            @endphp

            <div class="page" style="page-break-after: always">

                @php
                    $date_start = $week->startOfWeek()->format('d F');
                    $date_end = $week->endOfWeek()->format('d F');
                    //dd($orders_in_type);
                    $order_by_day = $orders_in_type->groupBy('week_day');
                @endphp

                <table>
                    <tr style="background: #01484d;">
                        <td>
                            <div style="padding: 15px;">
                                <img src="{{public_path('/russley-village-wh.png')}}" alt="" style="max-width: 190px">
                            </div>
                        </td>
                        <td>
                            <div style="color: white; padding: 15px;text-align: right;">
                                <h2 style="margin-top: 0;">{{$order_type->name}} Menu</h2>
                                <h4 style="margin-bottom: 5px; margin-top: 0;">{{ $user_orders[0]->user->name }}</h4>
                                <div style="font-size: .8em">
                                    Week {{$date_start}} - {{$date_end}} | Room {{ $user_orders[0]->user->room_number }}
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>

                <div style="clear: both;"></div>

                <div style="">
                    @php
                        $i = 1;
                        $closed = true;
                    @endphp
                    @foreach($order_by_day as $day => $day_orders)
                        @if($i % 2 !== 0)
                        @php $closed = false;  @endphp
                        <div style="display: inline-block; width: 100%">
                        @endif

                            <div class="day" style="width: 50%; float: left;">
                                <div style="padding: 5px 15px">
                                    <h3 style="margin-bottom: 5px">{{$day}}</h3>
                                    @foreach($day_orders as $key => $order)
                                        <strong>{{ $order->menu->menuType->name }}</strong><br>
                                        {{ $order->menu->name }}
                                        {{ $order->menuVariation->details ?? '' }}
                                        <br>
                                    @endforeach
                                </div>
                            </div>

                        @if($i % 2 === 0)
                        @php $closed= true; @endphp
                        </div>
                        <div style="clear: both"></div>
                        @endif
                        @php
                            $i++;
                        @endphp
                    @endforeach

                    @if (!$closed)
                        </div>
                    @endif

                </div>

            </div>

        @endforeach

    @endif
@endforeach

</body>
</html>




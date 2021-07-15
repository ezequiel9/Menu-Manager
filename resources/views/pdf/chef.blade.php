<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Menus</title>
    <style>
        body {
            font-family: 'Helvetica';
            font-size: 12px;
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

        tr:nth-child(even) {background: #f1f1f1}
        tr:nth-child(odd) {background: #FFF}
    </style>
</head>
<body>

@php
    $date_start = $week->startOfWeek()->format('d F');
    $date_end = $week->endOfWeek()->format('d F');
@endphp

    <h5>Week {{$date_start}} - {{$date_end}}</h5>
    <table class="table table-bordered">
        <tr>
            <th>Room</th>
            <th>Resident</th>
            <th>Size</th>
            <th>Soup</th>
            <th>Main</th>
            <th>Dessert</th>
        </tr>

        @php
            //dd($orders);
        @endphp


        @foreach ($orders as $week_day => $day_orders)
            @php
                $orders_grouped_by_type = $day_orders->groupBy('order_type_id');
            @endphp
            @foreach($orders_grouped_by_type as $order_type_id => $orders_in_type)
                @php
                    $order_type = $order_types->where('id', $order_type_id)->first();
                    $users_orders = $orders_in_type->groupBy('user.floor_id');
                @endphp

                <tr>
                    <td colspan="6" style="text-align: center; font-weight: bold; text-transform: uppercase; background: rgba(0, 90, 255, .36);">
                        {{$week_day}} {{$order_type->name}}
                    </td>
                </tr>

                @foreach($users_orders as $floor_id => $user_orders)

                    @php
                        $floor = $floors->where('id', $floor_id)->first();
                        $users_orders_grouped_by_floor = $user_orders->groupBy('user_id');
                    @endphp

                    <tr>
                        <td colspan="6" style="text-align: center; font-weight: bold; text-transform: uppercase; background: #f9f907">
                            {{$floor->name ?? 'No Floor'}}
                        </td>
                    </tr>

                    @foreach($users_orders_grouped_by_floor as $floor_id => $user_orders_grouped_by_floor)



                        @if($user_orders_grouped_by_floor->isNotEmpty())
                            @php
                                $unique_user_order = $user_orders_grouped_by_floor->first();
                                $side = '';
                                $main = '';
                                $dessert = '';
                                foreach ($user_orders_grouped_by_floor as $order) {
                                    $details = $order->menuVariation ? $order->menuVariation->details : '';
                                    if (in_array($order->menu->menuType->name, ['Soup', 'Drinks'])) {
                                        $side = $order->menu->name . ' ' . $details;
                                    }
                                    if ($order->menu->menuType->name == 'Main') {
                                        $main = $order->menu->name . ' ' . $details;
                                    }
                                    if ($order->menu->menuType->name == 'Dessert') {
                                        $dessert = $order->menu->name . ' ' . $details;
                                    }
                                }
                            @endphp

                            <tr style="border-bottom: 1px solid #ccc;">
                                <td>
                                    {{ $unique_user_order->user->room_number ?? '' }}
                                </td>
                                <td style="border-bottom: 1px solid #ccc;">
                                    {{ $unique_user_order->user->name }}
                                    @if($unique_user_order->user->diet)
                                        <span style="color: red; font-weight: bold">({{ $unique_user_order->user->diet }})</span>
                                    @endif
                                </td>
                                <td style="border-bottom: 1px solid #ccc;">{{ $unique_user_order->user->meal_size }}</td>
                                <td style="border-bottom: 1px solid #ccc;">
                                    {{$side}}
                                </td>
                                <td style="border-bottom: 1px solid #ccc;">
                                    {{$main}}
                                </td>
                                <td style="border-bottom: 1px solid #ccc;">
                                    {{$dessert}}
                                </td>
                            </tr>
                        @endif

                    @endforeach

                @endforeach

            @endforeach


            <tr style="page-break-after: always;">
                <td colspan="6">

                </td>
            </tr>

        @endforeach
    </table>

</body>
</html>




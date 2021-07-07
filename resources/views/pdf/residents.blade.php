<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Menus</title>
    <style>
        body {
            font-family: 'Helvetica';
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
    </style>
</head>
<body>


@foreach ($orders as $user_orders)

    <div class="page" style="page-break-after: always">


        @if(!empty($user_orders))

            @php
                $date_start = $week->startOfWeek()->format('d F');
                $date_end = $week->endOfWeek()->format('d F');

                $order_by_day = $user_orders->groupBy('week_day');
            @endphp

            <h1 style="text-align: center">{{ $user_orders[0]->user->name }}</h1>

            <h3 style="text-align: center; text-transform: uppercase;">Week {{$date_start}} - {{$date_end}} | Room {{ $user_orders[0]->user->room_number }}</h3>

            <div style="text-align: center;">
                @foreach($order_by_day as $day => $day_orders)
                    <h3>{{$day}}</h3>
                    @foreach($day_orders as $order)

                        <div class="order-container">
                            <strong>[{{ $order->orderType->name }}]</strong>
                            <strong>[{{ $order->menu->menuType->name }}]</strong>
                            {{ $order->menu->name }} {{ $order->menuVariation->details ?? '' }}
                        </div>

                    @endforeach
                @endforeach
            </div>
        @endif


    </div>


@endforeach

</body>
</html>




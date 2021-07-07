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
            <th>Size</th>
            <th>Day</th>
            <th>Menu</th>
        </tr>
        @foreach ($orders as $user_orders)

            @foreach($user_orders as $user_order)

                <tr style="border-bottom: 1px solid #ccc;">
                    <td style="border-bottom: 1px solid #ccc;">{{ $user_order->user->name }} {{ $user_order->user->room_number }}</td>
                    <td style="border-bottom: 1px solid #ccc;">{{ $user_order->user->meal_size }}</td>
                    <td style="border-bottom: 1px solid #ccc;">{{ $user_order->week_day }}</td>
                    <td style="border-bottom: 1px solid #ccc;">
                        <strong>[{{ $user_order->orderType->name }}]</strong>
                        <strong>[{{ $user_order->menu->menuType->name }}]</strong>
                        {{ $user_order->menu->name }} {{ $user_order->menuVariation->details ?? '' }}
                    </td>
                </tr>

            @endforeach


            <tr>
                <td colspan="4"></td>
            </tr>

        @endforeach
    </table>

</body>
</html>




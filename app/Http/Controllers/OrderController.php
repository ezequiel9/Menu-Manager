<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;

class OrderController extends Controller
{

    public function storeOrder(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|integer|exists:users,id',
            'week_number' => 'required|integer',
            'week_day' => 'required|string',
            'menu_id' => 'required|integer|exists:menus,id',
            'order_type_id' => 'required|integer|exists:order_types,id',
            'menu_variation_id' => 'nullable|integer|exists:menu_variations,id',
        ]);

        // insert
        Order::query()->create($request->all());

        // query all current user and week orders
        $orders = Order::query()
            ->where('user_id', $request->user_id)
            ->where('week_number', $request->week_number)
            ->with([
                'menu.menuType',
                'menuVariation',
                'orderType'
            ])
            ->get();

        return response()->json([
            'orders' => $orders
        ], 200);
    }

    public function getOrders(Request $request, $user_id, $week_number)
    {

        // query all current user and week orders
        $orders = Order::query()
            ->where('user_id', $user_id)
            ->where('week_number', $week_number)
            ->with([
                'menu.menuType',
                'menuVariation',
                'orderType'
            ])
            ->get();

        return response()->json([
            'orders' => $orders
        ], 200);
    }

    public function deleteOrder(Request $request, Order $order)
    {
        try {
            $order->delete();
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
        return response()->json(true, 200);
    }


    public function getOrderTypes(Request $request)
    {
        return response()->json(OrderType::all(), 200);
    }

    public function pdfChef(Request $request, $week_number)
    {
        if (!$week_number) {
            die('nah.. please add a week number');
        }
        $week_number = filter_var($week_number, FILTER_SANITIZE_NUMBER_INT);

        $orders = Order::query()
            ->where('week_number', $week_number)
            ->whereHas('user')
            ->with([
                'user',
                'orderType',
                'menu.menuType',
                'menuVariation',
            ])
            ->get()
            ->groupBy('week_day');

        $week = Carbon::now();
        $week->setISODate(date('Y'), $week_number);

        $order_types = OrderType::all();
        $floors = Floor::all();

        return view('pdf.chef', compact('orders', 'week', 'order_types', 'floors'));

        $pdf = PDF::loadView('pdf.chef', compact('orders', 'week', 'order_types', 'floors'));
        return $pdf->stream();
        return $pdf->download('menus-chef.pdf');
    }

    public function pdfResidents(Request $request, $week_number)
    {
        if (!$week_number) {
            die('nah.. please add a week number');
        }
        $week_number = filter_var($week_number, FILTER_SANITIZE_NUMBER_INT);

        $orders = Order::query()
            ->where('week_number', $week_number)
            ->with([
                'user',
                'orderType',
                'menu.menuType',
                'menuVariation',
            ])
            ->get()
            ->groupBy('user_id');

        $week = Carbon::now();
        $week->setISODate(date('Y'), $week_number);

        $order_types = OrderType::all();
        $floors = Floor::all();

        //return view('pdf.residents', compact('orders', 'week', 'order_types', 'floors'));


        $pdf = PDF::loadView('pdf.residents', compact('orders', 'week', 'order_types', 'floors'));
        return $pdf->stream();
        return $pdf->download('menus-residents.pdf');
    }

}

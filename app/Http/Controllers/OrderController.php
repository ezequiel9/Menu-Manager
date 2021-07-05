<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function storeOrder(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|integer|exists:users,id',
            'week_number' => 'required|integer',
            'week_day' => 'required|string',
            'menu_id' => 'required|integer|exists:menus,id',
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
                'menuVariation'
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
                'menuVariation'
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

}

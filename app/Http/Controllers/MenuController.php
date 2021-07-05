<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuType;
use App\Models\MenuVariation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    public function search(Request $request)
    {
        $search_query = $request->search_query;

        $menus = Menu::query()
            ->where('name', 'LIKE', "%{$search_query}%")
            ->orWhereHas('menuType', function ($query) use ($search_query) {
                $query->where('name', 'LIKE', "%{$search_query}%");
            })
            ->orWhereHas('menuVariations', function ($query) use ($search_query) {
                $query->where('details', 'LIKE', "%{$search_query}%");
            })
            ->with('menuType', 'menuVariations')
            ->get();

        return response()->json([
            'menus' => $menus
        ]);
    }

    public function deleteMenu(Request $request, Menu $menu)
    {
        try {
            $menu->delete();
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
        return response()->json(true, 200);
    }

    public function deleteVariation(Request $request, Menu $menu, MenuVariation $menu_variation)
    {
        try {
            $menu_variation->delete();
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
        return response()->json(true, 200);
    }

    public function addVariation(Request $request)
    {
        $this->validate($request, [
            'menu_id' => 'required|integer|exists:menus,id',
            'details' => 'required|string'
        ]);
        $menu_variation = MenuVariation::query()->create($request->all());
        return response()->json($menu_variation, 200);
    }

    public function addMenu(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'menu_type_id' => 'required|integer|exists:menu_types,id'
        ]);
        $menu = Menu::query()->create($request->all());
        return response()->json($menu, 200);
    }

    public function getMenuTypes(Request $request)
    {
        return response()->json(MenuType::all(), 200);
    }


    public function getDates()
    {
        $carbon = Carbon::now()->startOfWeek()->subWeek();
        $dates = [];
        foreach (range(0,6) as $item) {
            $start = $carbon->format('d/m/Y');
            $end = $carbon->copy()->endOfWeek()->format('d/m/Y');
            $dates[] = [
                'week_number' => $carbon->week,
                'week_days' => [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                    'Sunday',
                ],
                'label' => "$start - $end"
            ];
            $carbon->addWeek();
        }

        return response()->json($dates, 200);
    }

}

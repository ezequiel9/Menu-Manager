<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuType;
use App\Models\MenuVariation;
use App\Models\OrderType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class LiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        MenuType::query()->truncate();
        OrderType::query()->truncate();
        Schema::enableForeignKeyConstraints();

        OrderType::query()->create(['name' => 'Lunch']);
        OrderType::query()->create(['name' => 'Dinner']);
        OrderType::query()->create(['name' => 'BreakFast']);
        OrderType::query()->create(['name' => 'Afternoon Tea']);
        OrderType::query()->create(['name' => 'Other']);

        MenuType::query()->create(['name' => 'Main']);
        MenuType::query()->create(['name' => 'Dessert']);
        MenuType::query()->create(['name' => 'Sides']);
        MenuType::query()->create(['name' => 'Other']);
    }
}

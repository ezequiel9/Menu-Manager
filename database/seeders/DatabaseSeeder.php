<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuType;
use App\Models\MenuVariation;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public $faker;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = Factory::create();

        Schema::disableForeignKeyConstraints();
        MenuVariation::query()->truncate();
        Menu::query()->truncate();
        MenuType::query()->truncate();
        User::query()->truncate();
        Schema::enableForeignKeyConstraints();

        MenuType::query()->create(['name' => 'Main']);
        MenuType::query()->create(['name' => 'Sides']);
        MenuType::query()->create(['name' => 'Dessert']);
        MenuType::query()->create(['name' => 'Breakfast']);
        MenuType::query()->create(['name' => 'Afternoon Tea']);

        foreach (range(0,30) as $item) {
            $menu = Menu::query()
                ->create([
                    'name' => $this->faker->text(20),
                    'menu_type_id' => MenuType::query()->inRandomOrder()->first()->id
                ]);

            foreach (range(0,rand(0,3)) as $i) {
                MenuVariation::query()->create([
                    'details' => $this->faker->text(30),
                    'menu_id' => $menu->id
                ]);
            }
        }


        $sizes = [
            'Small',
            'Medium',
            'Large',
        ];


        User::query()
            ->create([
                'name' => 'Ezequiel F',
                'email' => 'ezequielf057@gmail.com',
                'password' => Hash::make('123123'),
                'room_number' => rand(1,48),
                'meal_size' => $sizes[array_rand($sizes)],
                'phone' => $this->faker->phoneNumber,
            ]);
        foreach (range(0,30) as $item) {
            User::query()
                ->create([
                    'name' => $this->faker->name,
                    'email' => $this->faker->email,
                    'room_number' => rand(1,48),
                    'meal_size' => $sizes[array_rand($sizes)],
                    'phone' => $this->faker->phoneNumber,
                ]);
        }



    }
}

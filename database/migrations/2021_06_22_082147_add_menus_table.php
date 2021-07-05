<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('menu_type_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('menu_type_id')->references('id')->on('menu_types');
        });
        Schema::create('menu_variations', function (Blueprint $table) {
            $table->id();
            $table->string('details');
            $table->unsignedBigInteger('menu_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');;
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('menu_variation_id')->nullable();
            $table->string('week_number');
            $table->string('week_day');
            $table->string('note')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('menu_id')->references('id')->on('menus');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('menu_variation_id')->references('id')->on('menu_variations')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('menu_variations');
        Schema::dropIfExists('menu_types');
    }
}

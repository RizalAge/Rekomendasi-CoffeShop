<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('coffee_shops', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description');
        $table->string('address');
        $table->string('latitude');
        $table->string('longitude');
        $table->string('phone')->nullable();
        $table->string('image')->nullable();
        $table->enum('price_range', ['murah', 'sedang', 'mahal']);
        $table->integer('min_price');
        $table->integer('max_price');
        
        // Fasilitas (boolean)
        $table->boolean('wifi')->default(false);
        $table->boolean('power_outlet')->default(false);
        $table->boolean('smoking_area')->default(false);
        $table->boolean('outdoor_seating')->default(false);
        $table->boolean('meeting_room')->default(false);
        $table->boolean('parking')->default(false);
        
        // Suasana (enum atau boolean)
        $table->boolean('suitable_for_work')->default(false);
        $table->boolean('suitable_for_chat')->default(false);
        $table->boolean('quiet_atmosphere')->default(false);
        $table->boolean('romantic')->default(false);
        
        // Menu
        $table->boolean('manual_brew')->default(false);
        $table->boolean('non_coffee')->default(false);
        $table->boolean('vegan_options')->default(false);
        
        $table->time('open_time');
        $table->time('close_time');
        $table->boolean('is_24_hours')->default(false);
        $table->float('rating_avg', 2, 1)->default(0);
        $table->integer('total_reviews')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coffee_shops');
    }
};

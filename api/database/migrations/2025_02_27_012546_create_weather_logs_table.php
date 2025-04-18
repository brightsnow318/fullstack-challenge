<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weather_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned(); // Foreign key to users
            $table->string('main', 30); // weather main status (Rain, Snow, Clouds etc.)
            $table->string('description', 100); // Weather condition within the group
            $table->string('icon', 10); // Weather icon
            $table->decimal('temp', 5, 2)->unsigned(); // temperature in kelvin K
            $table->integer('pressure')->unsigned(); // pressure in hPa
            $table->decimal('humidity', 5, 2)->unsigned(); // humidity in %
            $table->integer('visibility')->unsingned(); // visibility in meter
            $table->integer('wind_speed')->unsingned(); // wind speed in meter/sec
            $table->integer('wind_deg')->unsingned(); // wind direction in degrees
            $table->integer('cloudiness')->unsingned(); // clouds all
            $table->dateTime('datetime')->nullable(); // Time of data calculation, unix, UTC
            $table->string('country', 5); // Country code (GB, JP etc.)
            $table->string('city', 50); // City name eg. London
            $table->dateTime('sunrise')->nullable(); // Time of sunrise, unix, UTC
            $table->dateTime('sunset')->nullable(); // Time of sunset, unix, UTC
            $table->timestamps();

            // Setting up the Foreign Key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weather_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('weather_logs');
    }
};

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
        Schema::create('phone_types', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->string('label')->nullable()->comment('Label');
            $table->string('name')->nullable()->comment('Name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('phone_types');
    }
};

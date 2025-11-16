<?php

use CleaniqueCoders\Profile\Models\PhoneType;
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
        Schema::create('phones', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->foreignIdFor(PhoneType::class)->default(PhoneType::HOME);
            $table->unsignedInteger('phoneable_id');
            $table->string('phoneable_type');
            $table->string('number')->nullable();
            $table->boolean('is_default')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('phones');
    }
};

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
        Schema::create('social_media', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->unsignedInteger('socialable_id');
            $table->string('socialable_type');
            $table->string('platform'); // twitter, linkedin, facebook, instagram, github, etc.
            $table->string('username')->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_primary')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['socialable_id', 'socialable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('social_media');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->increments('id');
            $table->hashslug();
            $table->addForeign('phone_type_id', 'phone_types');
            $table->unsignedInteger('phoneable_id');
            $table->string('phoneable_type');
            $table->string('phone_number')->nullable();
            $table->standardTime();

            $table->referenceOn('phone_type_id', 'phone_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phones');
    }
}

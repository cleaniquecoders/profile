<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->hashslug();
            $table->addForeign('country_id', 'countries');
            $table->unsignedInteger('addressable_id');
            $table->string('addressable_type');
            $table->text('primary');
            $table->text('secondary');
            $table->string('postcode');
            $table->string('city');
            $table->string('state');
            $table->standardTime();

            $table->referenceOn('country_id', 'countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMushroomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mushrooms', function (Blueprint $table) {
            $table->increments('id');

            $table->string('capShape');
            $table->string('capSurface');
            $table->string('capColor');

            $table->string('bruises');
            $table->string('odor');

            $table->string('gillAttachment');
            $table->string('gillSpacing');
            $table->string('gillSize');
            $table->string('gillColor');

            $table->string('stalkShape');
            $table->string('stalkRoot');
            $table->string('stalkSurfaceAboveRing');
            $table->string('stalkSurfaceBelowRing');
            $table->string('stalkColorAboveRing');
            $table->string('stalkColorBelowRing');

            $table->string('veilType');
            $table->string('veilColor');
            $table->string('ringNumber');
            $table->string('ringType');
            $table->string('sporePrintColor');
            $table->string('population');
            $table->string('habitat');
            $table->string('result');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mushrooms');
    }
}

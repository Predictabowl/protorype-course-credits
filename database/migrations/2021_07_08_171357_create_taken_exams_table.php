<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTakenExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taken_exams', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedTinyInteger("cfu");
            $table->foreignId("ssd_id"); //not constrained, an user may insert a ssd not present in the DB. TO BE TESTED
            $table->foreignId("front_id")->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('taken_exams');
    }
}

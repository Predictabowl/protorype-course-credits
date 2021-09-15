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
            $table->foreignId("ssd_id")->constrained()->cascadeOnDelete();
            $table->foreignId("front_id")->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger("grade");
            $table->unsignedTinyInteger("courseYear")->nullable();
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

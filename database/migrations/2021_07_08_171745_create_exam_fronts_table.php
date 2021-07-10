<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamFrontsTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * This is a linking table for many to many
     * between exam and front tables
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_front', function (Blueprint $table) {
            $table->id();
            $table->foreignId("front_id")->constrained()->cascadeOnDelete();
            $table->foreignId("exam_id")->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('exam_front');
    }
}

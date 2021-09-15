<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger("max_exams");
            $table->foreignId("course_id")->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unsignedTinyInteger("cfu"); //cfu value for every exam in this block
            $table->unsignedTinyInteger("courseYear")->nullable(); //course year for every exam in this block
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_blocks');
    }
}

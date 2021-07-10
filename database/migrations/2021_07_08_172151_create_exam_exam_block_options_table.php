<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamExamBlockOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * This is a linking table for many to many
     * between exam and exam_block_option tables
     * @return void
     */
    public function up()
    {
        Schema::create('exam_exam_block_option', function (Blueprint $table) {
            $table->id();
            $table->foreignId("exam_block_option_id")->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('exam_exam_block_option');
    }
}

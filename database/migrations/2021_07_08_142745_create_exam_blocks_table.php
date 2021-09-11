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
            //$table->unsignedTinyInteger("cfu"); //unused
            //
            // Every exams of a block should have the same CFU, so it makes
            // sense to have the CFU in the block entity instead of the exam.
            // The reasons I won't do are: 
            // - Exams are thematically independend, and exam can exist even when
            //  is not assigned to a block. If an exam is not assigned then it 
            //  will miss its cfu value.
            // - It won't remove the possibility of inconsistency, because then 
            //  the same exam could be assigned to different blocks with different 
            //  CFU values
            // 
            // As far as I know each exam could be unique (code wise) to a
            // single degree course, even when the same exam is teached in two
            // different courses normally they have different names and
            // especially different codes. If that's the case the it could make
            // more sense to assign the CFU directly to the block entity.
            // 
            
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

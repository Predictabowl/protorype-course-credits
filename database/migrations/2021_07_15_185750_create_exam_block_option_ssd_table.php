<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * This table holds the compatibility options between an exam and other SSDs,
 * which will be used as secondary search for credit integration, after the
 * main SSD.
 */

class CreateExamBlockOptionSsdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_block_option_ssd', function (Blueprint $table) {
            $table->id();
            $table->foreignId("ssd_id")->constrained()->cascadeOnDelete();
            $table->foreignId("exam_block_option_id")->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('exam_block_option_ssd');
    }
}

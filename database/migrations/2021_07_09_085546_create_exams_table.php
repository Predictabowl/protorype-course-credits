<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId("ssd_id")->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId("exam_block_id")->constrained()->cascadeOnDelete();
            $table->string("code")->unique()->nullable(); //unused right now, but left for future applications
            $table->boolean("free_choice")->default(false);
            $table->string("name");
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
        Schema::dropIfExists('exams');
    }
}

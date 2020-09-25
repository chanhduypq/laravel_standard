<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->integer('created_id')->nullable();
            $table->string('photo', 100)->nullable();
            $table->jsonb('information')->nullable()->comment('Thông tin khác');
            $table->jsonb('interest')->nullable()->comment('Sở thích');
            $table->unsignedBigInteger('classes_id');
            $table->foreign('classes_id')->references('id')->on('classes');
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
        Schema::dropIfExists('students');
    }
}

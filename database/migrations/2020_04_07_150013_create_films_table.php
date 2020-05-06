<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('films', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->date('release_date')->nullable();
            $table->string('director')->nullable();


            $table->unsignedBigInteger('picture_id');
            $table->foreign('picture_id')->references('id')->on('pictures');

            $table->unsignedInteger('status');
            $table->foreign('status')->references('id')->on('status');


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
        Schema::dropIfExists('films');
    }
}

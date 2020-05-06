<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePivotCategoryFilmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_category_film', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('film_id');
            $table->foreign('film_id')->references('id')->on('films');

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('category_movies');

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
        Schema::dropIfExists('pivot_category_film');
    }
}

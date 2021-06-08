<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale', 5);
            $table->string('group');
            $table->string('title');
            $table->string('code');
            $table->string('icon')->nullable();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->string('url')->nullable();
            $table->bigInteger('rank')->nullable();
            $table->tinyInteger('visibility')->default(0);
            $table->timestamps();

            $table->unique(['code', 'locale']);

            $table->foreign('parent_id')->references('id')->on('links')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('links');
    }
}

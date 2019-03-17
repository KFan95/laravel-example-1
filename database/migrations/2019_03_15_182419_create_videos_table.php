<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('videos');
        Schema::create('videos', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id', false, true);
            $table->string('name', 255);
            $table->string('src', 512)->nullable();
            $table->enum('status', [
                'upload', 'process', 'processed', 'error'
            ])->default('upload');
            $table->string('preview', 512)->nullable();
            $table->mediumInteger('duration', false, true)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->index(['user_id', 'status', 'created_at']); // filter with status
            $table->index(['user_id', 'created_at']); // filter without status
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}

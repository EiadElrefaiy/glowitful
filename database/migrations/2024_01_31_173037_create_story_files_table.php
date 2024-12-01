<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoryFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('story_files', function (Blueprint $table) {
            $table->id();
            $table->integer('serviceprovider_id')->unsigned()->index();
            $table->foreign('serviceprovider_id')->references('id')->on('service_providers')->onDelete('cascade');
            $table->string('file');
            $table->integer('ended')->default(0);
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
        Schema::dropIfExists('story_files');
    }
}

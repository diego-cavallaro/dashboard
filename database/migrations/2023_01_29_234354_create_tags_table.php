<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    //protected $connection = 'mysql2';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::connection('mysql2')->create('tags', function (Blueprint $table) {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
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
        //Schema::connection('mysql2')->dropIfExists('tags');
        Schema::dropIfExists('tags');
    }
}

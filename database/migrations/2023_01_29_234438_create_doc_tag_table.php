<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocTagTable extends Migration
{
    //protected $connection = 'mysql2';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::connection('mysql2')->create('doc_tag', function (Blueprint $table) {
        Schema::create('doc_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('doc_id');
            $table->unsignedInteger('tag_id');
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
        //Schema::connection('mysql2')->dropIfExists('doc_tag');
        Schema::dropIfExists('doc_tag');
    }
}

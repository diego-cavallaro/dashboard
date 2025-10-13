<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocsTable extends Migration
{
        //protected $connection = 'mysql2';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::connection('mysql2')->create('docs', function (Blueprint $table) {
        Schema::create('docs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url');
            $table->unsignedInteger('user_id');
            $table->mediumText('exerpt')->nullable();
            $table->longtext('content')->nullable();
            $table->boolean('public')->default = 0;
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('area_id')->nullable();
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
        //Schema::connection('mysql2')->dropIfExists('docs');
        Schema::dropIfExists('docs');
    }
}

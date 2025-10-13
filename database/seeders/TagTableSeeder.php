<?php

namespace Database\Seeders;
use App\Models\Tag;

use Illuminate\Database\Seeder;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::truncate();

        $tag = new tag;
        $tag->name = 'Procedimiento';
        $tag->save();

        $tag = new tag;
        $tag->name = 'Instrucctivo';
        $tag->save();

        $tag = new tag;
        $tag->name = 'Notificación';
        $tag->save();

    }
}

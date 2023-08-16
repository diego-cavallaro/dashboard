<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doc;

class DocTableSeeder extends Seeder
{
    public function run()
    {
        Doc::truncate();
        
        $doc = new Doc;

        $doc->title = "Documento Seeder Nro01";
        $doc->url = "Documento_Seeder_Nro01";
        $doc->user_id = 1;
        $doc->exerpt = "Extracto o resumen de lo que trata el documento Seeder Nro01";
        $doc->content = "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).";
        $doc->public = 1;
        $doc->published_at = NOW();
        $doc->area_id = 1;
        $doc->save();

    
        $doc = new Doc;

        $doc->title = "Documento Seeder Nro02";
        $doc->url = "Documento_Seeder_Nro02";
        $doc->user_id = 1;
        $doc->exerpt = "Extracto o resumen de lo que trata el documento Seeder Nro02";
        $doc->content = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum";
        $doc->public = 0;
        $doc->published_at = NOW();
        $doc->area_id = 2;
        $doc->save();
    

        $doc = new Doc;

        $doc->title = "Documento Seeder Nro03";
        $doc->url = "Documento_Seeder_Nro03";
        $doc->user_id = 1;
        $doc->exerpt = "Extracto o resumen de lo que trata el documento Seeder Nro03";
        $doc->content = "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.";
        $doc->public = 1;
        $doc->published_at = NOW();
        $doc->area_id = 3;
        $doc->save();


        $doc = new Doc;

        $doc->title = "Documento Seeder Nro04";
        $doc->url = "Documento_Seeder_Nro04";
        $doc->user_id = 2;
        $doc->exerpt = "Extracto o resumen de lo que trata el documento Seeder Nro04";
        $doc->content = "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.";
        $doc->public = 1;
        $doc->published_at = NOW();
        $doc->area_id = 1;
        $doc->save();


        $doc = new Doc;

        $doc->title = "Documento Seeder Nro05";
        $doc->url = "Documento_Seeder_Nro05";
        $doc->user_id = 2;
        $doc->exerpt = "Extracto o resumen de lo que trata el documento Seeder Nro05";
        $doc->content = "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.";
        $doc->public = 1;
        $doc->published_at = NOW();
        $doc->area_id = 2;
        $doc->save();

    }
}
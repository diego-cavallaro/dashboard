<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Doc extends Model
{
    use HasFactory;
    protected $fillable = ['title','url','user_id','exerpt','content'];
    protected $guarded = []; // No fields are guarded
    protected $dates = ['published_at']; // Carbon instance

    public function GetRouteKeyName()
        {
            return 'url';
        }

    public function area()
        {
            return $this->belongsTo(Area::class); //cada doc pertenece a un area y solo uno
        }

    public function tags()
        {
            return $this->belongsToMany(Tag::class); //pertenece a muchos "etiquetas"
        }

    public function user()
        {
            return $this->belongsTo(User::class); //cada doc pertenece a un Autor y solo uno
        }

    public function scopePublic($query)
        {
            $query  ->whereNotNull('published_at')               //filtra que no tenga fecha null en publicado
                    ->where('published_at', '<=', carbon::now()) //filtra docs menores a la fecha actual
                    ->where('public', '=', 1)                    // filtra que el doc sea publico
                    ->latest('published_at');                    //ordena de mas nuevo a mas viejo
        }

    public function scopeAllowed($query)
        {
            if( auth()->user()->can('view', $this))
                {
                    $query  ->whereNotNull('published_at')
                            ->whereNotNull('exerpt')
                            ->whereNotNull('content')
                            ->whereNotNull('area_id');
                    return $query;
                }
            return $query->where('user_id', auth()->id());
        }
}

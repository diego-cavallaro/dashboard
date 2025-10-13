<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Doc;
    use App\Models\Area;
    use App\Models\User;
    use App\Models\Tag;
    use Carbon\Carbon;
    use Illuminate\Support\Str;

class DocsController extends Controller
{
    public function index()
        {
            $autor = User::all();
            $tags = Tag::all();
            $areas = Area::all();
            $docs = Doc::Public()->simplePaginate(3); //public function scopePublic($query) en Model
            return view ('docs.index', compact('docs','tags', 'areas', 'autor'));
        }

    public function list()
        {
            $docs = Doc::Allowed()->get();
/*                 $docs = Doc::where('published_at', '<=', carbon::now())
                            ->latest('published_at')                    
                            ->get(); */
        
                return view ('docs.list', compact('docs'));
        }
    
    public function edit(Doc $doc)
        {
            $this->authorize('update', $doc);
            $tags = Tag::all();
            $areas = Area::all();
            return view('docs.edit', compact('doc','tags', 'areas'));
        }
    
    public function store(Request $request)
        {
            $this->authorize('create', new Doc);
            $this->validate($request, 
                [
                    'title' => 'required'
                ]
            );

            $doc = Doc::create([
                'title' => $request->get('title'),
                'url'   => str::slug($request->get('title')),
                'user_id'=> auth()->user()->id
            ]);
            return redirect()->route('docs.edit', $doc);
        }

    public function update(Doc $doc, Request $request)
        {
            $this->authorize('update', $doc);
            $this->validate($request,
                [
                    //'title'     =>'required|min:10|max:80|unique:docs',
                    'exerpt'    =>'required|min:20|max:280',
                    'content'   =>'required|min:40',
                    'area'      =>'required',
                    'public'    =>'required',
                    'tags'      =>'required'
                ] );

            //return $request->get('content');

            //$doc->title         = $request->get('title');
            //$doc->url           = str::slug($request->get('title'), "_");
            $doc->user_id       = auth()->user()->id; //cambiar a actualizado por :
            $doc->area_id       = $request->get('area');
            $doc->exerpt        = $request->get('exerpt');
            $doc->content       = $request->get('content');
            $doc->published_at  = Carbon::parse($request->get('published_at'));
            $doc->public        = $request->get('public');
            $doc->save();
            
            $doc->tags()->sync($request->get('tags'));

            return redirect()->route('docs.edit', $doc)->with('success','Documento guardado');
        }

    public function show(Doc $doc)
        {
            return view('docs.show', compact('doc'));
        }

    public function destroy(Doc $doc)
        {
            $this->authorize('delete', $doc);
            $doc->tags()->detach();
            $doc->delete();
            return redirect()->route('docs.list')->with('success', 'Documento eliminado');
        }

    public function areaShow(area $area)
        {
            $docs=$area->Doc()->simplePaginate(3);
            return view ('docs.index', compact('docs'));
        }

    public function tagShow(tag $tag)
        {
            $docs=$tag->Doc()->simplePaginate(3);
            return view ('docs.index', compact('docs'));
        }
}
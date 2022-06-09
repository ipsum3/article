<?php

namespace Ipsum\Article\app\Http\Controllers;

use Illuminate\Http\Request;
use Ipsum\Admin\app\Http\Controllers\AdminController;
use Ipsum\Article\app\Http\Requests\StoreArticle;
use Ipsum\Article\app\Models\Article;
use Ipsum\Article\app\Models\Categorie;
use Prologue\Alerts\Facades\Alert;

class ArticleController extends AdminController
{
    protected $acces = 'article';

    public function index(Request $request, $type)
    {
        $query = Article::with('categorie', 'illustration')->where('type', $type);

        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->get('categorie_id'));
        }
        if ($request->filled('search')) {
            $query->where(function($query) use ($request) {
                foreach (['titre', 'nom', 'extrait', 'texte'] as $colonne) {
                    $query->orWhere($colonne, 'like', '%'.$request->get('search').'%');
                }
            });
        }
        if ($request->filled('tri')) {
            $query->orderBy($request->tri, $request->order);
        }
        $articles = $query->latest()->paginate();

        $categories = $this->getCategories($type);

        return view('IpsumArticle::article.index', compact('articles', 'type', 'categories'));
    }

    public function create($type)
    {
        $article = new Article;

        $categories = $this->getCategories($type);

        return view('IpsumArticle::article.form', compact('article', 'type', 'categories'));
    }

    public function store(StoreArticle $request, $type)
    {
        $article = Article::create($request->all());
        Alert::success("L'enregistrement a bien été ajouté")->flash();
        return redirect()->route('admin.article.edit', [$type, $article->id]);
    }

    public function edit($type, Article $article)
    {
        $categories = $this->getCategories($type);

        return view('IpsumArticle::article.form', compact('article', 'type', 'categories'));
    }

    public function update(StoreArticle $request, $type, Article $article)
    {
        $article->update($request->all());

        Alert::success("L'enregistrement a bien été modifié")->flash();
        return back();
    }

    public function destroy(Article $article)
    {
        if (!$article->is_deletable) {
            return abort(403);
        }

        $type = $article->type;
        $article->delete();

        Alert::warning("L'enregistrement a bien été supprimé")->flash();
        return redirect()->route('admin.article.index', $type);

    }

    private function getCategories($type)
    {
        if($parent_id = config('ipsum.article.types.'.$type.'.categorie_parent_id')) {
            return Categorie::where('parent_id',$parent_id)->with('children')->orderBy('order')->get();
        }
        return Categorie::root()->with('children')->orderBy('order')->get();
    }

}

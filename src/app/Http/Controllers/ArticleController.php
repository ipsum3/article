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

    public function index(Request $request, $type)
    {
        $query = Article::with('categorie', 'illustration')->where('type', $type);

        if ($request->has('categorie_id')) {
            $query->where('categorie_id', $request->get('categorie_id'));
        }
        if ($request->has('search')) {
            $query->where(function($query) use ($request) {
                foreach (['titre', 'extrait', 'texte'] as $colonne) {
                    $query->orWhere($colonne, 'like', '%'.$request->get('search').'%');
                }
            });
        }
        $articles = $query->latest()->paginate();

        $categories = Categorie::root()->with('children')->orderBy('order')->get()->pluck('nom', 'id');

        return view('IpsumArticle::article.index', compact('articles', 'type', 'categories'));
    }

    public function create($type)
    {
        $article = new Article;

        $categories = Categorie::root()->with('children')->orderBy('order')->get()->pluck('nom', 'id');

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
        $categories = Categorie::root()->with('children')->orderBy('order')->get()->pluck('nom', 'id');

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
        $article->delete();

        Alert::warning("L'enregistrement a bien été supprimé")->flash();
        return back();

    }
}

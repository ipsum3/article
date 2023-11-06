<?php

namespace Ipsum\Article\app\Http\Controllers;

use Illuminate\Http\Request;
use Ipsum\Admin\app\Http\Controllers\AdminController;
use Ipsum\Article\app\Http\Requests\StoreArticle;
use Ipsum\Article\app\Models\Article;
use Ipsum\Article\app\Models\Categorie;
use Prologue\Alerts\Facades\Alert;
use voku\helper\AntiXSS;

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

        $categories = Categorie::where('type', $type )->with('children')->orderBy('order')->get();

        return view('IpsumArticle::article.index', compact('articles', 'type', 'categories'));
    }

    public function create($type)
    {
        $article = new Article;

        $article->type = $type;

        $categories = $article->config['categorie'] ? Categorie::root( $article->config['categorie']['type'] )->with('children')->orderBy('order')->get() : null;

        return view('IpsumArticle::article.form', compact('article', 'type', 'categories'));
    }

    public function store(StoreArticle $request, $type)
    {
        $article = Article::create($request->validated());
        Alert::success("L'enregistrement a bien été ajouté")->flash();
        return redirect()->route('admin.article.edit', [$type, $article->id]);
    }

    public function edit($type, Article $article, $locale = null)
    {
        $categories = $article->config['categorie'] ? Categorie::root( $article->config['categorie']['type'] )->with('children')->orderBy('order')->get() : null;
        return view('IpsumArticle::article.form', compact('article', 'type', 'categories'));
    }

    public function update(StoreArticle $request, $type, Article $article, $locale = null)
    {
        $datas = $request->validated();
        $datas = $this->cleanArray($datas);

        $article->update($datas);

        Alert::success("L'enregistrement a bien été modifié")->flash();
        return back();
    }

    public function destroy(Article $article)
    {
        if ($article->config['is_guarded']) {
            return abort(403);
        }

        $type = $article->type;
        $article->delete();

        Alert::warning("L'enregistrement a bien été supprimé")->flash();
        return redirect()->route('admin.article.index', $type);

    }

    protected function cleanArray($fields)
    {
        foreach ($fields as $name => $value) {
            if(is_array($value)){
                $value = $this->cleanArray($value);
            }
            $fields[$name] = is_string($value) ? $this->cleanValue($value) : $value;
        }

        return $fields;
    }

    protected function cleanValue($data)
    {
        $antiXss = new AntiXSS();
        $antiXss->removeEvilHtmlTags(config('ipsum.admin.remove_evil_html_tags'));
        return $antiXss->xss_clean($data);
    }
}

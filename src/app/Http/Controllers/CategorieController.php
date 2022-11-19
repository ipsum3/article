<?php

namespace Ipsum\Article\app\Http\Controllers;

use Ipsum\Admin\app\Http\Controllers\AdminController;
use Ipsum\Article\app\Http\Requests\StoreCategorie;
use Ipsum\Article\app\Models\Categorie;
use Prologue\Alerts\Facades\Alert;

class CategorieController extends AdminController
{

    public function index()
    {
        $categories_by_type = [];
        foreach ( config('ipsum.categorie.types') as $type ) {
            $categories_by_type[$type] = Categorie::where('type', $type )->root($type)->with('children')->orderBy('order')->get();
        }

        return view('IpsumArticle::categorie.index', compact('categories_by_type'));
    }

    public function create( $type )
    {
        $categorie = new Categorie;

        $categories = Categorie::root($type)->get()->pluck('nom', 'id');

        return view('IpsumArticle::categorie.form', compact('categorie', 'categories', 'type'));
    }

    public function store(StoreCategorie $request)
    {
        $categorie = Categorie::create($request->validated());

        Alert::success("L'enregistrement a bien été ajouté")->flash();
        return redirect()->route('admin.articleCategorie.edit', [$categorie->id]);
    }

    public function edit(Categorie $categorie, $locale = null)
    {
        $categories = Categorie::root($categorie->type)->where('id', '!=', $categorie->id)->get()->pluck('nom', 'id');
        return view('IpsumArticle::categorie.form', compact('categorie', 'categories'));
    }

    public function update(StoreCategorie $request, Categorie $categorie, $locale = null)
    {
        $categorie->update($request->validated());

        Alert::success("L'enregistrement a bien été modifié")->flash();
        return back();
    }

    public function destroy(Categorie $categorie)
    {
       if ($categorie->articles->count()) {
           Alert::warning("Impossible de supprimer l'enregistrement, car il existe des articles associés")->flash();
           return back();
       }

        if ($categorie->children()->count()) {
            Alert::warning('error', "Impossible de supprimer l'enregistrement, cette catégorie comporte des enfants.")->flash();
            return back();
        }

        $categorie->delete();

        Alert::warning("L'enregistrement a bien été supprimé")->flash();
        return redirect()->route('admin.articleCategorie.index');

    }

    public function changeOrder(Categorie $categorie, $direction)
    {

        $categorie_suivante = Categorie::where('parent_id', $categorie->parent_id)->where('order', $categorie->order + ($direction == 'up' ? -1 : +1))->first();
        if(!$categorie_suivante) {
            Categorie::updateOrder($categorie->parent_id, $categorie->type); // En cas d'erreur on retrie les categories
        } else {
            $categorie->order = $categorie_suivante->order;
            $categorie_suivante->order = $categorie->getOriginal('order');

            $categorie->save();
            $categorie_suivante->save();
        }

        Alert::success("L'ordre a bien été changé")->flash();
        return back();
    }
}

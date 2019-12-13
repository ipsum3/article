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
        $categories = Categorie::root()->with('children')->orderBy('order')->get();

        return view('IpsumArticle::categorie.index', compact('categories'));
    }

    public function create()
    {
        $categorie = new Categorie;

        $categories = Categorie::root()->get()->pluck('nom', 'id');

        return view('IpsumArticle::categorie.form', compact('categorie', 'categories'));
    }

    public function store(StoreCategorie $request)
    {
        $categorie = Categorie::create($request->all());

        Alert::success("L'enregistrement a bien été ajouté")->flash();
        return redirect()->route('admin.articleCategorie.edit', [$categorie->id]);
    }

    public function edit(Categorie $categorie)
    {
        $categories = Categorie::root()->get()->pluck('nom', 'id');
        return view('IpsumArticle::categorie.form', compact('categorie', 'categories'));
    }

    public function update(StoreCategorie $request, Categorie $categorie)
    {
        $categorie->update($request->all());

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
            Categorie::updateOrder($categorie->parent_id); // En cas d'erreur on retrie les categories
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

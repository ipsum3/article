@extends('IpsumAdmin::layouts.app')
@section('title', 'Catégories')

@section('content')

    <h1 class="main-title">Catégorie</h1>

    {{ Aire::open()->route($categorie->exists ? 'admin.articleCategorie.update' : 'admin.articleCategorie.store', $categorie->exists ? $categorie->id : null)->bind($categorie)->formRequest(\Ipsum\Article\app\Http\Requests\StoreCategorie::class) }}
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ $categorie->exists ? 'Modification' : 'Ajout' }}</h3>
            </div>
            <div class="box-body">
                {{ Aire::input('nom', 'Nom*') }}
                {{-- TODO select recursif --}}
                {{ Aire::select(collect(['' => '---- Base -----'])->union($categories), 'parent_id', 'Catégorie parente') }}
                {{ Aire::textArea('description', 'Description')->class('tinymce-simple') }}
                <script src="{{ asset('ipsum/admin/dist/tinymce.js') }}"></script>
            </div>
            <div class="box-footer">
                <div><button class="btn btn-outline-secondary" type="reset">Annuler</button></div>
                <div><button class="btn btn-primary" type="submit">Enregistrer</button></div>
            </div>
        </div>
        @if(auth()->user()->isSuperAdmin())
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Seo</h3>
                </div>
                <div class="box-body">
                    {{ Aire::input('seo_title', 'Balise title') }}
                    {{ Aire::input('seo_description', 'Balise description') }}
                    {{ Aire::input('slug', 'Slug')->placeholder($categorie->exists ? $categorie->slug : null)->value('')->helpText('En cas de modification, pensez à modifier tous les liens vers cet categorie.') }}
                </div>
                <div class="box-footer">
                    <div><button class="btn btn-outline-secondary" type="reset">Annuler</button></div>
                    <div><button class="btn btn-primary" type="submit">Enregistrer</button></div>
                </div>
            </div>
        @endif
    {{ Aire::close() }}

@endsection
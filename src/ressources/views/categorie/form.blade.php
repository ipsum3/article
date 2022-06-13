@extends('IpsumAdmin::layouts.app')
@section('title', 'Catégories')

@section('content')

    <h1 class="main-title">Catégorie</h1>

    {{ Aire::open()->route($categorie->exists ? 'admin.articleCategorie.update' : 'admin.articleCategorie.store', $categorie->exists ? $categorie : null)->bind($categorie)->formRequest(\Ipsum\Article\app\Http\Requests\StoreCategorie::class) }}
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ $categorie->exists ? 'Modification' : 'Ajout' }}</h3>
                <div class="btn-toolbar">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Enregistrer</button>&nbsp;
                    <button class="btn btn-outline-secondary" type="reset" data-toggle="tooltip" title="Annuler les modifications en cours"><i class="fas fa-undo"></i></button>&nbsp;
                    @if ($categorie->exists)
                        <a class="btn btn-outline-secondary" href="{{ route('admin.articleCategorie.create') }}" data-toggle="tooltip" title="Ajouter">
                            <i class="fas fa-plus"></i>
                        </a>&nbsp;
                        @if(config('ipsum.article.categories.guard_id') AND !in_array($categorie->id,config('ipsum.article.categories.guard_id')))
                            <a class="btn btn-outline-danger" href="{{ route('admin.articleCategorie.delete', $categorie) }}" data-toggle="tooltip"
                               title="Supprimer">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        @endif
                    @endif
                </div>
            </div>
            <div class="box-body">
                {{ Aire::input('nom', 'Nom*') }}
                {{ Aire::select(collect(['' => '---- Base -----'])->union($categories), 'parent_id', 'Catégorie parente') }}
                {{ Aire::textArea('description', 'Description')->class('tinymce-simple') }}
                <script src="{{ asset('ipsum/admin/dist/tinymce.js') }}"></script>
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
            </div>
        @endif
    {{ Aire::close() }}

@endsection
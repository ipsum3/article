@extends('IpsumAdmin::layouts.app')
@section('title', 'Articles')

@section('content')

    <h1 class="main-title">Article</h1>

    {{ Aire::open()->route($article->exists ? 'admin.article.update' : 'admin.article.store', $article->exists ? [$type, $article] : $type)->bind($article)->formRequest(\Ipsum\Article\app\Http\Requests\StoreArticle::class) }}
    {{ Aire::hidden('type', $type) }}

    <div class="box">
        <div class="box-header">
            <h2 class="box-title">{{ $article->exists ? 'Modification' : 'Ajout ' . $type }}</h2>
            <div class="btn-toolbar">
                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Enregistrer</button>&nbsp;
                <button class="btn btn-outline-secondary" type="reset" data-toggle="tooltip" title="Annuler les modifications en cours"><i class="fas fa-undo"></i></button>&nbsp;
                @if ($article->exists)
                    <a class="btn btn-outline-secondary" href="{{ route('admin.article.create', $type) }}" data-toggle="tooltip" title="Ajouter">
                        <i class="fas fa-plus"></i>
                    </a>&nbsp;
                    @if (!$article->config['is_guarded'])
                        <a class="btn btn-outline-danger" href="{{ route('admin.article.delete', $article) }}" data-toggle="tooltip" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    @endif
                @endif
            </div>
        </div>
        <div class="box-body">
            {{ Aire::input('titre', 'Titre*') }}

            @if ($article->config['categorie'])
                <div class="form-group">
                    <label for="categorie_id">Catégorie*</label>
                    <select id="categorie_id" name="categorie_id" class="form-control @error('categorie_id') is-invalid @enderror">
                        <option value="">---- Catégories -----</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ ($article->exists and $categorie->id == request()->old('categorie_id', $article->categorie_id)) ? 'selected' : '' }}>{{ $categorie->nom }}</option>
                            @foreach($categorie->children as $sous_categorie)
                                <option value="{{ $sous_categorie->id }}" {{ ($article->exists and $sous_categorie->id == request()->old('categorie_id', $article->categorie_id)) ? 'selected' : '' }}>-- {{ $sous_categorie->nom }}</option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('categorie_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            @if ( $article->config['has_extrait'] )
                {{ Aire::textArea('extrait', 'Extrait')->class('tinymce-simple') }}
            @endif

            @if ( $article->config['has_texte'] )
                {{ Aire::textArea('texte', 'Texte')->class('tinymce')->data('medias', route('admin.media.popin')) }}
            @endif

        </div>
    </div>

    @if ( $article->config['custom_fields'] )
        <div class="box">
            <div class="box-header">
                <h2 class="box-title">
                    Informations complémentaires
                </h2>
            </div>
            <div class="box-body">
                @foreach($article->config['custom_fields'] as $field)
                    <x-admin::custom
                            name="{{ 'custom_fields['.$field['name'].']' }}"
                            label="{{ $field['label'] }}"
                            description="{{ $field['description'] }}"
                            value="{!! old('custom_fields.'.$field['name'], $article->custom_fields->{$field['name']}) !!}"
                            type="{{ $field['type'] }}"
                    />
                @endforeach
            </div>
        </div>
    @endif

    <script src="{{ asset('ipsum/admin/dist/tinymce.js') }}"></script>

    <div class="box">
        <div class="box-header">
            <h2 class="box-title">
                Publication
                @if ($article->exists)
                    <small class="text-muted">&nbsp;Créé le <span data-toggle="tooltip"  title="{{ $article->created_at }}">{{ $article->created_at->format('d/m/Y')  }}</span> et modifié le <span data-toggle="tooltip"  title="{{ $article->updated_at }}">{{ $article->updated_at->format('d/m/Y')  }}</span></small>
                @endif
            </h2>
        </div>
        <div class="box-body row">
            <div class="col">
                {{ Aire::date('date', 'Date') }}
            </div>
            <div class="col">
                {{ Aire::select(config('ipsum.article.etats'), 'etat', 'Etat') }}
            </div>
        </div>
    </div>
    @if( $article->config['medias'] )
        @foreach( $article->config['medias'] as $media )
            <div class="box">
                <div class="box-header">
                    <h2 class="box-title">Médias {{ $media['groupe'] }}</h2>
                </div>
                <div class="box-body">
                    <div class="upload"
                         data-uploadendpoint="{{ route('admin.media.store') }}"
                         data-uploadmedias="{{ route('admin.media.publication', ['publication_type' => \Ipsum\Article\app\Models\Article::class, 'publication_id' => $article->exists ? $article->id : '', "groupe" => $media['groupe']]) }}"
                         data-uploadrepertoire="article"
                         data-uploadpublicationid="{{ $article->id }}"
                         data-uploadpublicationtype="{{ \Ipsum\Article\app\Models\Article::class }}"
                         data-uploadgroupe="{{ $media['groupe'] }}"
                         data-uploadnote="Images et documents, poids maximum {{ config('ipsum.media.upload_max_filesize') }} Ko"
                         data-uploadmaxfilesize="{{ config('ipsum.media.upload_max_filesize') }}"
                         data-uploadmmaxnumberoffiles=""
                         data-uploadminnumberoffiles=""
                         data-uploadallowedfiletypes=""
                         data-uploadcsrftoken="{{ csrf_token() }}">
                        <div class="upload-DragDrop"></div>
                        <div class="upload-ProgressBar"></div>
                        <div class="upload-alerts mt-3"></div>
                        <div class="mt-3">
                            <h3>Médias associés :</h3>
                            <div class="d-flex flex-row flex-wrap sortable upload-files" data-sortableurl="{{ route('admin.media.changeOrder') }}" data-sortablecsrftoken="{{ csrf_token() }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <link href="{{ asset('ipsum/admin/dist/uppy.css') }}" rel="stylesheet">
        <script src="{{ asset('ipsum/admin/dist/uppy.js') }}"></script>
    @endif
    @if(auth()->user()->isSuperAdmin())
        <div class="box">
            <div class="box-header">
                <h2 class="box-title">Seo</h2>
            </div>
            <div class="box-body">
                {{ Aire::input('seo_title', 'Balise title') }}
                {{ Aire::input('seo_description', 'Balise description') }}
                {{ Aire::input('slug', 'Slug')->placeholder($article->exists ? $article->slug : null)->value('')->helpText('En cas de modification, pensez à modifier tous les liens vers cet article.') }}
            </div>
        </div>
    @endif
    {{ Aire::close() }}

@endsection

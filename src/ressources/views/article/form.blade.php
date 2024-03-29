@extends('IpsumAdmin::layouts.app')
@section('title', 'Articles')

@section('content')

    <h1 class="main-title">Article</h1>

    {{ Aire::open()->route($article->exists ? 'admin.article.update' : 'admin.article.store', $article->exists ? [$type, $article, request()->route('locale')] : $type)->bind($article)->formRequest(\Ipsum\Article\app\Http\Requests\StoreArticle::class) }}
    {{ Aire::hidden('type', $type) }}
    <div class="box">
        <div class="box-header">
            <h2 class="box-title">{{ $article->exists ? 'Modification' : 'Ajout' }}</h2>
            <div class="btn-toolbar">
                @if ($article->exists and count(config('ipsum.translate.locales')) > 1)
                    <ul class="nav nav-tabs mr-5" role="tablist">
                        @foreach(config('ipsum.translate.locales') as $locale)
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->route('locale') == $locale['nom'] or (request()->route('locale') === null and config('ipsum.translate.default_locale') == $locale['nom'])) ? 'active' : '' }}"
                                   href="{{ route('admin.article.edit', [$type, $article, $locale['nom']]) }}"
                                   aria-selected="true">{{ $locale['intitule'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Enregistrer</button>&nbsp;
                <button class="btn btn-outline-secondary" type="reset" data-toggle="tooltip"
                        title="Annuler les modifications en cours"><i class="fas fa-undo"></i></button>&nbsp;
                @if ($article->exists)
                    <a class="btn btn-outline-secondary" href="{{ route('admin.article.create', $type) }}"
                       data-toggle="tooltip" title="Ajouter">
                        <i class="fas fa-plus"></i>
                    </a>&nbsp;
                    @if (!$article->config['is_guarded'])
                        <a class="btn btn-outline-danger" href="{{ route('admin.article.delete', $article) }}"
                           data-toggle="tooltip" title="Supprimer">
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
                    <select id="categorie_id" name="categorie_id"
                            class="form-control @error('categorie_id') is-invalid @enderror">
                        <option value="">---- Catégories -----</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ ($article->exists and $categorie->id == request()->old('categorie_id', $article->categorie_id)) ? 'selected' : '' }}>{{ $categorie->nom }}</option>
                            @foreach($categorie->children as $sous_categorie)
                                <option value="{{ $sous_categorie->id }}" {{ ($article->exists and $sous_categorie->id == request()->old('categorie_id', $article->categorie_id)) ? 'selected' : '' }}>
                                    -- {{ $sous_categorie->nom }}</option>
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

    @if (!empty($article->config['custom_blocs']))
        <div id="blocs-container">
            <div id="blocs" class="sortable">
                @if ($article->custom_blocs->count())
                    @foreach($article->custom_blocs as $key => $bloc)
                        @include("IpsumArticle::article._bloc")
                    @endforeach
                @endif
            </div>
        </div>

        @foreach($article->config['custom_blocs'] as $bloc_config)
            @php
            $bloc = (object) $bloc_config;
            @endphp
            <div id="customBlocs-{{ $bloc_config['name'] }}-template" class="x-tmpl-mustache d-none">
                @include("IpsumArticle::article._bloc", ['key' => '@{{indice}}', $bloc])
            </div>
        @endforeach

        <div class="box">
            <div class="box-header">
                <h2 class="box-title">Blocs</h2>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="type_bloc">Ajouter un bloc</label>
                    <div class="row">
                        <div class="col">
                            <select name="type_bloc" class="col form-control" id="type_bloc">
                                <option value="">----- Type de bloc -----</option>
                                @foreach($article->config['custom_blocs'] as $bloc)
                                    <option value="{{ $bloc['name'] }}">{{ $bloc['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <button id="add-bloc_btn" class="btn btn-primary"
                                    data-prefix-route="{{ config('ipsum.admin.route_prefix') }}"
                                    data-article="{{ $article->id }}" type="button">Ajouter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (!empty($article->config['custom_fields']))
        <div class="box">
            <div class="box-header">
                <h2 class="box-title">
                    Informations complémentaires
                </h2>
            </div>
            <div class="box-body">
                @foreach($article->config['custom_fields'] as $field)
                    @php
                        $field_name = 'custom_fields['.$field['name'].']';
                        $field_value = old('custom_fields.'.$field['name'], $article->custom_fields->{$field['name']} ?  : ($field['type'] == "repeater" ? [] : '') );
                    @endphp
                    <x-admin::custom :field="$field" :name="$field_name" :value="$field_value"/>

                @endforeach
            </div>
        </div>
    @endif

    <script src="{{ asset('ipsum/admin/dist/tinymce.js') }}"></script>

    @if (!empty($article->config['publication']['has_date']) || !empty($article->config['publication']['has_etat']))
        <div class="box">
            <div class="box-header">
                <h2 class="box-title">
                    Publication
                    @if ($article->exists)
                        <small class="text-muted">&nbsp;Créé le <span data-toggle="tooltip"
                                                                      title="{{ $article->created_at }}">{{ $article->created_at->format('d/m/Y')  }}</span>
                            et modifié le <span data-toggle="tooltip"
                                                title="{{ $article->updated_at }}">{{ $article->updated_at->format('d/m/Y')  }}</span></small>
                    @endif
                </h2>
            </div>
            <div class="box-body row">
                @if (!empty($article->config['publication']['has_date']))
                    <div class="col">
                        @if (!empty($article->config['publication']['required_date']))
                            {{ Aire::date('date', 'Date*')->required() }}
                        @else
                            {{ Aire::date('date', 'Date') }}
                        @endif
                    </div>
                @endif
                @if (!empty($article->config['publication']['has_etat']))
                    <div class="col">
                        {{ Aire::select(config('ipsum.article.etats'), 'etat', 'Etat*')->required() }}
                    </div>
                @endif
            </div>
        </div>
    @endif
    @if (empty($article->config['publication']['has_etat']))
        {{ Aire::hidden('etat', array_key_first(config('ipsum.article.etats'))) }}
    @endif
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
                            <div class="d-flex flex-row flex-wrap sortable upload-files"
                                 data-sortableurl="{{ route('admin.media.changeOrder') }}"
                                 data-sortablecsrftoken="{{ csrf_token() }}">
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

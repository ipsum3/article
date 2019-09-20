@extends('IpsumAdmin::layouts.app')
@section('title', 'Articles')

@section('content')

    <h1 class="main-title">Article</h1>

    {{ Aire::open()->route($article->exists ? 'admin.article.update' : 'admin.article.store', $article->exists ? [$type, $article->id] : $type)->bind($article)->formRequest(\Ipsum\Article\app\Http\Requests\StoreArticle::class) }}
        {{ Aire::hidden('type', $type) }}
        <div class="box">
            <div class="box-header">
                <h2 class="box-title">{{ $article->exists ? 'Modification' : 'Ajout' }}</h2>
            </div>
            <div class="box-body">
                {{ Aire::input('titre', 'Titre*') }}
                @if ($type != \Ipsum\Article\app\Models\Article::TYPE_PAGE)
                {{-- TODO select recursif --}}
                {{ Aire::select(collect(['' => '---- Catégorie -----'])->union($categories), 'categorie_id', 'Catégorie') }}
                @endif
                {{ Aire::textArea('extrait', 'Extrait')->class('tinymce-simple') }}

                {{ Aire::textArea('texte', 'Texte')->class('tinymce')->data('medias', route('admin.media.popin')) }}

                <script src="{{ asset('ipsum/admin/dist/tinymce.js') }}"></script>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h2 class="box-title">
                    Publication
                    @if ($article->exists)
                    <small class="text-muted">Créé le <span data-toggle="tooltip"  title="{{ $article->created_at }}">{{ $article->created_at->format('d/m/Y')  }}</span> et modifié le <span data-toggle="tooltip"  title="{{ $article->updated_at }}">{{ $article->updated_at->format('d/m/Y')  }}</span></small>
                    @endif
                </h2>
            </div>
            <div class="box-body row">
                <div class="col">
                    {{ Aire::date('date', 'Date') }}
                </div>
                <div class="col">
                    {{ Aire::select(\Ipsum\Article\app\Models\Article::$etats, 'etat', 'Etat') }}
                </div>
            </div>
            <div class="box-footer">
                <div><button class="btn btn-outline-secondary" type="reset">Annuler</button></div>
                <div><button class="btn btn-primary" type="submit">Enregistrer</button></div>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h2 class="box-title">Médias</h2>
            </div>
            <div class="box-body">
                <div class="upload"
                     data-uploadendpoint="{{ route('admin.media.store') }}"
                     data-uploadmedias="{{ route('admin.media.publication', [urlencode(\Ipsum\Article\app\Models\Article::class), 'publication_id' => $article->exists ? $article->id : '']) }}"
                     data-uploadrepertoire="article"
                     data-uploadpublicationid="{{ $article->id }}"
                     data-uploadpublicationtype="{{ \Ipsum\Article\app\Models\Article::class }}"
                     data-uploadgroupe=""
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
                        <div class="d-flex flex-row flex-wrap sortable upload-files" data-sortableurl="{{ route('admin.media.changeOrder', [urlencode(\Ipsum\Article\app\Models\Article::class), $article->exists ? $article->id : 0]) }}" data-sortablecsrftoken="{{ csrf_token() }}">
                        </div>
                    </div>
                </div>
            </div>
            <link href="{{ asset('ipsum/admin/dist/uppy.css') }}" rel="stylesheet">
            <script src="{{ asset('ipsum/admin/dist/uppy.js') }}"></script>
        </div>
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
                <div class="box-footer">
                    <div><button class="btn btn-outline-secondary" type="reset">Annuler</button></div>
                    <div><button class="btn btn-primary" type="submit">Enregistrer</button></div>
                </div>
            </div>
        @endif
    {{ Aire::close() }}

@endsection
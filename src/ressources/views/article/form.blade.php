@extends('IpsumAdmin::layouts.app')
@section('title', 'Articles')

@section('content')

    <h1 class="main-title">Article</h1>

    {{ Aire::open()->route($article->exists ? 'admin.article.update' : 'admin.article.store', $article->exists ? [$type, $article->id] : $type)->bind($article)->formRequest(\Ipsum\Article\app\Http\Requests\StoreArticle::class) }}
        {{ Aire::hidden('type', $type) }}
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ $article->exists ? 'Modification' : 'Ajout' }}</h3>
            </div>
            <div class="box-body">
                {{ Aire::input('titre', 'Titre*') }}
                @if ($type != \Ipsum\Article\app\Models\Article::TYPE_PAGE)
                {{ Aire::select(collect(['' => '---- Catégorie -----'])->union($categories), 'categorie_id', 'Catégorie') }}
                @endif
                {{ Aire::textArea('extrait', 'Extrait') }}

                <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
                <script>
                    tinymce.init({
                        selector: '.tinymce',
                        plugins: "code paste autolink fullscreen link lists media image ",
                        toolbar: "bold italic bullist numlist removeformat | formatselect | link image media | code fullscreen",
                        menubar: "",
                        paste_as_text: true,
                        height: 500,
                        branding: false,
                        target_list: false,
                        image_class_list: [
                            {title: 'None', value: ''},
                            {title: 'Left', value: 'text-left'},
                            {title: 'Center', value: 'text-center'}
                        ],
                        image_dimensions: false,
                        object_resizing : false,
                        formats: {
                            headline: { block: 'div', classes: 'headline' },
                            highlight: { block: 'div', classes: 'highlight' },
                        },

                        block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Chapeau=headline; Mise en avant=highlight;',

                        language: '{{ app()->getLocale() }}', {{-- TODO --}}

                        fix_list_elements : true

                    });
                </script>
                <textarea name="texte" class="tinymce">{{ request()->old('texte', $article->exists ? $article->texte : '') }}</textarea>
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
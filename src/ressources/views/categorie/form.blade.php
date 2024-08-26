@extends('IpsumAdmin::layouts.app')
@section('title', 'Catégories')

@section('content')

    <h1 class="main-title">Catégorie</h1>

    {{ Aire::open()->route($categorie->exists ? 'admin.articleCategorie.update' : 'admin.articleCategorie.store', $categorie->exists ? [$categorie, request()->route('locale')] : null)->bind($categorie)->formRequest(\Ipsum\Article\app\Http\Requests\StoreCategorie::class) }}
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ $categorie->exists ? 'Modification' : 'Ajout catégorie ' . $type }}</h3>
                <div class="btn-toolbar">
                    @if ($categorie->exists and count(config('ipsum.translate.locales')) > 1)
                        <ul class="nav nav-tabs mr-5" role="tablist">
                            @foreach(config('ipsum.translate.locales') as $locale)
                                <li class="nav-item">
                                    <a class="nav-link {{ (request()->route('locale') == $locale['nom'] or (request()->route('locale') === null and config('ipsum.translate.default_locale') == $locale['nom'])) ? 'active' : '' }}" href="{{ route('admin.articleCategorie.edit', [$categorie, $locale['nom']]) }}" aria-selected="true">{{ $locale['intitule'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Enregistrer</button>&nbsp;
                    <button class="btn btn-outline-secondary" type="reset" data-toggle="tooltip" title="Annuler les modifications en cours"><i class="fas fa-undo"></i></button>&nbsp;
                    @if ($categorie->exists)
                        <a class="btn btn-outline-secondary" href="{{ route('admin.articleCategorie.create',[$categorie->type]) }}" data-toggle="tooltip" title="Ajouter">
                            <i class="fas fa-plus"></i>
                        </a>&nbsp;
                        @if (!$categorie->config['is_guarded'])
                            <a class="btn btn-outline-danger" href="{{ route('admin.articleCategorie.delete', $categorie) }}" data-toggle="tooltip" title="Supprimer">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        @endif
                    @endif
                </div>
            </div>
            <div class="box-body">
                {{ Aire::hidden('type', $categorie->exists ? $categorie->type : $type) }}
                {{ Aire::input('nom', 'Nom*') }}
                {{ Aire::select(collect(['' => '---- Base -----'])->union($categories), 'parent_id', 'Catégorie parente') }}
                {{ Aire::textArea('description', 'Description')->class('tinymce-simple') }}
            </div>
        </div>
        @if (!empty($categorie->config['custom_fields']))
            <div class="box">
                <div class="box-header">
                    <h2 class="box-title">
                        Informations complémentaires
                    </h2>
                </div>
                <div class="box-body">
                    @foreach($categorie->config['custom_fields'] as $field)
                        @php
                            $field_name = 'custom_fields['.$field['name'].']';
                            $field_value = old('custom_fields.'.$field['name'], $categorie->custom_fields->{$field['name']} ?  : ($field['type'] == "repeater" ? [] : '') );
                        @endphp
                        <x-admin::custom :field="$field" :name="$field_name" :value="$field_value"/>
                    @endforeach
                </div>
            </div>
        @endif

        @if( !empty($categorie->config['medias']) )
            @foreach( $categorie->config['medias'] as $media )
                <div class="box">
                    <div class="box-header">
                        <h2 class="box-title">Médias {{ $media['groupe'] }}</h2>
                    </div>
                    <div class="box-body">
                        <div class="upload"
                             data-uploadendpoint="{{ route('admin.media.store') }}"
                             data-uploadmedias="{{ route('admin.media.publication', ['publication_type' => \Ipsum\Article\app\Models\Categorie::class, 'publication_id' => $categorie->exists ? $categorie->id : '', "groupe" => $media['groupe']]) }}"
                             data-uploadrepertoire="article"
                             data-uploadpublicationid="{{ $categorie->id }}"
                             data-uploadpublicationtype="{{ \Ipsum\Article\app\Models\Categorie::class }}"
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

        <script src="{{ asset('ipsum/admin/dist/tinymce.js') }}"></script>
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
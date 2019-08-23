@extends('IpsumAdmin::layouts.app')
@section('title', 'Articles')

@section('content')

    <h1 class="main-title">Articles</h1>
    <div class="box">
        <div class="box-header">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h2 class="box-title">Liste ({{ $articles->total() }})</h2>
                <div class="btn-toolbar">
                    <a class="btn btn-primary" href="{{ route('admin.article.create', $type) }}">
                        <i class="fas fa-plus"></i>
                        Ajouter
                    </a>
                </div>
            </div>
        </div>
        <div class="box-body">

            {{ Aire::open()->class('form-inline mt-4 mb-1')->route('admin.article.index', $type) }}
                <label class="sr-only" for="search">Recherche</label>
                {{ Aire::input('search')->id('search')->class('form-control mb-2 mr-sm-2')->value(request()->get('search'))->placeholder('Recherche')->withoutGroup() }}

                @if ($type != \Ipsum\Article\app\Models\Article::TYPE_PAGE)
                <label class="sr-only" for="categorie_id">Catégorie</label>
                {{-- TODO select recursif --}}
                {{ Aire::select(collect(['' => '---- Catégorie -----'])->union($categories), 'categorie_id')->value(request()->get('categorie_id'))->id('categorie_id')->class('form-control mb-2 mr-sm-2')->withoutGroup() }}
                @endif

                <button type="submit" class="btn btn-outline-secondary mb-2">Submit</button>
            {{ Aire::close() }}

            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Titre</th>
                        <th>Extrait</th>
                        @if ($type != \Ipsum\Article\app\Models\Article::TYPE_PAGE)
                        <th>Catégorie</th>
                        @endif
                        <th width="180px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($articles as $article)
                    <tr>
                        <td>{{ $article->id }}</td>
                        <td>{{ $article->created_at->format('d/m/Y') }}</td>
                        <td>{{ $article->titre }}</td>
                        <td>{{ Str::limit($article->extrait) }}</td>
                        @if ($type != \Ipsum\Article\app\Models\Article::TYPE_PAGE)
                        <td>{{ $article->categorie ? $article->categorie->nom : '' }}</td>
                        @endif
                        <td class="text-right">
                            <form action="{{ route('admin.article.destroy', $article->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <a class="btn btn-primary" href="{{ route('admin.article.edit', [$type, $article->id]) }}"><i class="fa fa-pen"></i> Edit</a>
                                @if ($article->type != \Ipsum\Article\app\Models\Article::TYPE_PAGE)
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash-alt"></i> Delete</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $articles->appends(request()->all())->links() !!}

        </div>
    </div>

@endsection
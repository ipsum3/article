@extends('IpsumAdmin::layouts.app')
@section('title', 'Catégories')

@section('content')

    <h1 class="main-title">Catégories</h1>
    <div class="box">
        <div class="box-header">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h2 class="box-title">Liste ({{ $categories->count() }})</h2>
                <div class="btn-toolbar">
                    <a class="btn btn-primary" href="{{ route('admin.articleCategorie.create') }}">
                        <i class="fas fa-plus"></i>
                        Ajouter
                    </a>
                </div>
            </div>
        </div>
        <div class="box-body">

            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Ordre</th>
                        <th width="180px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($categories as $categorie_key => $categorie)
                    <tr class="bg-secondary text-white">
                        <td>{{ $categorie->id }}</td>
                        <td>{{ $categorie->nom }}</td>
                        <td>{{ $categorie->description }}</td>
                        <td>
                            @if($categorie_key < $categories->count() - 1)
                                <a href="{{ route('admin.articleCategorie.changeOrder', [$categorie, 'down']) }}" class="text-white"><span class="fa fa-arrow-down"></span></a>
                            @endif
                            @if($categorie_key)
                                <a href="{{ route('admin.articleCategorie.changeOrder', [$categorie, 'up']) }}" class="text-white"><span class="fa fa-arrow-up"></span></a>
                            @endif
                        </td>
                        <td class="text-right">
                            <form action="{{ route('admin.articleCategorie.destroy', $categorie) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <a class="btn btn-primary" href="{{ route('admin.articleCategorie.edit', $categorie->id) }}"><i class="fa fa-pen"></i> Edit</a>
                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash-alt"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                    @foreach ($categorie->children as $child_key => $child)
                        <tr>
                            <td>{{ $child->id }}</td>
                            <td>---- {{ $child->nom }}</td>
                            <td>{{ $child->description }}</td>
                            <td>
                                @if($child_key < $categorie->children->count() - 1)
                                    <a href="{{ route('admin.articleCategorie.changeOrder', [$child, 'down']) }}" ><span class="fa fa-arrow-down"></span></a>
                                @endif
                                @if($child_key)
                                    <a href="{{ route('admin.articleCategorie.changeOrder', [$child, 'up']) }}" ><span class="fa fa-arrow-up"></span></a>
                                @endif
                            </td>
                            <td class="text-right">
                                <form action="{{ route('admin.articleCategorie.destroy', $child) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a class="btn btn-primary" href="{{ route('admin.articleCategorie.edit', $child->id) }}"><i class="fa fa-edit"></i> Edit</a>
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash-alt"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endsection
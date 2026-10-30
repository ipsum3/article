<?php

use Illuminate\Support\Facades\Route;
use Ipsum\Article\app\Http\Controllers\ArticleController;
use Ipsum\Article\app\Http\Controllers\CategorieController;

Route::middleware(['web'])
    ->prefix(config('ipsum.admin.route_prefix').'/article')
    ->group(function () {

        Route::get('categorie', [CategorieController::class, 'index'])
            ->name('admin.articleCategorie.index');
        Route::post('categorie', [CategorieController::class, 'store'])
            ->name('admin.articleCategorie.store');
        Route::get('categorie/{type}/create', [CategorieController::class, 'create'])
            ->name('admin.articleCategorie.create');
        Route::delete('categorie/{categorie}', [CategorieController::class, 'destroy'])
            ->name('admin.articleCategorie.destroy');
        Route::get('categorie/{categorie}/delete', [CategorieController::class, 'destroy'])
            ->name('admin.articleCategorie.delete');
        Route::put('categorie/{categorie}/{locale?}', [CategorieController::class, 'update'])
            ->name('admin.articleCategorie.update');
        Route::get('categorie/{categorie}/edit/{locale?}', [CategorieController::class, 'edit'])
            ->name('admin.articleCategorie.edit');
        Route::get('categorie/{categorie}/changeOrder/{direction}', [CategorieController::class, 'changeOrder'])
            ->name('admin.articleCategorie.changeOrder');

        Route::get('{type}', [ArticleController::class, 'index'])
            ->name('admin.article.index');
        Route::post('{type}', [ArticleController::class, 'store'])
            ->name('admin.article.store');
        Route::get('{type}/create', [ArticleController::class, 'create'])
            ->name('admin.article.create');
        Route::delete('{article}', [ArticleController::class, 'destroy'])
            ->name('admin.article.destroy');
        Route::get('{article}/delete', [ArticleController::class, 'destroy'])
            ->name('admin.article.delete');
        Route::put('{type}/{article}/{locale?}', [ArticleController::class, 'update'])
            ->name('admin.article.update');
        Route::get('{type}/{article}/edit/{locale?}', [ArticleController::class, 'edit'])
            ->name('admin.article.edit');

    });
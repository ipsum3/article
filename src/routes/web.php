<?php

Route::group(
    [
        'middleware' => ['web'],
        'prefix' => config('ipsum.admin.route_prefix').'/article',
        'namespace' => '\Ipsum\Article\app\Http\Controllers'
    ],
    function() {

        Route::get("categorie", array(
            "as" => "admin.articleCategorie.index",
            "uses" => "CategorieController@index",
        ));
        Route::post("categorie", array(
            "as" => "admin.articleCategorie.store",
            "uses" => "CategorieController@store",
        ));
        Route::get("categorie/{type}/create", array(
            "as" => "admin.articleCategorie.create",
            "uses" => "CategorieController@create",
        ));
        Route::delete("categorie/{categorie}", array(
            "as" => "admin.articleCategorie.destroy",
            "uses" => "CategorieController@destroy",
        ));
        Route::get("categorie/{categorie}/delete", array(
            "as" => "admin.articleCategorie.delete",
            "uses" => "CategorieController@destroy",
        ));
        Route::put("categorie/{categorie}", array(
            "as" => "admin.articleCategorie.update",
            "uses" => "CategorieController@update",
        ));
        Route::get("categorie/{categorie}/edit", array(
            "as" => "admin.articleCategorie.edit",
            "uses" => "CategorieController@edit",
        ));
        Route::get('categorie/{categorie}/changeOrder/{direction}', array(
            'as'     => 'admin.articleCategorie.changeOrder',
            'uses'   => 'CategorieController@changeOrder'
        ));


        Route::get("{type}", array(
            "as" => "admin.article.index",
            "uses" => "ArticleController@index",
        ));
        Route::post("{type}", array(
            "as" => "admin.article.store",
            "uses" => "ArticleController@store",
        ));
        Route::get("{type}/create", array(
            "as" => "admin.article.create",
            "uses" => "ArticleController@create",
        ));
        Route::delete("{article}", array(
            "as" => "admin.article.destroy",
            "uses" => "ArticleController@destroy",
        ));
        Route::get("{article}/delete", array(
            "as" => "admin.article.delete",
            "uses" => "ArticleController@destroy",
        ));
        Route::put("{type}/{article}", array(
            "as" => "admin.article.update",
            "uses" => "ArticleController@update",
        ));
        Route::get("{type}/{article}/edit", array(
            "as" => "admin.article.edit",
            "uses" => "ArticleController@edit",
        ));

    }
);
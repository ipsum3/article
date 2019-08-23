<?php

use Ipsum\Article\app\Models\Article;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Article::class, function (Faker $faker) {
    return [
        'titre' => $faker->sentence,
        'type' => Article::TYPE_POST,
        'extrait' => $faker->text(100),
        'texte' => $faker->realText(),
    ];
});

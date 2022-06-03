<?php

namespace Ipsum\Article\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ipsum\Article\app\Models\Article;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ArticleFactory extends Factory
{

    protected $model = Article::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
		return [
			'titre' => $this->faker->sentence,
			'type' => Article::TYPE_POST,
			'extrait' => $this->faker->text(100),
			'texte' => $this->faker->realText(),
		];
    }

}

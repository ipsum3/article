<?php
namespace Ipsum\Article\database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ipsum\Article\app\Models\Categorie;

class CategorieFactory extends Factory
{

    protected $model = Categorie::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->sentence()
        ];
    }
}
<?php

namespace Ipsum\Article\database\seeds;

use Illuminate\Database\Seeder;
use Ipsum\Article\app\Models\Article;
use Ipsum\Article\app\Models\Categorie;


class ArticleTableSeeder extends Seeder
{
    public function run()
    {
        // Seed Post
        factory(Article::class, 10)->create();

        // Sees Page
        foreach ($this->getPages() as $page) {
            factory(Article::class, 1)->create($page);
        }

        // Seed Catégorie
        factory(Categorie::class, 3)->create();
    }

    private function getPages()
    {
        return array(
            array(
                'slug' => '',
                'titre' => 'Accueil',
                'type' => Article::TYPE_PAGE,
            ),
            array(
                'slug' => 'page1',
                'titre' => 'Page 1',
                'type' => Article::TYPE_PAGE,
            ),
            array(
                'slug' => 'page2',
                'titre' => 'Page 2',
                'type' => Article::TYPE_PAGE,
            ),
            array(
                'slug' => 'contact',
                'titre' => 'Contact',
                'type' => Article::TYPE_PAGE,
            ),
            array(
                'slug' => 'mentions-legales',
                'titre' => 'Mentions légales',
                'type' => Article::TYPE_PAGE,
            ),
        );
    }

}

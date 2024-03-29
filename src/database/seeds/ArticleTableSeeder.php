<?php

namespace Ipsum\Article\database\seeds;

use Illuminate\Database\Seeder;
use Ipsum\Article\app\Models\Article;
use Ipsum\Article\app\Models\Categorie;


class ArticleTableSeeder extends Seeder
{
    public function run()
    {
        $articles = Article::all()->pluck('slug')->toArray();

        // Seed Page
        foreach ($this->getPages() as $page) {
            if (!in_array($page['slug'], $articles)) {
                Article::create($page);
            }
        }

        if (!count($articles)) {
            // Seed Post
            Article::factory()->count(10)->create();
        }

        // Seed Catégorie
        Categorie::factory()->count(4)->create();
    }

    private function getPages()
    {
        return array(
            array(
                'slug' => '',
                'titre' => 'Accueil',
                'nom' => 'Accueil',
                'type' => Article::TYPE_PAGE,
            ),
            array(
                'slug' => 'page1',
                'titre' => 'Page 1',
                'nom' => 'Page 1',
                'type' => Article::TYPE_PAGE,
            ),
            array(
                'slug' => 'page2',
                'titre' => 'Page 2',
                'nom' => 'Page 2',
                'type' => Article::TYPE_PAGE,
            ),
            array(
                'slug' => 'contact',
                'titre' => 'Contact',
                'nom' => 'Contact',
                'type' => Article::TYPE_PAGE,
            ),
            array(
                'slug' => 'blog',
                'titre' => 'Blog',
                'nom' => 'Blog',
                'type' => Article::TYPE_PAGE,
            ),
            array(
                'slug' => 'mentions-legales',
                'titre' => 'Mentions légales',
                'nom' => 'Mentions légales',
                'type' => Article::TYPE_PAGE,
            ),
        );
    }

}

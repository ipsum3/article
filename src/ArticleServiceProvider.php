<?php

namespace Ipsum\Article;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Ipsum\Article\app\Models\Article;
use Ipsum\Article\app\Policies\ArticlePolicy;

class ArticleServiceProvider extends ServiceProvider
{

    protected $commands = [
        \Ipsum\Article\app\Console\Commands\Install::class,
    ];

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    protected $policies = [
        Article::class => ArticlePolicy::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //Schema::defaultStringLength(191); // Fix version de mysql

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadViews();
        //$this->loadTranslationsFrom(__DIR__.'/ressources/lang', 'IpsumArticle');

        $this->publishFiles();

        //$this->addPolicies();

    }


    public function loadViews()
    {
        $this->loadViewsFrom([
            resource_path('views/ipsum/article'),
            __DIR__.'/ressources/views',
        ], 'IpsumArticle');

    }


    public function addPolicies()
    {
        $this->registerPolicies();
    }



    public function publishFiles()
    {
        $this->publishes([
            __DIR__.'/ressources/views' => resource_path('views/ipsum/article'),
        ], 'views');
    
        $this->publishes([
            __DIR__.'/database/seeds/' => database_path('seeds'),
        ], 'install');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(
            __DIR__.'/config/ipsum/article.php', 'ipsum.article'
        );

        // register the artisan commands
        $this->commands($this->commands);
    }
}

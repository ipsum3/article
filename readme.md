## Installation du package article de l'administration d'Ipsum

``` bash
# install the package
composer require ipsum3/article

# Run install
php artisan ipsum:article:install

# Optional publish views
php artisan vendor:publish --provider="Ipsum\Article\ArticleServiceProvider" --tag=views

```

### Add Article seeder to DatabaseSeeder.php file
`$this->call(ArticleTableSeeder::class);`

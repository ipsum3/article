<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('type', 20)->nullable();
            $table->string('etat')->default('publie');
            $table->integer('categorie_id')->nullable()->unsigned()->index();
            $table->string('titre');
            $table->string('nom')->nullable()->index();
            $table->text('extrait')->nullable();
            $table->longText('texte')->nullable();
            $table->dateTime('date')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->nullableTimestamps();
        });

        Schema::create('article_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable()->unsigned();
            $table->string('slug')->unique();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->integer('order')->nullable()->unsigned();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('articles');
        Schema::drop('article_categories');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCustomFieldsArticleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->text('custom_fields')->nullable()->after('texte');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}

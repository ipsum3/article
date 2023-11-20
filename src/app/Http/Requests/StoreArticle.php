<?php

namespace Ipsum\Article\app\Http\Requests;


use Ipsum\Admin\app\Http\Requests\FormRequest;
use Ipsum\Article\app\Models\Article;

class StoreArticle extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $types =  config('ipsum.article.types');
        $etats =  array_keys(config('ipsum.article.etats'));

        $rules = [];

        if( !request()->article ) {
            $article = new Article;
            $article->type = request()->type;
        } else {
            $article = request()->article;
        }

        foreach ($article->config['custom_fields'] as $field) {
            $rules['custom_fields.'.$field['name']] = $field['rules'];
        }

        return [
            "categorie_id" => "nullable|integer|exists:article_categories,id",
            'titre' => 'required|max:255',
            'extrait' => '',
            'texte' => '',
            'custom_fields' => '',
            'custom_blocs' => '',
            'seo_title' => '',
            'seo_description' => '',
            'slug' => '',
            'type' => 'required|in:'.implode(',', $types),
            'etat' => 'required|in:'.implode(',', $etats),
            'date' => 'date'.(!empty($article->config['publication']['has_etat']) ? '|required': '|nullable' )
        ] + $rules;
    }

}

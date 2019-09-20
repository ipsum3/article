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
        $types =  array_keys(Article::$types);
        $etats =  array_keys(Article::$etats);

        return [
            "categorie_id" => "nullable|integer|exists:article_categories,id",
            'titre' => 'required|max:255',
            'type' => 'in:'.implode(',', $types),
            'etat' => 'in:'.implode(',', $etats),
            'date' => 'date'
        ];
    }

}

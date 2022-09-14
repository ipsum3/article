<?php

namespace Ipsum\Article\app\Http\Requests;


use Ipsum\Admin\app\Http\Requests\FormRequest;

class StoreCategorie extends FormRequest
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
        return [
            'parent_id' => 'nullable|exists:article_categories,id,parent_id,NULL',
            'nom' => 'required|max:255',
            'description' => '',
            'type' => 'required|in:'. implode(",", config('ipsum.categorie.types') ),
            'seo_title' => '',
            'seo_description' => '',
            'slug' => '',
        ];
    }

}

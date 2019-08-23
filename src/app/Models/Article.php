<?php

namespace Ipsum\Article\app\Models;


use Ipsum\Core\app\Models\BaseModel;
use Ipsum\Core\Concerns\Slug;

class Article extends BaseModel
{
    use Slug;

    protected $table = 'articles';

    protected $fillable = ['titre', 'extrait', 'texte', 'seo_title', 'seo_description', 'type', 'categorie_id', 'etat', 'slug'];

    protected $slugBase = 'titre';
    
    
    static public $types = ['page' => 'Pages', 'post' => 'Post'];
    const TYPE_PAGE = 'page';
    const TYPE_POST = 'post';



    /*
     * Relations
     */

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }


    /*
     * Scopes
     */

    public function scopePosts($query)
    {
        return $query->where('type', self::TYPE_POST);
    }

    public function scopePages($query)
    {
        return $query->where('type', self::TYPE_PAGE);
    }
    
    
    
    /*
     * Accessors & Mutators
     */

    public function getTagTitleAttribute()
    {
        return $this->attributes['seo_title'] == '' ? $this->titre : $this->attributes['seo_title'];
    }
    
    public function getTagMetaDescriptionAttribute()
    {
        return $this->attributes['seo_description'] == '' ? $this->extrait : $this->attributes['seo_description'];
    }
    
    public function getTypeNomAttribute()
    {
        return isset($this->types[$this->type]) ? $this->types[$this->type] : null;
    }
    
    public function isPost()
    {
        return $this->type == self::TYPE_POST;
    }
    
    public function isPage()
    {
        return $this->type == self::TYPE_PAGE;
    }    
}

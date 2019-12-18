<?php

namespace Ipsum\Article\app\Models;


use Ipsum\Core\app\Models\BaseModel;
use Ipsum\Core\Concerns\Slug;
use Ipsum\Media\Concerns\Mediable;

class Article extends BaseModel
{
    use Slug, Mediable;

    // TODO check champs html

    protected $table = 'articles';

    protected $fillable = ['slug', 'type', 'etat', 'categorie_id', 'titre', 'extrait', 'texte', 'date', 'seo_title', 'seo_description'];

    protected $slugBase = 'titre';
    
    
    const TYPE_PAGE = 'page';
    const TYPE_POST = 'post';

    const ETAT_PUBLIE = 'publie';

    protected $dates = [
        'date',
    ];


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

    public function scopePublie($query)
    {
        return $query->where('etat', self::ETAT_PUBLIE);
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
    
    public function getIsPostAttribute()
    {
        return $this->type == self::TYPE_POST;
    }
    
    public function getIsPageAttribute()
    {
        return $this->type == self::TYPE_PAGE;
    }

    public function getIsPublieAttribute()
    {
        return $this->etat == self::ETAT_PUBLIE;
    }

    public function getEtatToStringAttribute()
    {
        return isset(config('ipsum.article.etats')[$this->etat]) ? config('ipsum.article.etats')[$this->etat] : null;
    }

    public function getNomAttribute()
    {
        return $this->attributes['nom'] === null ? $this->titre : $this->attributes['nom'];
    }

    public function getIsDeletableAttribute()
    {
        return $this->attributes['nom'] === null;
    }
}

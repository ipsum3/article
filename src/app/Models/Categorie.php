<?php

namespace Ipsum\Article\app\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Ipsum\Admin\Concerns\Htmlable;
use Ipsum\Article\Concerns\Sortable;
use Ipsum\Article\database\Factories\CategorieFactory;
use Ipsum\Core\app\Models\BaseModel;
use Ipsum\Core\Concerns\Slug;

class Categorie extends BaseModel
{
    use Slug, Sortable, Htmlable, HasFactory;

    protected $table = 'article_categories';

    protected $fillable = ['parent_id', 'slug', 'nom', 'description', 'order', 'seo_title', 'seo_description'];

    protected $slugBase = 'nom';

    protected $htmlable = ['description'];

    public $timestamps = false;


    protected static function newFactory()
    {
        return CategorieFactory::new();
    }
    


    /*
     * Relations
     */

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }



    /*
     * Functions
     */

    public function freres()
    {
        return $this->parent->children();
    }


    /*
     * Scopes
     */

    public function scopeRoot($query)
    {
        return $query->where('parent_id', null)->orderBy('order');
    }
    
    
    
    /*
     * Accessors & Mutators
     */

    public function getIsRootAttribute()
    {
        return $this->parent_id === null;
    }

    public function getTagTitleAttribute()
    {
        return $this->attributes['seo_title'] == '' ? $this->nom : $this->attributes['seo_title'];
    }
    
    public function getTagMetaDescriptionAttribute()
    {
        return $this->attributes['seo_description'] == '' ? $this->description : $this->attributes['seo_description'];
    }

}

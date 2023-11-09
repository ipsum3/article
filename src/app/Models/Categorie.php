<?php

namespace Ipsum\Article\app\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Ipsum\Admin\app\Casts\AsCustomFieldsObject;
use Ipsum\Admin\Concerns\Htmlable;
use Ipsum\Article\Concerns\Sortable;
use Ipsum\Article\database\factories\CategorieFactory;
use Ipsum\Core\app\Models\BaseModel;
use Ipsum\Core\Concerns\Slug;
use Ipsum\Core\Concerns\Translatable;

/**
 * Ipsum\Article\app\Models\Categorie
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $slug
 * @property string $nom
 * @property string $type
 * @property string|null $description
 * @property int|null $order
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property AsCustomFieldsObject $custom_fields
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Ipsum\Article\app\Models\Article> $articles
 * @property-read int|null $articles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Categorie> $children
 * @property-read int|null $children_count
 * @property-read array $config
 * @property-read mixed $is_root
 * @property-read mixed $tag_meta_description
 * @property-read mixed $tag_title
 * @property-read Categorie|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Ipsum\Core\app\Models\Translate> $translates
 * @property-read int|null $translates_count
 * @method static \Ipsum\Article\database\factories\CategorieFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Categorie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Categorie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Categorie query()
 * @method static \Illuminate\Database\Eloquent\Builder|Categorie root(string $type)
 * @mixin \Eloquent
 */
class Categorie extends BaseModel
{
    use Slug, Sortable, Htmlable, HasFactory, Translatable;

    protected $table = 'article_categories';

    protected $fillable = ['parent_id', 'slug', 'nom', 'description', 'type', 'order', 'custom_fields', 'seo_title', 'seo_description'];

    protected $slugBase = 'nom';

    protected $htmlable = ['description'];

    protected $translatable_attributes = ['nom', 'description'];

    public $timestamps = false;

    protected $casts = [
        'custom_fields' => AsCustomFieldsObject::class,
    ];


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

    public function scopeRoot($query, string $type)
    {
        return $query->where('parent_id', null)->where('type', $type)->orderBy('order');
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

    public function getConfigAttribute(): array {
        $config = [];
        foreach( config('ipsum.categorie.groupes') as $groupe ) {
            if( array_key_exists( 'categorie_types', $groupe['conditions'] ) && in_array( $this->type, $groupe['conditions']['categorie_types'] )
                || array_key_exists( 'categorie_ids', $groupe['conditions'] ) && in_array( $this->id, $groupe['conditions']['categorie_ids'] ) ) {
                $config = $groupe;
                break;
            }
        }
        return $config + config('ipsum.categorie.groupes.default');
    }
}

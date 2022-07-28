<?php

namespace Ipsum\Article\app\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Ipsum\Admin\Concerns\Htmlable;
use Ipsum\Article\database\factories\ArticleFactory;
use Ipsum\Core\app\Models\BaseModel;
use Ipsum\Core\Concerns\Slug;
use Ipsum\Media\Concerns\Mediable;

/**
 * Ipsum\Article\app\Models\Article
 *
 * @property int $id
 * @property string $slug
 * @property string|null $type
 * @property string $etat
 * @property int|null $categorie_id
 * @property int|null $media_id
 * @property string $titre
 * @property string|null $nom
 * @property string|null $extrait
 * @property string|null $texte
 * @property \Illuminate\Support\Carbon|null $date
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Ipsum\Article\app\Models\Categorie|null $categorie
 * @property-read mixed $etat_to_string
 * @property-read mixed $is_deletable
 * @property-read mixed $is_page
 * @property-read mixed $is_post
 * @property-read mixed $is_publie
 * @property-read mixed $tag_meta_description
 * @property-read mixed $tag_title
 * @property-read mixed $type_nom
 * @property-read \Ipsum\Media\app\Models\Media|null $illustration
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ipsum\Media\app\Models\Media[] $medias
 * @property-read int|null $medias_count
 * @method static \Ipsum\Article\database\factories\ArticleFactory factory(...$parameters)
 * @method static Builder|Article newModelQuery()
 * @method static Builder|Article newQuery()
 * @method static Builder|Article pages()
 * @method static Builder|Article posts()
 * @method static Builder|Article publie()
 * @method static Builder|Article query()
 * @mixin \Eloquent
 */
class Article extends BaseModel
{
    use Slug, Mediable, Htmlable, HasFactory;

    protected $table = 'articles';

    protected $fillable = ['slug', 'type', 'etat', 'categorie_id', 'titre', 'extrait', 'texte', 'date', 'seo_title', 'seo_description'];

    protected $slugBase = 'titre';

    protected $htmlable = ['extrait', 'texte'];
    
    
    const TYPE_PAGE = 'page';
    const TYPE_POST = 'post';

    const ETAT_PUBLIE = 'publie';

    protected $dates = [
        'date',
    ];


    protected static function newFactory()
    {
        return ArticleFactory::new();
    }


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

    public function scopePosts(Builder $query)
    {
        return $query->where('type', self::TYPE_POST);
    }

    public function scopePages(Builder $query)
    {
        return $query->where('type', self::TYPE_PAGE);
    }

    public function scopePublie(Builder $query)
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

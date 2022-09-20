<?php

namespace Ipsum\Article\app\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Ipsum\Admin\app\Casts\AsCustomFieldsObject;
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
 * @property string $titre
 * @property string|null $nom
 * @property string|null $extrait
 * @property string|null $texte
 * @property mixed|null $custom_fields
 * @property \Illuminate\Support\Carbon|null $date
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Ipsum\Article\app\Models\Categorie|null $categorie
 * @property-read array $config
 * @property-read mixed $etat_to_string
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

    protected $fillable = ['slug', 'type', 'etat', 'categorie_id', 'titre', 'extrait', 'texte', 'custom_fields', 'date', 'seo_title', 'seo_description'];

    protected $slugBase = 'titre';

    protected $htmlable = ['extrait', 'texte'];

    protected $casts = [
        'custom_fields' => AsCustomFieldsObject::class,
    ];

    const TYPE_PAGE = 'page';
    const TYPE_POST = 'post';
    const TYPE_RECETTE = 'recette';

    const ETAT_PUBLIE = 'publie';

    protected $dates = [
        'date',
    ];


    protected static function newFactory()
    {
        return ArticleFactory::new();
    }


    /*
     * Functions
     */

    protected function htmlableAttributes() {
        $htmlable = $this->htmlable;
        foreach( $this->config['custom_fields'] as $field ) {
            if( in_array( $field['type'], ['html', 'html-simple'] ) ) {
                $htmlable[] = $field['name'];
            }
        }
        return $htmlable;
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
        return empty( $this->attributes['nom'] ) ? $this->titre : $this->attributes['nom'];
    }

    public function getConfigAttribute(): array {
        $config = [];
        foreach( config('ipsum.article.groupes') as $groupe ) {
            if( array_key_exists( 'article_types', $groupe['conditions'] ) && in_array( $this->type, $groupe['conditions']['article_types'] )
                || array_key_exists( 'article_noms', $groupe['conditions'] ) && in_array( $this->nom, $groupe['conditions']['article_noms'] ) ) {
                $config = $groupe;
                break;
            }
        }
        return $config + config('ipsum.article.groupes.default');
    }
}

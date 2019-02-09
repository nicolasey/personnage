<?php
namespace Nicolasey\Personnage\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Personnage extends Model implements HasMedia
{
    use SoftDeletes, HasSlug, HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['alive'];

    protected $hidden = ["deleted_at", "owner"];

    public static $rules = [
        "name" => "unique:personnages|min:3|required",
        "owner" => "required",
    ];

    /**
     * Get the options for generating the slug.
     * 
     * @return mixed
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * User this personnage is owned by
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(config("personnages.owner.class"), "owner");
    }

    /**
     * Filter staff from casual personnages
     *
     * @param $query
     * @param boolean $bool
     * @return mixed
     */
    public function scopeStaff($query, $bool)
    {
        return $query->where('isStaff', $bool);
    }

    /**
     * Filter active personnages
     *
     * @param $query
     * @param boolean $bool
     * @return mixed
     */
    public function scopeActive($query, $bool)
    {
        return $query->where('active', $bool);
    }

    /**
     * Select by owner
     *
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeOf($query, $id)
    {
        return $query->where('owner', $id);
    }
}
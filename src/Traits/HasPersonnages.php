<?php
namespace Nicolasey\Personnage\Traits;

trait HasPersonnages
{
    /**
     * Model's personnages
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function personnages()
    {
        return $this->hasMany(Personnage::class, "owner_id");
    }
}
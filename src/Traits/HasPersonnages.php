<?php
namespace Nicolasey\Personnages\Traits;

use Nicolasey\Personnages\Models\Personnage;

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
<?php
namespace Nicolasey\PErsonnages\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolasey\Personnage\Traits\HasPersonnages;
use Nicolasey\Personnages\Models\Personnage;

class User extends Model
{
    use SoftDeletes, HasPersonnages;

    protected $guarded = [];

    public function personnages()
    {
        return $this->hasMany(Personnage::class);
    }
}
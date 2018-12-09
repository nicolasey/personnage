<?php
namespace Nicolasey\PErsonnages\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolasey\Personnages\Models\Personnage;

class User extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function personnages()
    {
        return $this->hasMany(Personnage::class);
    }
}
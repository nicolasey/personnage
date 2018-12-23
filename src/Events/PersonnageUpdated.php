<?php
namespace Nicolasey\Personnages\Events;

use Nicolasey\Personnages\Models\Personnage;

class PersonnageUpdated extends Event
{
    protected $name;
    public $personnage;

    public function __construct(Personnage $personnage)
    {
        $this->personnage = $personnage;
        $this->name = "personnage.".$personnage->id.".updated";
    }

    public function broadcastOn()
    {

    }
}
<?php
namespace Nicolasey\Personnages\Events;

use Nicolasey\Personnages\Models\Personnage;

class PersonnageCreated extends Event
{
    protected $name;
    public $personnage;

    public function __construct(Personnage $personnage)
    {
        $this->personnage = $personnage;
        $this->name = "personnage.".$personnage->id.".created";
    }

    public function broadcastOn()
    {

    }
}
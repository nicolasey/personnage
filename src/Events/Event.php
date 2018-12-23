<?php
namespace Nicolasey\Personnages\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class Event implements ShouldBroadcast
{
    protected $name;
    
    use SerializesModels, InteractsWithSockets;

    public function broadcastAs()
    {
        return $this->name;
    }

    public function broadcastOn()
    {
        // must be extended by real events
    }
}
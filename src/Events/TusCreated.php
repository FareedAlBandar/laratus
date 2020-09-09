<?php

namespace Laratus\Events;

use TusPhp\Events\TusEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TusCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event;
    /**
     * Create a new event instance.
     *
     * @param TusEvent
     * @return void
     */
    public function __construct(TusEvent $tusEvent)
    {
        $this->tusEvent = $tusEvent;
    }
}

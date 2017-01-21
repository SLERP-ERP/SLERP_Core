<?php

namespace App\Events\Api\Room;

use App\Http\Models\API\Room;
use Illuminate\Broadcasting\Channel;
use App\Events\Event;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class RoomRestored extends Event
{


    public function __construct(Room $room)
    {
        if (auth()->user()) {

            Log::info('Room Restored:', [
                'id' => $room->id,
                'code' => $room->code,
                'label' => $room->label
            ]);

            $room->campus;

            $data_to_secure = json_encode([
                'data' => $room->toArray(),
                'conf' => [
                    'ldap' => ldap_config()
                ]
            ]);

            $secure_data = encrypt_broadcast_data($data_to_secure);

            $message = [
                'event' => 'restored',
                'type' => 'room',
                'encrypted' => $secure_data
            ];

            Redis::publish('events', json_encode($message));

            history()->log(
                'Room',
                'restored a room: ' . $room->label() . '.',
                $room->id,
                'building-o',
                'bg-lime'
            );
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
//    public function broadcastOn()
//    {
//        return new PrivateChannel('account-events');
//    }
}
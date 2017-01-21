<?php
/**
 * Created by PhpStorm.
 * User: melon
 * Date: 1/16/17
 * Time: 4:37 PM
 */

namespace App\Events\Api\Course;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Support\Facades\Log;
use App\Events\Event;

class CoursesViewed extends Event
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $courseIds;

    /**
     * CoursesViewed constructor.
     * @param array $courseIds
     */
    public function __construct($courseIds = [])
    {
        $user_name = 'System';

        if ($user = auth()->user()) {

            $user_name = $user->name;

            history()->log(
                'Course',
                'viewed ' . count($courseIds) . ' courses',
                $user->id,
                'graduation-cap',
                'bg-aqua'
            );

        }

        Log::info($user_name . ' viewed ' . count($courseIds) . ' courses', $courseIds);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     *
     * public function broadcastOn()
     * {
     * return new PrivateChannel('channel-name');
     * }
     */
}
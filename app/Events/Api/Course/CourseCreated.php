<?php

namespace App\Events\Api\Course;

use App\Events\Api\ApiRequestEvent;
use App\Http\Models\API\Course;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Krucas\Settings\Facades\Settings;

class CourseCreated extends ApiRequestEvent
{
    /**
     * CourseCreated constructor.
     * @param Course $course
     */
    public function __construct(Course $course)
    {
        parent::__construct();

        $logMessage = 'created course - ';
        $logContext = [
            'action' => 'create',
            'model' => 'course',
            'course_id' => $course->id,
            'course_code' => $course->code,
            'course_label' => $course->label,
            'course_department_id' => $course->department->id,
            'course_department_code' => $course->department->code,
            'course_department_label' => $course->department->label,
            'course_created' => $course->created_at,
            'course_updated' => $course->updated_at,
            'requester_id' => 0,
            'requester_name' => 'System',
            'requester_ip' => getRequestIP(),
            'request_proxy_ip' => getRequestIP(true),
            'request_method' => \Request::getMethod(),
            'request_url' => \Request::fullUrl(),
            'request_uri' => \Request::getRequestUri(),
            'request_scheme' => \Request::getScheme(),
            'request_host' => \Request::getHost()
        ];

        if ($user = auth()->user()) {

            $logMessage = auth()->user()->name . ' ' . $logMessage;
            $logContext['requester_id'] = auth()->user()->id;
            $logContext['requester_name'] = auth()->user()->name;

            if (Settings::get('broadcast-events', false)) {

                $course->department;

                $data_to_secure = json_encode([
                    'data' => $course->toArray(),
                    'conf' => [
                        'ldap' => ldap_config()
                    ]
                ]);

                $secure_data = encrypt_broadcast_data($data_to_secure);

                $message = [
                    'event' => 'created',
                    'type' => 'course',
                    'encrypted' => $secure_data
                ];

                Redis::publish('events', json_encode($message));
            }

            history()->log(
                'Course',
                'created a new course: ' . $course->label,
                $course->id,
                'graduation-cap',
                'bg-green'
            );
        }

        Log::info($logMessage, $logContext);
    }
}

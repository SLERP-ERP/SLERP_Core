<?php

namespace App\Events\Api\Department;

use App\Events\Api\ApiRequestEvent;
use App\Http\Models\API\Department;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Krucas\Settings\Facades\Settings;

class DepartmentCreated extends ApiRequestEvent
{
    /**
     * DepartmentCreated constructor.
     * @param Department $department
     */
    public function __construct(Department $department)
    {
        parent::__construct();

        $logMessage = 'created department - ';
        $logContext = [
            'action' => 'create',
            'model' => 'department',
            'department_id' => $department->id,
            'department_code' => $department->code,
            'department_label' => $department->label,
            'department_created' => $department->created_at,
            'department_updated' => $department->updated_at,
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

                $data_to_secure = json_encode([
                    'data' => $department->toArray(),
                    'conf' => [
                        'ldap' => ldap_config()
                    ]
                ]);

                $secure_data = encrypt_broadcast_data($data_to_secure);

                $message = [
                    'event' => 'created',
                    'type' => 'department',
                    'encrypted' => $secure_data
                ];

                Redis::publish('events', json_encode($message));
            }

            history()->log(
                'Department',
                'created a new department: ' . $department->label,
                $department->id,
                'cubes',
                'bg-green'
            );
        }

        Log::info($logMessage, $logContext);
    }
}

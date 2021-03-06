<?php

namespace App\Events\Api\Building;

use App\Events\Api\ApiRequestEvent;
use App\Http\Models\API\Building;
use App\Http\Models\API\Campus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Krucas\Settings\Facades\Settings;


class AssignedCampus extends ApiRequestEvent
{

    /**
     * AssignedCampus constructor.
     * @param Building $building
     * @param Campus $campus
     */
    public function __construct(Building $building, Campus $campus)
    {
        parent::__construct();

        $logMessage = 'assigned building to campus - ';
        $logContext = [
            'action' => 'assignment',
            'model' => 'building',
            'pivot' => 'campus',
            'building_id' => $building->id,
            'building_code' => $building->code,
            'building_label' => $building->label,
            'building_created' => $building->created_at,
            'building_updated' => $building->updated_at,
            'campus_id' => $campus->id,
            'campus_code' => $campus->code,
            'campus_label' => $campus->label,
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
                    'data' => [
                        'building' => $building->toArray(),
                        'campus' => $campus->toArray()
                    ],
                    'conf' => [
                        'ldap' => ldap_config()
                    ]
                ]);

                $secure_data = encrypt_broadcast_data($data_to_secure);

                $message = [
                    'event' => 'assigned',
                    'type' => 'campus',
                    'to' => 'building',
                    'encrypted' => $secure_data
                ];

                Redis::publish('events', json_encode($message));
            }

            history()->log(
                'Assignment',
                'assigned ' . $building->label . ' to campus: "' . $campus->label . '"',
                $building->id,
                'university',
                'bg-olive'
            );
        }

        Log::info($logMessage, $logContext);
    }
}

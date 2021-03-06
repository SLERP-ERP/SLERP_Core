<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\Auth\Auth;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Log;

/**
 * @SWG\Swagger(
 *   schemes={"http"},
 *   host="localhost:8000",
 *   basePath="/api/v1",
 *  @SWG\Info(
 *     title="ORM Core",
 *     version="1.0.0",
 *     description="An API that houses ERP data, allowing open development around institutional data.",
 *     @SWG\Contact(
 *          email="markea125@gmail.com",
 *          name="Alex Markessinis",
 *          url="https://github.com/OpenResourceManager/Core"
 *     ),
 *     @SWG\License(
 *          name="MIT",
 *          url="https://opensource.org/licenses/MIT"
 *     )
 *   )
 *  )
 * )
 *
 */
class ApiController extends Controller
{
    /**
     * ApiController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {

        $user = auth()->user();

        if (config('api.debug', false)) {
            $logMessage = 'received API request  - ';
            $logContext = [
                'action' => 'request',
                'model' => 'request',
                'requester_id' => 0,
                'requester_name' => 'System',
                'requester_ip' => getRequestIP(),
                'request_proxy_ip' => getRequestIP(true),
                'request_method' => \Request::getMethod(),
                'request_url' => \Request::fullUrl(),
                'request_uri' => \Request::getRequestUri(),
                'request_scheme' => \Request::getScheme(),
                'request_host' => \Request::getHost(),
                'request_input' => $request->input(),
                'request_all' => $request->all()
            ];
            if ($user) {
                $logMessage = auth()->user()->name . ' ' . $logMessage;
                $logContext['requester_id'] = $user->id;
                $logContext['requester_name'] = $user->name;
            }
            Log::debug($logMessage, $logContext);
        }
    }

    use Helpers;

    /**
     * A name describing the model that this controller relates to.
     * Makes it easier to dynamically provide contextual errors.
     *
     * @var string
     */
    protected $noun = 'resource';

    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @var bool
     */
    protected $successStatus = true;

    /**
     * @var int
     */
    protected $resultLimit = 50;


    /**
     * @var int
     */
    protected $maxResultLimit = 1500;


    /**
     * @param $amount
     * @return $this
     */
    public function resultLimit($amount)
    {
        $this->resultLimit = is_null($amount) ? $this->resultLimit : $amount;
        $this->resultLimit = $this->resultLimit > $this->maxResultLimit ? $this->maxResultLimit : $this->resultLimit;
        return $this;
    }

    /**
     * @param $amount
     * @return $this
     */
    public function maxResultLimit($amount)
    {
        $this->maxResultLimit = $amount;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getSuccessStatus()
    {
        return $this->successStatus;
    }

    /**
     * @param $successStatus
     * @return $this
     */
    public function setSuccessStatus($successStatus)
    {
        $this->successStatus = $successStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return json_encode([
            'message' => 'ok',
            'status_code' => $this->getStatusCode()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroySuccessResponse()
    {
        return $this->response->noContent()->setStatusCode(204);
    }

    /**
     * @param string $resource
     */
    public function destroyFailure($resource = 'resource')
    {
        throw new \Dingo\Api\Exception\DeleteResourceFailedException('Could not delete ' . $resource . '.');
    }

    /**
     *
     */
    public function notFound()
    {
        throw new NotFoundHttpException("Unable to find the requested resource: " . $this->noun);
    }

    /**
     * @param string $resource1
     * @param string $resource2
     */
    public function detachFailure($resource1 = 'resource', $resource2 = 'resource')
    {
        throw new \Dingo\Api\Exception\DeleteResourceFailedException('Could not detach ' . $resource1 . ' from ' . $resource2 . '.');
    }

    /**
     * @param string $resource1
     * @param string $resource2
     */
    public function assignFailure($resource1 = 'resource', $resource2 = 'resource')
    {
        throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not assign ' . $resource1 . ' to ' . $resource2 . '.');
    }
}

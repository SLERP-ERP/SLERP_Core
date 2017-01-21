<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use ReflectionClass;

class TokenVerificationController extends ApiController
{

    /**
     * TokenVerificationController constructor.
     */
    public function __construct()
    {
        $this->noun = 'token';
    }

    /**
     * @param Request $request
     * @param String $token
     * @return \Dingo\Api\Http\Response|void
     */
    public function verify(Request $request, $token = null)
    {

        if ($request->isMethod('post') && empty($token)) {
            $data = $request->all();
            $token = $data['token'];
        }

        $verified = verifyToken($token);

        if ($verified) {
            $reflection = new ReflectionClass(get_class($verified));
            $type = strtolower($reflection->getShortName());

            $response['id'] = $verified->id;

            switch ($type) {
                case 'email':
                    $route = route('api.emails.show', ['id' => $verified->id]);
                    $response['type'] = 'email';
                    $response['message'] = 'Email has been verified successfully.';
                    break;
                case 'mobilephone':
                    $route = route('api.mobile-phones.show', ['id' => $verified->id]);
                    $response['type'] = 'mobile-phone';
                    $response['message'] = 'Mobile phone has been verified successfully.';
                    break;
                default:
                    return $this->response->accepted();
                    break;
            }

            return $this->response->accepted($route, $response);
        } else {
            return $this->response->errorNotFound('Token Not Found');
        }
    }

}
<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Events\Frontend\Auth\UserRegistered;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\Frontend\Auth\RegisterRequest;
use App\Repositories\Frontend\Access\User\UserRepository;
use Krucas\Settings\Facades\Settings;

/**
 * Class RegisterController
 * @package App\Http\Controllers\Frontend\Auth
 */
class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * @var UserRepository
     */
    protected $user;

    /**
     * RegisterController constructor.
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        // Where to redirect users after registering
        $this->redirectTo = route('frontend.index');

        $this->user = $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        if (Settings::get('enable-registration', false)) {
            return view('frontend.auth.register');
        } else {
            return redirect('/');
        }
    }

    /**
     * @param RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(RegisterRequest $request)
    {
        if (Settings::get('enable-registration', false)) {
            if (config('access.users.confirm_email')) {
                $user = $this->user->create($request->all());
                event(new UserRegistered($user));
                return redirect($this->redirectPath())->withFlashSuccess(trans('exceptions.frontend.auth.confirmation.created_confirm'));
            } else {
                auth()->login($this->user->create($request->all()));
                event(new UserRegistered(access()->user()));
                return redirect($this->redirectPath());
            }
        } else {
            return redirect('/');
        }
    }
}
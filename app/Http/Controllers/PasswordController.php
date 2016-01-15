<?php

namespace App\Http\Controllers;

use App\Model\Password;
use App\Model\User;
use App\UUD\Transformers\PasswordTransformer;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class PasswordController extends ApiController
{

    /**
     * @var PasswordTransformer
     */
    protected $passwordTransformer;

    /**
     * PasswordController constructor.
     * @param PasswordTransformer $passwordTransformer
     */
    function __construct(PasswordTransformer $passwordTransformer)
    {
        $this->passwordTransformer = $passwordTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$this->isAuthorized($request)) return $this->respondNotAuthorized();
        if (!$this->canManagePassword($request)) return $this->respondNotAuthorized();
        parent::index($request);
        $result = Password::paginate($this->limit);
        return $this->respondSuccessWithPagination($request, $result, $this->passwordTransformer->transformCollection($result->all()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$this->isAuthorized($request)) return $this->respondNotAuthorized();
        if (!$this->canManagePassword($request)) return $this->respondNotAuthorized();
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$this->isAuthorized($request)) return $this->respondNotAuthorized();
        if (!$this->canManagePassword($request)) return $this->respondNotAuthorized();
        $validator = Validator::make($request->all(), [
            'user_id' => 'integer|required|exists:users,id,deleted_at,NULL',
            'password' => 'string|required',

        ]);
        if ($validator->fails()) return $this->respondUnprocessableEntity($validator->errors()->all());
        $item = Password::updateOrCreate(['user_id' => Input::get('user_id')], ['user_id' => Input::get('user_id'), 'password' => Crypt::encrypt(Input::get('password'))]);
        return $this->respondCreateUpdateSuccess($id = $item->id, $item->wasRecentlyCreated);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!$this->isAuthorized($request)) return $this->respondNotAuthorized();
        if (!$this->canManagePassword($request)) return $this->respondNotAuthorized();
        $result = Password::findOrFail($id);
        return $this->respondWithSuccess($this->passwordTransformer->transform($result));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!$this->isAuthorized($request)) return $this->respondNotAuthorized();
        if (!$this->canManagePassword($request)) return $this->respondNotAuthorized();
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$this->isAuthorized($request)) return $this->respondNotAuthorized();
        if (!$this->canManagePassword($request)) return $this->respondNotAuthorized();
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (!$this->isAuthorized($request)) return $this->respondNotAuthorized();
        if (!$this->canManagePassword($request)) return $this->respondNotAuthorized();
        Password::findOrFail($id)->delete();
        return $this->respondDestroySuccess();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function userPasswords($id, Request $request)
    {
        if (!$this->isAuthorized($request)) return $this->respondNotAuthorized();
        if (!$this->canManagePassword($request)) return $this->respondNotAuthorized();
        parent::index($request);
        $result = User::findOrFail($id)->password()->paginate($this->limit);
        return $this->respondSuccessWithPagination($request, $result, $this->passwordTransformer->transformCollection($result->all()));
    }

    /**
     * @param $user_id
     * @param Request $request
     * @return mixed
     */
    public function userPasswordsByUserId($user_id, Request $request)
    {
        if (!$this->isAuthorized($request)) return $this->respondNotAuthorized();
        if (!$this->canManagePassword($request)) return $this->respondNotAuthorized();
        parent::index($request);
        $result = User::where('user_identifier', $user_id)->firstOrFail()->password()->paginate($this->limit);
        return $this->respondSuccessWithPagination($request, $result, $this->passwordTransformer->transformCollection($result->all()));
    }

    /**
     * @param $username
     * @param Request $request
     * @return mixed
     */
    public function userPasswordsByUsername($username, Request $request)
    {
        if (!$this->isAuthorized($request)) return $this->respondNotAuthorized();
        if (!$this->canManagePassword($request)) return $this->respondNotAuthorized();
        parent::index($request);
        $result = User::where('username', $username)->firstOrFail()->password()->paginate($this->limit);
        return $this->respondSuccessWithPagination($request, $result, $this->passwordTransformer->transformCollection($result->all()));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function storeUserPasswordByUserId(Request $request)
    {
        if (!$this->isAuthorized($request)) return $this->respondNotAuthorized();
        if (!$this->canManagePassword($request)) return $this->respondNotAuthorized();
        $validator = Validator::make($request->all(), [
            'user_id' => 'string|required|exists:users,user_identifier,deleted_at,NULL',
            'password' => 'integer|required'
        ]);
        if ($validator->fails()) return $this->respondUnprocessableEntity($validator->errors()->all());
        $user = User::where('user_identifier', $request->input('user_id'))->firstOrFail();
        $item = Password::updateOrCreate(['user_id' => $user->id], ['user_id' => $user->id, 'password' => Crypt::encrypt(Input::get('password'))]);
        return $this->respondCreateUpdateSuccess($id = $item->id, $item->wasRecentlyCreated);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function storeUserPasswordByUsername(Request $request)
    {
        if (!$this->isAuthorized($request)) return $this->respondNotAuthorized();
        if (!$this->canManagePassword($request)) return $this->respondNotAuthorized();
        $validator = Validator::make($request->all(), [
            'username' => 'string|required|exists:users,username,deleted_at,NULL',
            'password' => 'integer|required'
        ]);
        if ($validator->fails()) return $this->respondUnprocessableEntity($validator->errors()->all());
        $user = User::where('username', $request->input('username'))->firstOrFail();
        $item = Password::updateOrCreate(['user_id' => $user->id], ['user_id' => $user->id, 'password' => Crypt::encrypt(Input::get('password'))]);
        return $this->respondCreateUpdateSuccess($id = $item->id, $item->wasRecentlyCreated);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function deleteUserPassword(Request $request)
    {
        if (!$this->isAuthorized($request)) return $this->respondNotAuthorized();
        if (!$this->canManagePassword($request)) return $this->respondNotAuthorized();
        $validator = Validator::make($request->all(), [
            'user' => 'integer|required|exists:users,id,deleted_at,NULL'
        ]);
        if ($validator->fails()) return $this->respondUnprocessableEntity($validator->errors()->all());
        $user = User::findOrFail($request->input('user'));
        Password::where('user_id', $user->id)->firstOrFail()->delete();
        return $this->respondDestroySuccess();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function deleteUserPasswordByUserId(Request $request)
    {
        if (!$this->isAuthorized($request)) return $this->respondNotAuthorized();
        if (!$this->canManagePassword($request)) return $this->respondNotAuthorized();
        $validator = Validator::make($request->all(), [
            'user_id' => 'string|required|exists:users,user_identifier,deleted_at,NULL'
        ]);
        if ($validator->fails()) return $this->respondUnprocessableEntity($validator->errors()->all());
        $user = User::where('user_identifier', $request->input('user_id'))->firstOrFail();
        Password::where('user_id', $user->id)->firstOrFail()->delete();
        return $this->respondDestroySuccess();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function deleteUserPasswordByUsername(Request $request)
    {
        if (!$this->isAuthorized($request)) return $this->respondNotAuthorized();
        if (!$this->canManagePassword($request)) return $this->respondNotAuthorized();
        $validator = Validator::make($request->all(), [
            'username' => 'string|required|exists:users,username,deleted_at,NULL'
        ]);
        if ($validator->fails()) return $this->respondUnprocessableEntity($validator->errors()->all());
        $user = User::where('username', $request->input('username'))->firstOrFail();
        Password::where('user_id', $user->id)->firstOrFail()->delete();
        return $this->respondDestroySuccess();
    }
}
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Model\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return UserResource::collection(User::get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        Auth::user()->isAdmin() ? null : abort(403);
        $data = $request->all();

        $user = new User();
        $user->fill($data);
        $user->password = \Illuminate\Support\Facades\Hash::make($data['password']);
        $user->email_verified_at = now();
        $user->roles = $data['roles'];
        $user->api_token = Str::random(80);
        $user->save();
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        Auth::user()->isAdmin() ? null : abort(403);
        $data = $request->all();

        $user->fill($data);
        isset($data['password']) && !empty($data['password'])
            ? $user->password = \Illuminate\Support\Facades\Hash::make($data['password'])
            : null;
        $user->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        Auth::user()->isAdmin() ? null : abort(403);
        $user->delete();
    }

    public function getRolesList()
    {
        return new JsonResponse([
            ['id' => 1, 'name' => 'Администратор', 'value' => User::ROLE_ADMIN],
            ['id' => 2, 'name' => 'Пользователь', 'value' => User::ROLE_USER],
        ]);
    }
}

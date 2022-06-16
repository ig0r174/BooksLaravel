<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class AuthController extends Controller {

    use ApiResponse;

    /**
     * @OA\Get(
     *     path="/api/v1/user/",
     *     description="Get one user",
     *     security={{"bearer_token":{}}},
     *     tags={"Users"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=false,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                   @OA\Schema(
     *                         @OA\Property(property="success", type="boolean", example=true),
     *                         @OA\Property(property="message", type="string", example="OK"),
     *                         @OA\Property(property="data", ref="#/components/schemas/User"),
     *                   ),
     *              ),
     *         },
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized.",
     *     ),
     *      @OA\Response(
     *         response="404",
     *         description="Not found",
     *     ),
     *     @OA\SecurityScheme(
     *         scheme="bearerAuth",
     *     ),
     * )
     */
    public function getCurrentUser(Request $req)
    {
        return $this->sendResponse(auth()->user(), 'OK', 200);
    }

    public function getAnotherUser($id, Request $req)
    {
        $user = User::find($id);
        if(!$user)
            return $this->sendError('Not found', 404);
        return $this->sendResponse($user, 'OK', 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/register",
     *     description="Register a new user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         in="query",
     *         name="name",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="email",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="password",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                   @OA\Schema(
     *                         @OA\Property(property="success", type="boolean", example=true),
     *                         @OA\Property(property="message", type="string", example="Created"),
     *                         @OA\Property(property="data", ref="#/components/schemas/User"),
     *                         @OA\Property(property="token", type="string", example="7|aI87lhNyFBrWX73elXpYpDbzQ38YciDmN..."),
     *                   ),
     *              ),
     *         },
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflict",
     *     ),
     * )
     */
    public function register(Request $req)
    {
        $validFields = $req->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);

        if(User::where('email', '=', $validFields['email'])->first())
            return $this->sendError('Conflict', 409, ['User with such email already exists']);

        $user = User::create($validFields);

        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = [
            "user" => $user,
            "token" => $token,
        ];
        return $this->sendResponse($response, 'Created', 201);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/login",
     *     description="Log in ",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         in="query",
     *         name="email",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="password",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                   @OA\Schema(
     *                         @OA\Property(property="success", type="boolean", example=true),
     *                         @OA\Property(property="message", type="string", example="OK"),
     *                         @OA\Property(property="data", ref="#/components/schemas/User"),
     *                         @OA\Property(property="token", type="string", example="7|aI87lhNyFBrWX73elXpYpDbzQ38YciDmN..."),
     *                   ),
     *              ),
     *         },
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad credentials",
     *     ),
     * )
     */
    public function login(Request $req) {
        $fields = $req->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', '=', $fields['email'])->first();
        if(!$user or !Hash::check($fields['password'], $user->password))
            return $this->sendError('Bad credentials', 400);
        $token = $user->createToken($user->email)->plainTextToken;
        $response = [
            "user" => $user,
            "token" => $token,
        ];
        return $this->sendResponse($response, 'OK', 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/logout",
     *     description="Log out ",
     *     security={{"bearer_token":{}}},
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                   @OA\Schema(
     *                         @OA\Property(property="success", type="boolean", example=true),
     *                         @OA\Property(property="message", type="string", example="OK"),
     *                   ),
     *              ),
     *         },
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized.",
     *     ),
     *     @OA\SecurityScheme(
     *         scheme="bearerAuth",
     *     ),
     * )
     */
    public function logout(Request $req) {
        auth()->user()->tokens()->delete();
        return $this->sendEmptyResponse('OK', 200);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/user/edit",
     *     description="Edit user data ",
     *     security={{"bearer_token":{}}},
     *     tags={"Users"},
     *     @OA\Parameter(
     *         in="query",
     *         name="name",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="email",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="password",
     *         required=false,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                   @OA\Schema(
     *                         @OA\Property(property="success", type="boolean", example=true),
     *                         @OA\Property(property="message", type="string", example="OK"),
     *                         @OA\Property(property="data", ref="#/components/schemas/User"),
     *                   ),
     *              ),
     *         },
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized.",
     *     ),
     *     @OA\SecurityScheme(
     *         scheme="bearerAuth",
     *     ),
     * )
     */
    public function edit(Request $req) {
        $user = auth()->user();
        if($user->name != $req->input('name') and $req->input('name') != null)
            $user->name = $req->input('name');
        if($user->email != $req->input('email') and $req->input('email') != null)
            $user->email = $req->input('email');
        if(!Hash::check($req->input('password'), $user->password) and $req->input('password') != null)
            $user->password = Hash::make($req->input('password'));
        $user->save();
        return $this->sendResponse($user, 'OK', 200);
    }

}

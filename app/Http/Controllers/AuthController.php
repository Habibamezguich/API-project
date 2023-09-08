<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\BabysitterSignUpRequest;
use App\Models\User;
use App\Models\Babysitter;




class AuthController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup', 'babysitterlogin', 'loginbb']]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$userToken = auth()->guard('user')->attempt($credentials)) {
            return response()->json(['error' => 'adresse e-mail ou mot de passe est incorrect. Veuillez réessayer'], 401);
        }

        return $this->respondWithToken($userToken);
    }

    public function loginbb()
    {
        $credentials = request(['email', 'password']);

        if (!$babysitterToken = auth()->guard('babysitter')->attempt($credentials)) {
            return response()->json(['error' => 'adresse e-mail ou mot de passe est incorrect. Veuillez réessayer'], 401);
        }

        return $this->respondWithToken($babysitterToken);
    }

    public function signup(SignUpRequest $request)
    {
        try {
            $user = User::create($request->all());
            return $this->login($request);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function babysitterlogin(BabysitterSignUpRequest $request)
    {
        try {
            $bbsitter = Babysitter::create($request->all());

            // Create a new token for the newly registered babysitter
            $token = auth()->login($bbsitter);

            // Respond with the access token
            return $this->respondWithToken($token);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60

        ]);
    }
}

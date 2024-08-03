<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function auth(): JsonResponse
    {

        return response()->json([
            'redirect_url' => Socialite::driver('keycloak')->redirect()->getTargetUrl()
        ]);
    }

    public function callback()
    {
        $callbackUser = Socialite::driver('keycloak')->user();

        $user = User::where('email', $callbackUser->getEmail())->first();
        if(!$user) {
            $user = new User();
            $user->email = $callbackUser->getEmail();
        }
        //always update user data (except email)
        $user->name = $callbackUser->getName();
        $user->username = $callbackUser->getNickname();
        $user->last_login = Carbon::now()->toDateTimeString();
        $user->save();

        $token = Auth::login($user);

        return $this->respondWithToken($user, $token);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken(User $user, $token)
    {
        return response()->json([
            'access_token' => $token,
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'last_login' => $user->last_login,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class AuthenticateApi extends Middleware
{
    protected function authenticate($request, array $guards)
    {
        $token = $request->bearerToken();

        $user = User::where('api_token', '=', $token)->first();
        if(!$user)
            $this->unauthenticated($request, $guards);
        $date = strtotime($user->updated_at);
        $date = strtotime("+7 day", $date);
        $now = strtotime("now");

        if($user and $now < $date)
            return;
        $this->unauthenticated($request, $guards);
    }
}

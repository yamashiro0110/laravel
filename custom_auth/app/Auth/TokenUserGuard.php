<?php

namespace App\Auth;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;

class TokenUserGuard extends SessionGuard
{
    public function __construct($name, Session $session, Request $request = null)
    {
        $provider = new TokenUserProvider();
        parent::__construct($name, $provider, $session, $request);
    }

    public function user()
    {
        $user = parent::user();
        Log::debug("ユーザーを取得", ['user', $user]);

        if ($user) {
            Log::debug("ログイン済みのユーザー", ['user', $user]);
            return $user;
        } else {
            Log::debug("ログイン済みでないユーザー");
        }

        $token = $this->getRequest()->get('token');
        Log::debug("リクエストからTokenを取得", ['token', $token]);

        if ($token) {
            $user = $this->getProvider()->retrieveByCredentials(array($token));
            $this->login($user, true);
            Log::debug("ユーザーのログイン成功", ['user', $user]);
            return $this->getUser();
        }

        Log::debug("Tokenの指定無し", ['request', $this->getRequest()]);
        return $this->getUser();
    }

}

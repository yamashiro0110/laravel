<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TokenUserProvider implements UserProvider
{

    public function __construct()
    {
    }

    public function checkToken($token)
    {
        Log::debug("Tokenチェック開始", ['token', $token]);

        $enableTokens = array('123','abc','xyz');

        if (in_array($token, $enableTokens)) {
            return new TokenUser($token);
        }

        return null;
    }

    public function retrieveById($identifier)
    {
        return $this->checkToken($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        return $this->checkToken($identifier);
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
    }

    public function retrieveByCredentials(array $credentials)
    {
        foreach ($credentials as $token) {
            $user = $this->checkToken($token);

            if (!is_null($user)) {
                return $user;
            }
        }

        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return empty($credentials);
    }

}

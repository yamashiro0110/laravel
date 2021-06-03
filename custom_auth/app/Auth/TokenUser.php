<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;

class TokenUser implements Authenticatable
{
    // private $id;
    private $token;
    private $rememberMe;

    public function __construct($token)
    {
        // $this->id = $id;
        $this->token = $token;
        $this->rememberMe = Str::random(10);
    }

    public function getAuthIdentifierName()
    {
        return $this->token;
    }

    public function getAuthIdentifier()
    {
        return $this->token;
    }

    public function getAuthPassword()
    {
        return $this->token;
    }

    public function getRememberToken()
    {
        return $this->rememberMe;
    }

    public function setRememberToken($value)
    {
        $this->rememberMe = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_me';
    }

}

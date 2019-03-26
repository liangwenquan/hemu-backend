<?php

namespace App\Ship\Auth;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class TokenInCookieGuard implements Guard
{
    use GuardHelpers;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The name of the cookie key from the request containing the API token.
     *
     * @var string
     */
    protected $cookieKey;

    /**
     * The name of the token "column" in persistent storage.
     *
     * @var string
     */
    protected $storageKey;

    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider $provider
     * @param  \Illuminate\Http\Request $request
     */
    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
        $this->cookieKey = 'token';
        $this->storageKey = 'api_token';
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->getTokenForRequest();

        if (!empty($token)) {
            $user = $this->provider->retrieveByCredentials(
                [$this->storageKey => $token]
            );
        }
        return $this->user = $user;
    }

    protected function getTokenForRequest()
    {
        return $this->request->cookie(
            $this->cookieKey
        );
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if (empty($credentials[$this->cookieKey])) {
            return false;
        }

        $credentials = [$this->storageKey => $credentials[$this->cookieKey]];

        if ($this->provider->retrieveByCredentials($credentials)) {
            return true;
        }

        return false;
    }

    public function logout()
    {
        \Cookie::queue(\Cookie::forget('XSRF-TOKEN'));
        \Cookie::queue(\Cookie::forget('laravel_session'));
        \Cookie::queue(\Cookie::forget('token'));
    }
}

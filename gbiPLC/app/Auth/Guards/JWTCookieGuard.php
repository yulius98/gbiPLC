<?php

namespace App\Auth\Guards;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTCookieGuard implements Guard
{
    use GuardHelpers;

    protected $request;
    protected $lastAttempted;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    /**
     * Get the currently authenticated user.
     */
    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        // Get JWT token from cookie
        if ($token = $this->request->cookie('jwt_token')) {
            try {
                JWTAuth::setToken($token);
                $user = JWTAuth::authenticate();
            } catch (JWTException $e) {
                // Token invalid
            }
        }

        return $this->user = $user;
    }

    /**
     * Validate a user's credentials.
     */
    public function validate(array $credentials = [])
    {
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        return $this->hasValidCredentials($user, $credentials);
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     */
    public function attempt(array $credentials = [], $remember = false)
    {
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            $this->login($user, $remember);
            return true;
        }

        return false;
    }

    /**
     * Log a user into the application without sessions or cookies.
     */
    public function login($user, $remember = false)
    {
        $this->setUser($user);
    }

    /**
     * Log the user out of the application.
     */
    public function logout()
    {
        $this->user = null;
    }

    /**
     * Determine if the user credentials are valid.
     */
    protected function hasValidCredentials($user, $credentials)
    {
        return $user !== null && $this->provider->validateCredentials($user, $credentials);
    }

    /**
     * Log a user into the application by their ID.
     */
    public function loginUsingId($id, $remember = false)
    {
        $user = $this->provider->retrieveById($id);

        if ($user) {
            $this->login($user, $remember);
            return $user;
        }

        return null;
    }

    /**
     * Log the given user ID into the application without sessions or cookies.
     */
    public function onceUsingId($id)
    {
        $user = $this->provider->retrieveById($id);

        if ($user) {
            $this->setUser($user);
            return $user;
        }

        return null;
    }

    /**
     * Log a user into the application without sessions or cookies.
     * This is for one-time authentication.
     */
    public function once(array $credentials = [])
    {
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            $this->setUser($user);
            return true;
        }

        return false;
    }
}

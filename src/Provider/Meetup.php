<?php

namespace Stuki\OAuth2\Client\Provider;

use Stuki\OAuth2\Client\Entity\User;

class Meetup extends AbstractProvider
{
    public $responseType = 'json';
    protected $requireState = false;

    public function urlAuthorize()
    {
        return 'https://secure.meetup.com/oauth2/authorize';
    }

    public function urlAccessToken()
    {
        return 'https://secure.meetup.com/oauth2/access';
    }

    public function urlUserDetails(\Stuki\OAuth2\Client\Token\AccessToken $token)
    {
        return 'https://api.meetup.com/2/member/self';
    }

    public function userDetails($response, \Stuki\OAuth2\Client\Token\AccessToken $token)
    {
        $user = new User;

        $name = (isset($response->name)) ? $response->name : null;
        $email = (isset($response->email)) ? $response->email : null;

        $user->exchangeArray(array(
            'uid' => $response->id,
            'name' => $name,
            'email' => $email,
        ));

        return $user;
    }

    public function userUid($response, \Stuki\OAuth2\Client\Token\AccessToken $token)
    {
        return $response->id;
    }

    public function userEmail($response, \Stuki\OAuth2\Client\Token\AccessToken $token)
    {
        return isset($response->email) && $response->email ? $response->email : null;
    }

    public function userScreenName($response, \Stuki\OAuth2\Client\Token\AccessToken $token)
    {
        return $response->name;
    }
}

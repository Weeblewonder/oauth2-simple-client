OAuth2 Simple Client
=============

[![Build Status](https://travis-ci.org/StukiOrg/oauth2-simple-client.png?branch=master)](https://travis-ci.org/StukiOrg/oauth2-simple-client)
[![Coverage Status](https://coveralls.io/repos/StukiOrg/oauth2-simple-client/badge.png)](https://coveralls.io/r/StukiOrg/oauth2-simple-client)
[![Total Downloads](https://poser.pugx.org/stuki/oauth2-simple-client/downloads.png)](https://packagist.org/packages/stuki/oauth2-simple-client)

This OAuth2 client is a simply better way to use OAuth2 in your application.


Included Providers
------------------

- Google
- Facebook
- Github
- Microsoft
- LinkedIn
- Box
- Instagram
- Eventbrite


About
-----

OAuth2 Simple Client is a fork of the popular [league/oauth2-client](https://github.com/thephpleague/oauth2-client/tree/2dde0d98f98a242a681a5cdfa354331fe2832d5f) and includes unit testing and a completely overhauled engine.

Beyond unit testing this library is tested against each provider manually [using StukiOrg/oauth2-simple-client-test](https://github.com/StukiOrg/oauth2-simple-client-test), a complete testing framework application.


Installation
------------

```sh
$ php composer.phar require stuki/oauth2-simple-client dev-master
```


ZF2 Example
-----------

```php
use Stuki\OAuth2\Client;

    public function LoginAction()
    {
        $config = $this->getServiceLocator()->get('Config');

        $provider = new Client\Provider\Meetup(array(
            'clientId' => $config['meetup']['key'],
            'clientSecret' => $config['meetup']['secret'],
            'redirectUri' => $config['meetup']['redirect'],
        ));

        if ( ! $this->params()->fromQuery('code')) {
            // No authorization code; send user to get one
            // Some providers support and/or require an application state token
            return $this->plugin('redirect')->toUrl($provider->getAuthorizationUrl(array('state' => 'token')));
        } else {
            try {
                // Get an authorization token
                $token = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code'],
                ]);
            } catch (Client\Exception\IDPException $e) {
                // Handle error from oauth2 server
            }

            // Store the access and refresh token for future use
            $container = new Container('oauth2');
            $container->accessToken = $token->accessToken;
            $container->refreshToken = $token->refreshToken;

            // Redirect to save session
            return $this->plugin('redirect')->toRoute('member');
        }
    }
```

Refresh a Token
---------------

```php
use Stuki\OAuth2\Client;

$provider = new Client\Provider\<ProviderName>(array(
    'clientId'  =>  'id',
    'clientSecret'  =>  'secret',
    'redirectUri'   =>  'https://your-registered-redirect-uri/'
));

$grant = new Client\Grant\RefreshToken();
$token = $provider->getAccessToken($grant, ['refresh_token' => $refreshToken]);
```
### ACKNOWLEDGEMENTS

StukiOrg would like to thank all the [contributors](https://github.com/StukiOrg/oauth2-simple-client/contributors) to this repository.

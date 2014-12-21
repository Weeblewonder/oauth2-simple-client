OAuth2 Simple Client
=============

[![Build Status](https://travis-ci.org/StukiOrg/oauth2-simple-client.png?branch=master)](https://travis-ci.org/StukiOrg/oauth2-simple-client)
[![Coverage Status](https://coveralls.io/repos/StukiOrg/oauth2-simple-client/badge.png)](https://coveralls.io/r/StukiOrg/oauth2-simple-client)
[![Total Downloads](https://poser.pugx.org/stuki/oauth2-simple-client/downloads.png)](https://packagist.org/packages/stuki/oauth2-simple-client)

This OAuth2 client is a simply better way to use OAuth2 in your application.


Included Providers
------------------

- Box
- Eventbrite
- Facebook
- Github
- Google
- Instagram
- LinkedIn
- Meetup
- Microsoft


About
-----

This is a hard fork of ThePHPLeague/oauth-client and this is the offical repository for StukiOrg/oauth2-simple-client.

This simple client implements a well architected solution for OAuth2 authentication.  Contributions are accepted for new OAuth2 adapters if you choose to share.


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


<?php

namespace JawboneApi;

use Unirest;
use JawboneApi\AccessScope;

/**
 * Class helper for generating the authentication token
 *
 * @package JawboneApi
 */
class OAuthClient
{
    const HTTPS = 'https://';

    const RESPONSE_TYPE = 'code';

    const GRANT_TYPE_OBTAIN_TOKENS = 'authorization_code';

    const GRANT_TYPE_REFRESH_TOKEN = 'refresh_token';

    protected $domainName = 'jawbone.com';

    protected $authURI = '/auth/oauth2/auth';

    protected $tokenURI = '/auth/oauth2/token';

    protected $redirectUrl;

    protected $clientId;

    protected $clientSecret;

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUrl
     */
    public function __construct($clientId, $clientSecret, $redirectUrl)
    {
        $this->setClientId($clientId);
        $this->setClientSecret($clientSecret);
        $this->setRedirectUrl($redirectUrl);
    }

    /**
     * Return URL for redirect the user
     *
     * Generates an URL of the access settings page.
     *
     * @return string
     */
    public function getRequestAccessUrl()
    {
        $requestParams = [
            'response_type' => static::RESPONSE_TYPE,
            'client_id' => $this->getClientId(),
            'scope' => implode(' ', AccessScope::getEnableScopeArray()),
            'redirect_uri' => $this->getRedirectUrl()
        ];

        return static::HTTPS . $this->domainName . $this->authURI . '?' . http_build_query($requestParams);
    }

    /**
     * Generate access and refresh tokens
     *
     * @param string $code
     *
     * @return \stdObj|string
     */
    public function getTokens($code)
    {
        $requestParams = [
            'grant_type' => static::GRANT_TYPE_OBTAIN_TOKENS,
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'code' => $code
        ];

        $response = Unirest\Request::get($this->getTokenUrl(), null, $requestParams);

        return $response->body;
    }

    /**
     * Refresh access token by refresh token
     *
     * @param string $refreshToken
     *
     * @return string
     */
    public function refreshAccessToken($refreshToken)
    {
        $requestParams = [
            'grant_type' => static::GRANT_TYPE_REFRESH_TOKEN,
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'refresh_token' => $refreshToken
        ];

        $response = Unirest\Request::get($this->getTokenUrl(), null, $requestParams);

        return $response->body;
    }

    /**
     * @param string $domainName
     */
    public function setDomainName($domainName)
    {
        $this->domainName = $domainName;
    }

    /**
     * @return string
     */
    public function getDomainName()
    {
        return $this->domainName;
    }

    /**
     * @param string $tokenURI
     */
    public function setTokenURI($tokenURI)
    {
        $this->tokenURI = $tokenURI;
    }

    /**
     * @return string
     */
    public function getTokenURI()
    {
        return $this->tokenURI;
    }

    /**
     * @param string $redirectUrl
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @param string $authURI
     */
    public function setAuthURI($authURI)
    {
        $this->authURI = $authURI;
    }

    /**
     * @return string
     */
    public function getAuthURI()
    {
        return $this->authURI;
    }

    /**
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    protected function getTokenUrl()
    {
        return static::HTTPS . $this->domainName . $this->tokenURI;
    }
}

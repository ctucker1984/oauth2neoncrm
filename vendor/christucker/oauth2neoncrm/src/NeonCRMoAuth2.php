<?php
/**
 * Chris Tucker - OAuth2 NeonCRM SSO Service.
 * An extension for the phpBB Forum Software Package
 */


namespace OAuth\OAuth2\Service;

use OAuth\OAuth2\Token\StdOAuth2Token;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Uri\Uri;
use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Http\Client\ClientInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Http\Uri\UriInterface;

class NeonCRMoAuth2 extends AbstractService
{

	/**
	 * NeonCRM OAuth2 constructor.
	 *
	 * @param \OAuth\Common\Consumer\CredentialsInterface	$credentials
	 * @param \OAuth\Common\Http\Client\ClientInterface		$httpClient
	 * @param \OAuth\Common\Storage\TokenStorageInterface	$storage
	 * @param array											$scopes
	 * @param \OAuth\Common\Http\Uri\UriInterface|null		$baseApiUri
	 */
	public function __construct(
		CredentialsInterface $credentials,
		ClientInterface $httpClient,
		TokenStorageInterface $storage,
		$scopes = [],
		UriInterface $baseApiUri = null
	)
	{
		parent::__construct($credentials, $httpClient, $storage, $scopes, $baseApiUri);

		if (null === $baseApiUri)
		{
			$this->baseApiUri = new Uri('https://api.neoncrm.com/v2');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAuthorizationEndpoint()
	{
		return new Uri('https://'. $this->credentials->org_id . '.app.neoncrm.com/np/oauth/auth');
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAccessTokenEndpoint()
	{
		return new Uri('https://app.neconcrm.com/np/oauth/token');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getExtraApiHeaders()
	{
		/** @see https://developer.neoncrm.com/authenticating-constituents/ */
		return array('Accept: application/x-www-form-urlencoded');
	}

    /**
     * {@inheritdoc}
     */
    protected function parseAccessTokenResponse($responseBody)
    {

        $data = json_decode($responseBody, true);

        if (null === $data || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (isset($data['error'])) {
            throw new TokenResponseException('Error in retrieving token: "' . $data['error'] . '"');
        }

        $token = new StdOAuth2Token();
        $token->setAccessToken($data['access_token']);

        return $token;
    }
}

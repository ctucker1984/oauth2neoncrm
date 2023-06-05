<?php
/**
 * Chris Tucker - OAuth2 NeonCRM SSO Service.
 * An extension for the phpBB Forum Software Package
 */

namespace christucker\oauth2neoncrm\auth\provider\oauth\service;

use phpbb\auth\provider\oauth\service\exception;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Exception\Exception as AccountResponseException;

/**
 * NeonCRM OAuth service
 */
class neoncrm extends \phpbb\auth\provider\oauth\service\base
{
	/** 
	 * @var \phpbb\config\config 
	 */
	protected $config;

	/** 
	 * @var \phpbb\language\language 
	 */
	protected $lang;

	/** 
	 * @var \phpbb\request\request_interface
	 */
	protected $request;

	/**
	 * Constructor.
	 *
	 * @param \phpbb\config\config				$config	Config object
	 * @param  \phpbb\language\language		$lang			Config object
	 * @param \phpbb\request\request_interface	$request	Request object
	 * @return void
	 * @access public
	 */
	public function __construct(
		\phpbb\config\config $config, 
		\phpbb\language\language $lang,
		\phpbb\request\request_interface $request)
	{
		$this->config	= $config;
		$this->lang		= $lang;
		$this->request	= $request;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_service_credentials()
	{
		return [
			'org_id'		=> $this->config['auth_oauth_neoncrm_org_id'],
			'client_id'	    => $this->config['auth_oauth_neoncrm_client_id'],
			'secret'	    => $this->config['auth_oauth_neoncrm_secret'],
			'api_key'	    => $this->config['auth_oauth_neoncrm_api_key'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function perform_auth_login()
	{
		if (!($this->service_provider instanceof \OAuth\OAuth2\Service\NeonCRMoAuth2))
		{
			throw new exception('AUTH_PROVIDER_OAUTH_ERROR_INVALID_SERVICE_TYPE');
		}

		try
		{
			// This was a callback request, get the token
			$this->service_provider->requestAccessToken($this->request->variable('code', ''));
		}
		catch (TokenResponseException $e)
		{
			throw new exception('AUTH_PROVIDER_OAUTH_ERROR_REQUEST');
		}

		$result['login'] = '';

		try
		{
			// Send a request with it
			$result = (array) json_decode($this->service_provider->request('user/info'), true);
		}
		catch (\OAuth\Common\Exception\Exception $e)
		{
			throw new exception('AUTH_PROVIDER_OAUTH_ERROR_REQUEST');
		}

		// Prevent SQL error
		if (!isset($result['login']))
		{
			throw new exception('AUTH_PROVIDER_OAUTH_RETURN_ERROR');
		}

		// Return the unique identifier returned from bitly
		return $result['login'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function perform_token_auth()
	{
		if (!($this->service_provider instanceof \OAuth\OAuth2\Service\NeonCRMoAuth2))
		{
			throw new exception('AUTH_PROVIDER_OAUTH_ERROR_INVALID_SERVICE_TYPE');
		}

		try
		{
			// Send a request with it
			$result = (array) json_decode($this->service_provider->request('user/info'), true);
		}
		catch (\OAuth\Common\Exception\Exception $e)
		{
			throw new exception('AUTH_PROVIDER_OAUTH_ERROR_REQUEST');
		}

		// Prevent SQL error
		if (!isset($result['login']))
		{
			throw new exception('AUTH_PROVIDER_OAUTH_RETURN_ERROR');
		}

		// Return the unique identifier
		return $result['login'];
	}
}

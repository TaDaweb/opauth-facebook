<?php
/**
 * Facebook strategy for Opauth
 * based on https://developers.facebook.com/docs/authentication/server-side/
 *
 * More information on Opauth: http://opauth.org
 *
 * @copyright    Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @package      Opauth.FacebookStrategy
 * @license      MIT License
 */
namespace Opauth\Strategy\Facebook;

class Strategy extends \Opauth\Strategy\Oauth\Strategy {

	/**
	 * Compulsory config keys, listed as unassociative arrays
	 * eg. array('app_id', 'app_secret');
	 */
	public $expects = array('app_id', 'app_secret');

	/**
	 * Map response from raw data
	 *
	 * @var array
	 */
	public $responseMap = array(
		'name' => 'username',
		'uid' => 'id',
		'info.name' => 'name',
		'info.email' => 'email',
		'info.nickname' => 'username',
		'info.first_name' => 'first_name',
		'info.last_name' => 'last_name',
		'info.location' => 'location.name',
		'info.urls.website' => 'website'
	);

	protected $requestUrl = 'https://www.facebook.com/dialog/oauth';

	protected $requestParams = array(
		'scope',
		'state',
		'response_type',
		'display',
		'auth_type',
		'app_id' => 'client_id'
	);

	protected $tokenUrl = 'https://graph.facebook.com/oauth/access_token';

	protected $userUrl = 'https://graph.facebook.com/me';

	/**
	 * Helper method for callback()
	 *
	 * @return array Parameter array
	 */
	protected function callbackParams() {
		return $this->addParams(array(
			'app_id' => 'client_id',
			'app_secret' => 'client_secret'
		));
	}

	protected function accessToken($code) {
		return $this->getToken($code);
	}

	protected function callbackResponse($response, $results) {
		$response->credentials = array(
			'token' => $results['access_token'],
			'expires' => date('c', time() + $results['expires'])
		);
		$response->info['image'] = 'https://graph.facebook.com/'. $response->raw['id'] . '/picture?type=square';
		return $response;
	}

}

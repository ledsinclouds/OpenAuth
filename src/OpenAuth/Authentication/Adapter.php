<?php
namespace OpenAuth\Authentication;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result as AuthenticationResult;

class Adapter implements AdapterInterface{

	public $response = null;
	public $provider;
	public $oauth;
	public $authService;

    public function authenticate(){
        $provider = $this->getProvider();
        $oauth   = $this->getOAuth();
        $response = null;

        switch ($oauth->env['callback_transport']) {
            case 'session':
                if (isset($_SESSION['opauth'])) {
                    $response = $_SESSION['opauth'];
                }
                break;
            case 'post':
                if (isset($_POST['opauth']))
                    $response = unserialize(base64_decode($_POST['opauth']));
                break;
            case 'get':
                if (isset($_GET['opauth']))
                    $response = unserialize(base64_decode($_GET['opauth']));
                break;
        }

        if (!is_array($response) || $response == null) {
                $result = new AuthenticationResult(
                    AuthenticationResult::FAILURE_UNCATEGORIZED,
                    array(
                        'provider' => $provider,
                        'response'   => $response,
                        'message' => 'Authentication error: Opauth response is not an array or is null'
                    )
                );
        } elseif (array_key_exists('error', $response)) {
                $result = new AuthenticationResult(
                    AuthenticationResult::FAILURE_UNCATEGORIZED,
                    array(
                        'provider' => $provider,
                        'response'   => $response,
                        'message' => 'Authentication error: Opauth returns error auth response'
                    )
                );
        } else {
            if (empty($response['auth']) || empty($response['timestamp']) || empty($response['signature']) || empty($response['auth']['provider']) || empty($response['auth']['uid'])) {
                $result = new AuthenticationResult(
                    AuthenticationResult::FAILURE_UNCATEGORIZED,
                    array(
                        'provider' => $provider,
                        'response'   => $response,
                        'message' => 'Invalid auth response: Missing key auth response components'
                    )
                );
            } elseif (!$oauth->validate(sha1(print_r($response['auth'], true)), $response['timestamp'], $response['signature'], $reason)) {
                $result = new AuthenticationResult(
                    AuthenticationResult::FAILURE_UNCATEGORIZED,
                    array(
                        'provider' => $provider,
                        'response'   => $response,
                        'message' => 'Invalid auth response: '.$reason
                    )
                );
            } else {

                if ($this->getAuthenticationService()->hasIdentity())
                    $identity = $this->getAuthenticationService()->getIdentity();
                else
                    $identity = array();

                if (!isset($identity['auth-login']) || !is_array($identity['auth-login'])) $identity['auth-login'] = array();
                if (!isset($identity['auth-login']['opauth']) || !is_array($identity['auth-login']['opauth'])) $identity['openpauth']['opauth'] = array();
                if (!isset($identity['auth-login']['current_providers']) || !is_array($identity['auth-login']['opauth'])) $identity['auth-login']['current_providers'] = array();

                $identity['auth-login']['opauth'][$provider] = $response;

                if (!isset($identity['auth-login']['current_providers']) || !is_array($identity['auth-login']['current_providers']))
                    $identity['auth-login']['current_providers'] = array();

                if (!in_array($provider, $identity['auth-login']['current_providers']))
                    $identity['auth-login']['current_providers'][] = $provider;

                $result = new AuthenticationResult(
                    AuthenticationResult::SUCCESS,
                    $identity
                );
            }
        }

        return $result;
	}

	public function setAuthenticationService($auth){
		$this->authService = $auth;
	}

	public function getAuthenticationService(){
		return $this->authService;
	}

	public function setProvider($provider){
		$this->provider = $provider;
	}

	public function getProvider(){
		return $this->provider;
	}

	public function setOAuth($oauth){
		$this->oauth = $oauth;
	}

	public function getOAuth(){
		return $this->oauth;
	}

}


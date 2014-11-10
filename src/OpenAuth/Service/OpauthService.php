<?php

namespace OpenAuth\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Opauth;
use OpenAuth\OpenAuthEvent;

class OpauthService implements ServiceLocatorAwareInterface, EventManagerAwareInterface{

	protected $eventManager;
	private $serviceLocator;
	private $options;
	private $router;
	private $loginUrlName;
	private $loginUrlNameParams;
	private $callbackUrlName;
	private $callbackUrlNameParams;

	public function redirect($provider, $oauth_callback){
		$opauth = new Opauth($this->getOptions($provider));
	}

	public function callback($provider){
		$opauth = new Opauth($this->getOptions($provider), false);
		$authService = $this->getServiceLocator()->get('authService');
		$authAdapter = $this->getServiceLocator()->get('authAdapter');
		$authAdapter->setOAuth($opauth);
		$authAdapter->setProvider($provider);
		$authAdapter->setAuthenticationService($authService);
		var_dump($provider);

		$authResult = $authService->authenticate($authAdapter);
		$data = array(
			'provider' => $provider,
			'result' => $authResult->isValid(),
			'code' => $authResult->getCode(),
			'message' => $authResult->getMessages(),
			'debug' => $authResult
		);

		$this->getEventManager()->trigger(OpenAuthEvent::EVENT_LOGIN_CALLBACK, $this, array(
			'authService' => $authService,
			'authResult' => $authResult,
			'provider' => $provider
		));
		return $data;
	}

	public function setRouter($router){
		$this->router = $router;
	}

	public function getRouter(){
		return $this->router;
	}

	public function setOptions($options){
		$this->options = $options;
	}

	public function getOptions($provider){
		if($this->options === null || !is_array($this->options)){
			$this->setOptions($this->getServiceLocator()->get('openauth_module_options'));
		}

		$callbackUrlParams = array_replace(array('provider' => $provider), $this->getCallbackUrlNameParams());
		$this->options['path'] = $this->getRouter()->assemble($this->getLoginUrlNameParams(), array('name' => $this->getLoginUrlName()));
		$this->options['callback_url'] = $this->getRouter()->assemble($callbackUrlParams, array('name' => $this->getCallbackUrlName()));
		return $this->options;
	}

	public function setLoginUrlName($loginUrlName){
		$this->loginUrlName = $loginUrlName;
	}

	public function getLoginUrlName(){
		if ($this->loginUrlName == null) return 'openauth_login';
		return $this->loginUrlName;
	}

	public function setLoginUrlNameParams($loginUrlNameParams){
		$this->loginUrlNameParams = $loginUrlNameParams;
	}

	public function getLoginUrlNameParams(){
		if ($this->loginUrlNameParams == null) return array();
		return $this->loginUrlNameParams;
	}

	public function setCallbackUrlName($callbackUrlName){
		$this->callbackUrlName = $callbackUrlName;
	}

	public function getCallbackUrlName(){
		if ($this->callbackUrlName == null) return 'openauth_callback';
		return $this->callbackUrlName;
	}

	public function setCallbackUrlNameParams($callbackUrlNameParams){
		$this->callbackUrlNameParams = $callbackUrlNameParams;
	}

	public function getCallbackUrlNameParams(){
		if ($this->callbackUrlNameParams == null) return array();
		return $this->callbackUrlNameParams;
	}

	public function getServiceLocator(){
		return $this->serviceLocator;
	}

	public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator){
		$this->serviceLocator = $serviceLocator;
	}

	public function setEventManager(EventManagerInterface $eventManager){
		$eventManager->addIdentifiers(array(
			get_called_class()
		));
		$this->eventManager = $eventManager;
	}

	public function getEventManager(){
		return $this->eventManager;
	}
}

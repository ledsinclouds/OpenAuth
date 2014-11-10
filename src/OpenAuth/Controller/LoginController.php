<?php

namespace OpenAuth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LoginController extends AbstractActionController{

    public function indexAction(){

        return new ViewModel();
	}

	public function redirectAndReturnAction(){
		$provider = $this->params()->fromRoute('provider');
		$oauth_callback = $this->params()->fromRoute('oauth_callback');
		$this->getServiceLocator()->get('opauthService')->redirect($provider, $oauth_callback);

	}

	public function callbackAction(){
		$provider = $this->params()->fromRoute('provider');
		$this->getServiceLocator()->get('opauthService')->callback($provider);
		$auth = $this->getServiceLocator()->get('authService');
		echo '<pre>';
		//var_dump($auth->getIdentity());
		print_r($provider);
		echo '<br>';
		print_r($_SESSION['opauth']['auth']['info']);
		echo '</pre>';
		return array(
			'result' => $auth->hasIdentity(),
			//'identity' => $auth->getIdentity(),
			'provider' => $provider
		);
	}


}


<?php

namespace OpenAuth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CheckController extends AbstractActionController{

    public function indexAction(){
        return new ViewModel();
	}

	public function checkAction(){
		if(!$this->isCheckControllerEnabled()){
			$this->getResponse()->setStatusCode(404);
			return;
		}

		$return = array();

		$auth = $this->getServiceLocator()->get('authService');
		$return['loggedIn'] = $auth->hasIdentity();
		$return['identify'] = $auth->getIdentity();

		return $return;
	}

	public function isCheckControllerEnabled(){
		$options = $this->getServiceLocator()->get('openauth_module_options');
		if(!isset($options['check_controller_enabled'])) return false;
		return (bool) $options['check_controller_enabled'];
	}


}


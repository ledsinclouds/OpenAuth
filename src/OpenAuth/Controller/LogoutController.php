<?php

namespace OpenAuth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LogoutController extends AbstractActionController{

    public function indexAction(){
        return new ViewModel();
	}

	public function logoutAction(){
		$auth = $this->getServiceLocator()->get('authService');
		return $auth->clearIdentity();
	}


}


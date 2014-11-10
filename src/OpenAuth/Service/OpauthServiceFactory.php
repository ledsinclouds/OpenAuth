<?php

namespace OpenAuth\Service;

use OpenAuth\Service\OpauthService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OpauthServiceFactory implements FactoryInterface{
	public function createService(ServiceLocatorInterface $serviceLocator){
		$opauth = new OpauthService();
		$router = $serviceLocator->get('router');
		$opauth->setEventManager($serviceLocator->get('EventManager'));
		$opauth->setRouter($router);

		return $opauth;
	}
}

<?php
namespace OpenAuth;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\EventManager\EventInterface as Event;
use Zend\Session\SessionManager;

class Module implements ConfigProviderInterface, ServiceProviderInterface{

	public function getServiceConfig(){
		return array(
			'invokables' => array(
				'authAdapter' =>'OpenAuth\Authentication\Adapter',
				'authService' => 'Zend\Authentication\AuthenticationService',
			),
			'factories' => array(
				'opauthService' => 'OpenAuth\Service\OpauthServiceFactory',
				'openauth_module_options' => function($sm){
					$config = $sm->get('Config');
					return isset($config['openauth']) ? $config['openauth'] : array();
				}
			),
		);
	}

	public function onBootstrap(Event $e){
		$session = new SessionManager();
		if(!$session->sessionExists()) $session->start();
}

	public function getControllerConfig(){
		return array(
			'invokables' => array(
				'OpenAuth\Controller\Index' => 'OpenAuth\Controller\IndexController',
				'OpenAuth\Controller\Login' => 'OpenAuth\Controller\LoginController',
				'OpenAuth\Controller\Logout' => 'OpenAuth\Controller\LogoutController',
				'OpenAuth\Controller\Check' => 'OpenAuth\Controller\CheckController',
			)
		);
	}

	public function getConfig(){
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig(){
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}

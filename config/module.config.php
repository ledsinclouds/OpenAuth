<?php
return array(
	'router' => array(
		'routes' => array(
			'openauth' => array(
				'type' => 'literal',
				'options' => array(
					'route' => '/user',
					'defaults' => array(
						'controller' => 'OpenAuth\Controller\Index',
						'action' => 'index'
					)
				)
			),
			'openauth_logout' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/user/logout/openid[/:provider]',
					'constraints' => array(
						'provider' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'OpenAuth\Controller\Logout',
						'action' => 'logout',
					),
				),
			),
			'openauth_login' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/user/login/openid/[:provider[/:oauth_callback]]',
					'constraints' => array(
						'provider'       => '[a-zA-Z][a-zA-Z0-9_-]*',
						'oauth_callback' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller'    => 'OpenAuth\Controller\Login',
						'action' => 'redirectAndReturn',
					),
				),
			),
			'openauth_callback' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/user/openid/callback/[:provider]',
					'constraints' => array(
						'provider'  => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'OpenAuth\Controller\Login',
						'action' => 'callback',
					),
				),
			),
			'oprnauth_check' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/user/openid/check',
					'defaults' => array(
						'controller' => 'OpenAuth\Controller\Check',
						'action' => 'check',
					),
				),
			),
		),
	),
	'view_manager' => array(
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),
);

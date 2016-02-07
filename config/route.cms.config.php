<?php

return array(
	'router' => array(
		'routes' => array(
			'om-zf2-user-account-register' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/user/account/register',
                    'defaults' => array(
                        'controller' => 'Oml\Zf2User\Controller\AccountController',
                        'action' => 'register'
                    )
                )
            ),
            'om-zf2-user-account-profile' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/user/:slug/account/profile',
                    'defaults' => array(
                        'controller' => 'Oml\Zf2User\Controller\AccountController',
                        'action' => 'profile'
                    )
                )
            ),
            'om-zf2-user-account-change-password' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/user/:slug/account/change-password',
                    'defaults' => array(
                        'controller' => 'Oml\Zf2User\Controller\AccountController',
                        'action' => 'change-password'
                    )
                )
            ),
            'om-zf2-user-account-sign-in' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/user/account/sign-in',
                    'defaults' => array(
                        'controller' => 'Oml\Zf2User\Controller\AccountController',
                        'action' => 'sign-in'
                    )
                )
            ),
            'om-zf2-user-account-sign-out' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/user/account/sign-out',
                    'defaults' => array(
                        'controller' => 'Oml\Zf2User\Controller\AccountController',
                        'action' => 'sign-out'
                    )
                )
            )
		)
	)
);

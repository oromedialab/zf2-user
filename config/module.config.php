<?php

return array(
    'service_manager' => array(
        'invokables' => array(
            'Oml\Zf2User\Entity\User' => 'Oml\Zf2User\Entity\User',
            'Oml\Zf2User\Hydrator\ClassMethods' => 'Zend\Stdlib\Hydrator\ClassMethods',
            'AliasManager' => 'Oml\Zf2User\Service\AliasManager',
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Oml\Zf2User\Controller\AccountController' => 'Oml\Zf2User\Controller\AccountController'
        )
    ),
    'view_manager' => array(
    	'template_map' => array(
    		'oml/account/register' => __DIR__.'/../view/zf2-user/account/register.phtml',
            'oml/account/profile' => __DIR__.'/../view/zf2-user/account/profile.phtml',
            'oml/account/change-password' => __DIR__.'/../view/zf2-user/account/change-password.phtml',
            'oml/account/forgot-password' => __DIR__.'/../view/zf2-user/account/forgot-password.phtml',
            'oml/account/sign-in' => __DIR__.'/../view/zf2-user/account/sign-in.phtml',
    	),
    	'template_path_stack' => array(
            __DIR__.'/../view',
        )
    )
);

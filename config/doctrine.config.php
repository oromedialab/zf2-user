<?php

namespace Oml\Zf2User;

return array(
	'doctrine' => array(
        'eventmanager' => array(
            'orm_default' => array(
                'subscribers' => array(
                    'Gedmo\Timestampable\TimestampableListener',
                    'Gedmo\Sluggable\SluggableListener'
                )
            )
        ),
        'driver' => array(
            'oml_zf2_user_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Zf2User/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Oml\Zf2User\Entity' => 'oml_zf2_user_entities'
                )
            )
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Oml\Zf2User\Entity\User',
                'identity_property' => 'email',
                'credential_property' => 'password',
                'credential_callable' => function(Entity\User $user, $password) {

                    function createAuthenticationResult($code, $message) {
                        $result = array();
                        $result['code'] = $code;
                        $result['messages'][] = $message;
                        return $result;
                    }

                    // User account is disabled
                    if (!$user->getEnabled()) {
                        return createAuthenticationResult(
                            \Zend\Authentication\Result::FAILURE_IDENTITY_AMBIGUOUS,
                            'User account is disabled, please contact administrator'
                        );
                    }

                    // Successfull authentication
                    return createAuthenticationResult(
                        \Zend\Authentication\Result::SUCCESS,
                        'Authenticated user successfully'
                    );
                }
            )
        )
    )
);

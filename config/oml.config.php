<?php

return [
	'oml' => [
        'zf2-user' => [
            'unrestricted-routes' => [
                'om-zf2-user-account-register',
                'om-zf2-user-account-sign-in',
                'om-zf2-user-account-forgot-password'
            ]
        ],
        'zf2-lazy-form' => [
            'aliases' => [
                'Form\SignIn' => 'Oml\Zf2User\Form\SignIn'
            ],
            '*' => function(\Zend\Form\Form $form) {
                $form->addFormElement(['name' => 'submit', 'label' => ':submit-btn-label', 'type' => 'button', 'lazy-set' => ['submit-btn']], ['priority' => -9999]);
                $form->addFormElement(['name' => 'csrf', 'type' => 'csrf', 'lazy-set' => ['submit-btn']]);
                $form->setHydrator(new \Zend\Stdlib\Hydrator\ClassMethods(true));
            },
            'default' => [
                'placeholder' => [
                    ':min' => 2,
                    ':max' => 200,
                    ':identical-field' => 'password',
                    ':submit-btn-label' => 'Save'
                ]
            ],
            'attributes' => [
                'submit-btn' => [
                    'type' => 'submit',
                    'class' => 'submit-btn'
                ]
            ],
            'filters' => [
                'strip-tags' => ['name' => 'StripTags'],
                'string-trim' => ['name' => 'StringTrim']
            ],
            'validators' => [
                'not-empty' => ['name' => 'NotEmpty'],
                'string-length' => [
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => ':min',
                        'max' => ':max'
                    )
                ],
                'email-address' => ['name' => 'EmailAddress'],
                'identical' => [
                    'name' => 'Identical',
                    'options' => [
                        'token' => ':identical-field',
                    ]
                ],
                'no-record-exist' => [
                    'name' => 'DoctrineModule\Validator\NoObjectExists',
                    'options' => [
                        'object_repository' => ':object-repository',
                        'fields' => ':no-record-exist-fields'
                    ]
                ],
                'unique-object' => [
                    'name' => 'DoctrineModule\Validator\UniqueObject',
                    'options' => [
                        'use_context' => true,
                        'object_manager' => ':object-manager',
                        'object_repository' => ':object-repository',
                        'fields' => ':unique-object-fields',
                        'messages' => [
                            'objectNotUnique' => "There is already another object matching '%value%'"
                        ]
                    ]
                ]
            ],
            'lazy-set' => [
                'submit-btn' => [
                    'attributes' => ['submit-btn'],
                    'filters' => false
                ],
                'required-text-field' => [
                    'validators' => ['not-empty', 'string-length'],
                    'filters' => ['strip-tags', 'string-trim']
                ],
                'email-address' => [
                    'validators' => ['email-address']
                ],
                'no-record-exist' => [
                    'validators' => ['no-record-exist']
                ],
                'unique-object' => [
                    'validators' => ['unique-object']
                ],
                'identical' => [
                    'validators' => ['identical']
                ]
            ]
        ]
    ]
];

<?php
/**
 * @author Ibrahim Azhar <azhar@iarmar.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Oml\Zf2User\Form;

use Oml\Zf2LazyForm\Form\Base;

class Register extends Base
{
	public function initialize()
	{
		// Init Parent
		parent::initialize();

		// ServiceManager and ObjectManager
		$sm = $this->getServiceLocator();
		$em = $sm->get('Doctrine\ORM\EntityManager');
		$userRepository = $em->getRepository('Oml\Zf2User\Entity\User');

		// Set Placeholder Parameter
		$this->setPlaceholderParameter(':object-manager', $em);
		$this->setPlaceholderParameter(':object-repository', $userRepository);
		$this->setPlaceholderParameter(':no-record-exist-fields', 'email');
		$this->setPlaceholderParameter(':submit-btn-label', 'Register');

		// Add Form Elements
		$this->addFormElement(['name' => 'name', 'label' => 'Name', 'type' => 'text', 'lazy-set' => ['required-text-field']]);
		$this->addFormElement(['name' => 'email', 'label' => 'Email', 'type' => 'text', 'lazy-set' => ['required-text-field', 'email-address', 'no-record-exist']]);
		$this->addFormElement(['name' => 'password', 'label' => 'Password', 'type' => 'password', 'lazy-set' => ['required-text-field']]);
		$this->addFormElement(['name' => 'confirm_password', 'label' => 'Confirm Password', 'type' => 'password', 'lazy-set' => ['required-text-field', 'identical']]);
	}
}

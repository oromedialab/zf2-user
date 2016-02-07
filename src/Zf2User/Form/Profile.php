<?php
/**
 * @author Ibrahim Azhar <azhar@iarmar.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace Oml\Zf2User\Form;

class Profile extends Register
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
		$this->setPlaceholderParameter(':object-repository', $userRepository);
		$this->setPlaceholderParameter(':object-repository', $userRepository);
		$this->setPlaceholderParameter(':unique-object-fields', 'email');
		$this->setPlaceholderParameter(':submit-btn-label', 'Save & Continue');

		// Remove Form Elements
		$this->removeFormElement('password');
		$this->removeFormElement('confirm_password');

		//Add Form Element
		$this->addFormElement(['name' => 'id', 'type' => 'hidden']);

		// Replace Form Element
		$this->replaceFormElement(['name' => 'email', 'label' => 'Email', 'type' => 'text', 'lazy-set' => ['required-text-field', 'email-address', 'unique-object']]);
	}
}

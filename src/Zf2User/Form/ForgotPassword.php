<?php
/**
 * @author Ibrahim Azhar <azhar@iarmar.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Oml\Zf2User\Form;

use Oml\Zf2LazyForm\Form\Base;
use Application\Common\Utility;

class ForgotPassword extends Base
{
	public function initialize()
	{
		// Init Parent
		parent::initialize();

		// ServiceManager and ObjectManager
		$sm = $this->getServiceLocator();
		$em = $sm->get('Doctrine\ORM\EntityManager');
		$userRepository = $em->getRepository('Oml\Zf2User\Entity\User');

		$this->setPlaceholderParameter(':object-manager', $em);
		$this->setPlaceholderParameter(':object-repository', $userRepository);
		$this->setPlaceholderParameter(':text-element-placeholder', 'Email Address', 'email');
		$this->setPlaceholderParameter(':href-link', Utility::routeNameToUrl($sm, 'om-zf2-user-account-sign-in'));

		//Add Form Element
		$this->addFormElement(['name' => 'email', 'label' => 'Email', 'type' => 'text', 'lazy-set' => [
			'form-element', 'required-text-field', 'email-address', 'no-record-exist'
		]]);
		$this->addFormElement(['name' => 'href', 'label' => 'Sign In', 'type' => 'Application\Form\Element\Href', 'lazy-set' => ['forgot-password-link']]);

		// Replace Form Element
		$this->replaceFormElement(['name' => 'submit', 'label' => 'Submit', 'type' => 'button', 'lazy-set' => ['sign-in-btn']]);
	}
}

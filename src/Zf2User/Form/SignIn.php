<?php
/**
 * @author Ibrahim Azhar <azhar@iarmar.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace Oml\Zf2User\Form;

use Oml\Zf2LazyForm\Form\Base;

class SignIn extends Base
{
	public function initialize()
	{
		// Init Parent
		parent::initialize();

		// Placeholder Parameter
		$this->setPlaceholderParameter(':submit-btn-label', 'Sign In');

		// Add Form Elements
		$this->addFormElement(['name' => 'email', 'label' => 'Email', 'type' => 'text']);
		$this->addFormElement(['name' => 'password', 'label' => 'Password', 'type' => 'password']);
	}
}

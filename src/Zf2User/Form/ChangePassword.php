<?php
/**
 * @author Ibrahim Azhar <azhar@iarmar.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Oml\Zf2User\Form;

use Oml\Zf2LazyForm\Form\Base;

class ChangePassword extends Base
{
	public function initialize()
	{
		// Init Parent
		parent::initialize();
		// Password
		$this->addFormElement(['name' => 'password', 'label' => 'Password', 'type' => 'password', 'lazy-set' => ['required-text-field']]);
		// Confirm Password
		$this->addFormElement(['name' => 'confirm_password', 'label' => 'Confirm Password', 'type' => 'password', 'lazy-set' => ['required-text-field', 'identical']]);
	}
}

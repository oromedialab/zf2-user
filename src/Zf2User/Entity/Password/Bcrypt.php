<?php
/**
 * @author Ibrahim Azhar <azhar@iarmar.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Oml\Zf2User\Entity\Password;

use Zend\Crypt\Password;

class Bcrypt implements PasswordInterface
{
	/**
	 * Instance of Bcrypt 
	 *
	 * @var Zend\Crypt\Password\Bcrypt
	 */
	protected static $bcrypt;

	/**
	 * Input Password
	 *
	 * @var string
	 */
	protected $password;

	/**
	 * Bcrypt Converted Password
	 *
	 * @var string
	 */
	protected $securedPassword;

	/**
	 * Initialize Instance of Bcrypt and generate secured password
	 */
	public function __construct($password)
	{
		self::$bcrypt = self::init();
		$this->password = $password;
		$this->securedPassword = $this->createSecuredPassword($password);
		return $this;
	}

	/**
	 * Get instance of Bcrypt
	 *
	 * @return Bcrypt
	 */
	protected static function init()
	{
		if (!self::$bcrypt instanceof Password\Bcrypt) {
			self::$bcrypt = new Password\Bcrypt;
		}
		return self::$bcrypt;
	}

	/**
	 * Create Secured Password
	 *
	 * @return string
	 */
	public function createSecuredPassword($password)
	{
		return self::$bcrypt->create($password);
	}

	/**
	 * Get Secured Password
	 *
	 * @return string
	 */
	public function getSecuredPassword()
	{
		return $this->securedPassword;
	}

	/**
	 * Verify Secured Password
	 *
	 * @return boolean
	 */
	public static function verifySecuredPassword($password, $securedPassword)
	{
		self::$bcrypt = self::init();
		return self::$bcrypt->verify($password, $securedPassword);
	}
}

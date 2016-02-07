<?php
/**
 * @author Ibrahim Azhar <azhar@iarmar.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Oml\Zf2User\Entity\Password;

interface PasswordInterface
{
	/**
	 * User must enter password using contructor
	 *
	 * @param string $password
	 */
	public function __construct($password);

	/**
	 * Create secure password
	 *
	 * @return string
	 */
	public function createSecuredPassword($password);

	/**
	 * Get generated secured password
	 *
	 * @return string
	 */
	public function getSecuredPassword();

	/**
	 * Verify password
	 *
	 * @return boolean
	 */
	public static function verifySecuredPassword($password, $securedPassword);
}

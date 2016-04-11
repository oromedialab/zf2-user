<?php
/**
 * @author Ibrahim Azhar <azhar@iarmar.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Oml\Zf2User\Entity;

use Zend\Session\SessionManager as ZendSessionManager;

class SessionManager extends ZendSessionManager
{
	/**
	 * Overwrite session regenerate Id
	 * @param  boolean $deleteOldSession
	 * @return $this
	 */
	public function regenerateId($deleteOldSession = false)
	{
	    session_regenerate_id((bool) $deleteOldSession);
	    return $this;
	}
}

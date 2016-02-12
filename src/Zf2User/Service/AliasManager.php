<?php
/**
 * Manages module service
 *
 * @author Ibrahim Azhar <azhar@iarmar.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace Oml\Zf2User\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AliasManager implements ServiceLocatorAwareInterface
{
    /**
     * Instance of service manager
     *
     * @var Zend\ServiceManager\ServiceManager
     */
    protected $serviceLocator;

    /**
     * Method applied from ServiceLocatorAwareInterface, required to inject service locator object
     *
     * @param ServiceLocatorInterface $sl
     * @return $this
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Method applied from ServiceLocatorAwareInterface, required to retreive service locator object
     *
     * @return Zend\ServiceManager\ServiceManager
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Get module config in array format
     *
     * @return array
     */
    public function config()
    {
    	$config = $this->getServiceLocator()->get('config');
    	return $config['oml']['zf2-lazy-form'];
    }

    public function alias($name)
    {
        $config = $this->config();
        if(!array_key_exists('aliases', $config) || empty($config['aliases']) || !is_array($config['aliases'])) {
            throw new \Exception('Aliases not defined in the configuration');
        }
        $aliases = $config['aliases'];
        if (!array_key_exists($name, $aliases)) {
            throw new \Exception('Alias with name "'.$name.'" not found in configuration');
        }
        return $aliases[$name];
    }
}

<?php
/**
 * @author Ibrahim Azhar <azhar@iarmar.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Oml\Zf2User;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

class Module
{
    protected $unrestrictedRoutes = array(
        'om-zf2-user-account-register',
        'om-zf2-user-account-sign-in'
    );

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'verifyAuthAccess'));
    }

    public function getConfig()
    {
        return array_merge_recursive(
            include __DIR__ . '/config/module.config.php',
            include __DIR__ . '/config/route.cms.config.php',
            include __DIR__ . '/config/doctrine.config.php',
            include __DIR__ . '/config/oml.config.php'
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/Zf2User',
                ),
            ),
        );
    }

    public function verifyAuthAccess(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $routeMatch = $e->getRouteMatch();
        $matchedRouteName = $routeMatch->getMatchedRouteName();
        // Skip auth-check for API request
        if($this->currentRequestIsApi($serviceManager)) {
            return;
        }
        // Skip auth-check for CLI request
        if (get_class($serviceManager->get('request')) == 'Zend\Console\Request') {
            return;
        }
        $authService = $serviceManager->get('Zend\Authentication\AuthenticationService');
        // Unauthenticated has access to unrestricted routes only
        if (!$authService->hasIdentity() && !in_array($matchedRouteName, $this->unrestrictedRoutes)) {
            $routeMatch->setParam('controller', __NAMESPACE__.'\Controller\AccountController');
            $routeMatch->setParam('action', 'sign-in');
        }
        // If authenticated user try accessing restricted route, redirect to home
        if ($authService->hasIdentity() && in_array($matchedRouteName, $this->unrestrictedRoutes)) {
            return $this->redirectToRoute($e, 'home', 302);
        }
        // If user is deleted redirect to sign-in
        $user = $authService->getIdentity();
        // If user is disabled or deleted, ask user to sign-in and clear identity
        $forceUserLogout = false;
        if ($user && !$user->getEnabled() && !in_array($matchedRouteName, $this->unrestrictedRoutes)) {
            $forceUserLogout = true;
        }
        if (!$user && !in_array($matchedRouteName, $this->unrestrictedRoutes)) {
            $forceUserLogout = true;
        }
        if (true === $forceUserLogout) {
            $authService->clearIdentity();
            $sessionManager = new \Zend\Session\SessionManager();
            $sessionManager->forgetMe();
            $routeMatch->setParam('controller', __NAMESPACE__.'\Controller\AccountController');
            $routeMatch->setParam('action', 'sign-in');
        }
    }

    public function currentRequestIsApi(ServiceManager $serviceManager)
    {
        $isApiRequest = false;
        $router = $serviceManager->get('router');
        $request = $serviceManager->get('request');
        $matchedRoute = $router->match($request);
        if ($matchedRoute) {
            $params = $matchedRoute->getParams();
            $matchedControllerName = $params['controller'];
            $controllerManager = $serviceManager->get('ControllerManager');
            if ($controllerManager->has($matchedControllerName)) {
                $controller = $controllerManager->get($matchedControllerName);
                if ('Zend\Mvc\Controller\AbstractRestfulController' == get_parent_class($controller)) {
                    $isApiRequest = true;
                }
            }
        }
        return $isApiRequest;
    }

    protected function redirectToRoute(MvcEvent $e, $routeName, $statusCode)
    {
        $router = $e->getRouter();
        $url = $router->assemble(array(), array('name' => $routeName));
        $response = $e->getResponse();
        $response->getHeaders()->addHeaderLine('Location', $url);
        $response->setStatusCode($statusCode);
        return $response;
    }
}

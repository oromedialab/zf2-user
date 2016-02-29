<?php
/**
 * @author Ibrahim Azhar <azhar@iarmar.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Oml\Zf2User\Controller;

use Zend\Form\FormInterface;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Mvc\Controller\AbstractActionController;

use Oml\Zf2User\Entity\Password;

class AccountController extends AbstractActionController
{
	public function registerAction()
	{
		$sm = $this->getServiceLocator();
		$em = $sm->get('Doctrine\ORM\EntityManager');
		$request = $this->getRequest();
		$form = $sm->get('FormElementManager')->get('Oml\Zf2User\Form\Register');
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid($request->getPost())) {
				$data = $form->getData();
				$password = $data['password'];
				unset($data['password']);
				// Initialize User Entity Using SM
				$user = $sm->get('Oml\Zf2User\Entity\User');
				// Initialize and apply values to user entity using ClassMethods Hydrator
				$hydrator = $sm->get('Oml\Zf2User\Hydrator\ClassMethods');
				$hydrator->hydrate($data, $user);
				// Overwrite password using Bcrypt
				$user->setPassword(new Password\Bcrypt($password));
				$em->persist($user);
				$em->flush();
				$this->redirect()->refresh();
			}
		}
		return new ViewModel(array(
			'form' => $form
		));
	}

	public function signInAction()
	{
		$sm = $this->getServiceLocator();
		$request = $this->getRequest();
		$matchedRoute = $sm->get('Application')->getMvcEvent()->getRouteMatch();
		$form = $sm->get('FormElementManager')->get($sm->get('AliasManager')->alias('Form\SignIn'));
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid($request->getPost())) {
				$data = $form->getData();
				$authService = $sm->get('Zend\Authentication\AuthenticationService');
				$adapter = $authService->getAdapter();
    			$adapter->setIdentityValue($data['email']);
    			$adapter->setCredentialValue($data['password']);
    			$authResult = $authService->authenticate();
    			if (!$authResult->isValid()) {
    				foreach ($authResult->getMessages() as $message) {
    					$this->flashMessenger()->addMessage(array('error' => $message));
    					break;
    				}
    			}
    			if ($authResult->isValid()) {
                    $identity = $authResult->getIdentity();
                    $authService->getStorage()->write($identity);
                    return $this->redirect()->toRoute('home');
                }
			}
		}
		return new ViewModel(array(
			'form' => $form
		));
	}

	public function profileAction()
	{
		$sm = $this->getServiceLocator();
		$em = $sm->get('Doctrine\ORM\EntityManager');
		$params = $this->params()->fromRoute();
		$request = $this->getRequest();
		$matchedRoute = $sm->get('Application')->getMvcEvent()->getRouteMatch();
		$user = $em->getRepository('\Oml\Zf2User\Entity\User')->findOneBy(array('slug' => $params['slug']));
		if (!$user) {
			$this->getResponse()->setStatusCode(404);
            return;
		}
		$request = $this->getRequest();
		$form = $sm->get('FormElementManager')->get('Oml\Zf2User\Form\Profile');
		$form->bind($user);
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid($request->getPost())) {
				$data = $form->getData(FormInterface::VALUES_AS_ARRAY);
				$hydrator = $sm->get('Oml\Zf2User\Hydrator\ClassMethods');
				$hydrator->hydrate($data, $user);
				$em->persist($user);
				$em->flush();
				$this->redirect()->toRoute($matchedRoute->getMatchedRouteName(), array('slug' => $user->getSlug()));
			}
		}
		return new ViewModel(array(
			'form' => $form
		));
	}

	public function changePasswordAction()
	{
		$sm = $this->getServiceLocator();
		$em = $sm->get('Doctrine\ORM\EntityManager');
		$params = $this->params()->fromRoute();
		$request = $this->getRequest();
		$matchedRoute = $sm->get('Application')->getMvcEvent()->getRouteMatch();
		$user = $em->getRepository('\Oml\Zf2User\Entity\User')->findOneBy(array('slug' => $params['slug']));
		if (!$user) {
			$this->getResponse()->setStatusCode(404);
            return;
		}
		$request = $this->getRequest();
		$form = $sm->get('FormElementManager')->get('Oml\Zf2User\Form\ChangePassword');
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid($request->getPost())) {
				$data = $form->getData();
				$user->setPassword(new Password\Bcrypt($data['password']));
				$em->persist($user);
				$em->flush();
				$this->redirect()->toRoute($matchedRoute->getMatchedRouteName(), array('slug' => $user->getSlug()));
			}
		}
		return new ViewModel(array(
			'form' => $form
		));
	}

	public function signOutAction()
	{
		$auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        if ($auth->hasIdentity()) {
            $auth->clearIdentity();
            $sessionManager = new \Zend\Session\SessionManager();
            $sessionManager->forgetMe();
        }
        return $this->redirect()->toRoute('om-zf2-user-account-sign-in');
	}
}

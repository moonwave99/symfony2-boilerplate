<?php

namespace MWLabs\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use MWLabs\RestBundle\Controller\RestController;
use MWLabs\RestBundle\Form\Type\UserType;

class UserController extends RestController
{

    public function getUsersAction()
    {

		$users = array_merge(
					array_filter(
						$this -> get('fos_user.user_manager') -> findUsers() ,
						function($user){ return !$user -> hasRole('ROLE_SUPER_ADMIN');}
					)
				);
		
        $view = View::create()
          ->setStatusCode(200)
          ->setData($users);

        return $this->get('fos_rest.view_handler')->handle($view);

	} // "get_users"    [GET] /users

    public function getUserAction($id)
    {

		$user = $this -> getDoctrine() -> getEntityManager() -> getRepository('UserBundle:User') -> findOneById($id);
		
		if(count($user) === 0)
			return $this -> notFound();

	    $view = View::create()
	          ->setStatusCode(200)
	          ->setData($user);
        return $this->get('fos_rest.view_handler')->handle($view);

	} // "get_user"     [GET] /users/{id}
	
	public function postUsersAction(Request $request)
	{
	
		$um = $this -> get('fos_user.user_manager');

		$user = $um -> createUser();
		$form = $this -> createForm(new UserType(), $user);
		
		$form -> bindRequest($request);

		if($form -> isValid()){
			
			$um -> updateUser($user);
			
		    $view = View::create()
		          ->setStatusCode(201)
		          ->setData(NULL);
	        return $this->get('fos_rest.view_handler')->handle($view);		
			
		}else{
			
			return $this -> missing("Check sent fields.", $user);
			
		}
		
	} // "post_users"     [POST] /users

	public function putUsersAction($id, Request $request)
	{		
		
		$user = $this -> getDoctrine() -> getEntityManager() -> getRepository('UserBundle:User') -> findOneById($id);

		if($user === NULL)
			return $this -> notFound();		

		$form = $this -> createForm(new UserType(), $user);

		$form -> bindRequest($request);

		if($form -> isValid()){
			
			$this -> getDoctrine() -> getEntityManager() -> persist($user);
			$this -> getDoctrine() -> getEntityManager() -> flush();
		
		    $view = View::create()
		          ->setStatusCode(200)
		          ->setData($user);
		
	        return $this->get('fos_rest.view_handler')->handle($view);		
			
		}else{

			return $this -> missing("Check sent fields.", $user);
			
		}
		
	} // "put_users"     [PUT] /users/{id}
	
	public function deleteUsersAction($id, Request $request)
	{
		
		$user = $this -> getDoctrine() -> getEntityManager() -> getRepository('UserBundle:User') -> findOneById($id);
		
		if($user === NULL)
			return $this -> notFound();			
			
		if($user -> hasRole('ROLE_SUPER_ADMIN') || $this -> get('security.context') -> getToken() -> getUser() -> getId() === $user -> getId() )
			return $this -> forbid('You cannot delete this user.');
			
		$user -> getMainLeagues() -> map(function($league){ $league -> removeManager(); });			
			
		$this -> getDoctrine() -> getEntityManager() -> remove($user);
		$this -> getDoctrine() -> getEntityManager() -> flush();
		
	    $view = View::create()
	          ->setStatusCode(204)
	          ->setData(NULL);
        return $this->get('fos_rest.view_handler')->handle($view);		
		
	} // "delete_users"     [DELETE] /users/{id}

}
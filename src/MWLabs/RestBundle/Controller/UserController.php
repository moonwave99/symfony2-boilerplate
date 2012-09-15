<?php

/*
 * This file is part of the Symfony2-Boilerplate repository.
 *
 * (c) Diego Caponera <http://moonwave99.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MWLabs\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use MWLabs\RestBundle\Controller\RestController;
use MWLabs\RestBundle\Form\Type\UserType;

/**
 * Controller for handling requests on User entity.
 *
 * @author Diego Caponera <diego.caponera@gmail.com>
 */
class UserController extends RestController
{

    /**
	 * Prepares a 200 OK Response for the whole list of users.
	 *
     * @return Response Serialized representation of entity list
     */
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

    /**
	 * Prepares a 200 OK Response for selected user, or a 404 Not Found one if it doesn't exist.
	 *
     * @param int $id Numeric id of selected entity	
     * @return Response Serialized representation of entity list
     */
    public function getUserAction($id)
    {

		return $this -> getEntity('MWLabs\UserBundle\Entity\User', $id);

	} // "get_user"     [GET] /users/{id}

    /**
	 * Creates a new user from Request data, answers with a 201 Created representation of such user or with a 400 Bad Request error list if any.
	 *
     * @param Request $request Current request instance
     * @return Response Serialized representation of entity list
     */
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

    /**
	 * Edits an existing user from Request data, answers with a 200 OK representation of such user or with a 400 Bad Request error list if any.
	 *
     * @param int $id Numeric id of selected entity
     * @param Request $request Current request instance
     * @return Response Serialized representation of modified entity
     */
	public function putUsersAction($id, Request $request)
	{

		return $this -> putEntity('MWLabs\UserBundle\Entity\User', $id, $request);

	} // "put_users"     [PUT] /users/{id}

    /**
	 * Deletes an existing user, answers with a 204 No Content response or with a 400 Bad Request error list if any.
	 *
     * @param int $id Numeric id of selected entity
     * @param Request $request Current request instance
     * @return Response Empty if deletion is successful, errors if any
     */
	public function deleteUsersAction($id, Request $request)
	{

		$user = $this -> getDoctrine() -> getEntityManager() -> getRepository('MWLabs\UserBundle\Entity\User') -> findOneById($id);

		if($user === NULL)
			return $this -> notFound();

		if($user -> hasRole('ROLE_SUPER_ADMIN') || $this -> get('security.context') -> getToken() -> getUser() -> getId() === $user -> getId() )
			return $this -> forbid('You cannot delete this user.');

		$this -> getDoctrine() -> getEntityManager() -> remove($user);
		$this -> getDoctrine() -> getEntityManager() -> flush();

	    $view = View::create()
	          ->setStatusCode(204)
	          ->setData(NULL);
        return $this->get('fos_rest.view_handler')->handle($view);

	} // "delete_users"     [DELETE] /users/{id}

}
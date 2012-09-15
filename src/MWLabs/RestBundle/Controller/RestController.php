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

/**
 * Base Controller for RestBundle controllers.
 *
 * @author Diego Caponera <diego.caponera@gmail.com>
 */
class RestController extends Controller
{

    /**
	 * Prepares a 200 OK Response for the whole list of entities.
	 *
     * @param string $entityName Namespaced entity name
     * @return Response Serialized representation of entity list
     */
	protected function getEntities($entityName)
	{

		$entities = $this -> getDoctrine() -> getEntityManager() -> getRepository($entityName) -> findAll();

	    $view = View::create()
	          ->setStatusCode(200)
	          ->setData($entities);
        return $this->get('fos_rest.view_handler')->handle($view);

	}

    /**
	 * Prepares a 200 OK Response for a single entity, or a 404 Not Found one if it doesn't exist.
	 *
     * @param string $entityName Namespaced entity name
     * @param int $id Numeric id of selected entity
     * @return Response Serialized representation of entity
     */
	protected function getEntity($entityName, $id)
	{

		$entity = $this -> getDoctrine() -> getEntityManager() -> getRepository($entityName) -> findOneById($id);

		if(count($entity) === 0){
			return $this -> notFound();			
		}

	    $view = View::create()
	          ->setStatusCode(200)
	          ->setData($entity);
        return $this->get('fos_rest.view_handler')->handle($view);

	}

    /**
	 * Creates a new entity from Request data, answers with a 201 Created representation of such entity or with a 400 Bad Request error list if any.
	 *
     * @param string $entityName Namespaced entity name
     * @param Request $request Current request instance
     * @return Response Serialized representation of created entity
     */
	protected function postEntity($entityName, Request $request)
	{

		$entity = new $entityName;

		$tokens = explode("\\", $entityName);

		$typeName = sprintf('MWLabs\RestBundle\Form\Type\%sType', array_pop($tokens) );

		if(!class_exists($typeName))
			throw new \Exception(sprintf('Class %s not found', $typeName));

		$form = $this -> createForm(new $typeName, $entity);

		$form -> bindRequest($request);

		if($form -> isValid()){

			$this -> getDoctrine() -> getEntityManager() -> persist($entity);
			$this -> getDoctrine() -> getEntityManager() -> flush();

		    $view = View::create()
		          ->setStatusCode(201)
		          ->setData($entity);

	        return $this->get('fos_rest.view_handler')->handle($view);

		}else{

			return $this -> missing("Check sent fields.", $entity);

		}

	}

    /**
	 * Edits an existing entity from Request data, answers with a 200 OK representation of such entity or with a 400 Bad Request error list if any.
	 *
     * @param string $entityName Namespaced entity name
     * @param int $id Numeric id of selected entity
     * @param Request $request Current request instance
     * @return Response Serialized representation of modified entity
     */
	protected function putEntity($entityName, $id, Request $request)
	{

		$entity = $this -> getDoctrine() -> getEntityManager() -> getRepository($entityName) -> findOneById($id);

		if($entity === NULL){
			return $this -> notFound();			
		}

		$tokens = explode("\\", $entityName);

		$typeName = sprintf('MWLabs\RestBundle\Form\Type\%sType', array_pop($tokens) );

		if(!class_exists($typeName)){
			throw new \Exception(sprintf('Class %s not found', $typeName));				
		}

		$form = $this -> createForm(new $typeName, $entity);

		$form -> bindRequest($request);

		if($form -> isValid()){

			$this -> getDoctrine() -> getEntityManager() -> flush();

		    $view = View::create()
		          ->setStatusCode(200)
		          ->setData($entity);

	        return $this->get('fos_rest.view_handler')->handle($view);

		}else{

			return $this -> missing("Check sent fields.", $entity);

		}

	}

    /**
	 * Deletes an existing entity, answers with a 204 No Content response or with a 400 Bad Request error list if any.
	 *
     * @param string $entityName Namespaced entity name
     * @param int $id Numeric id of selected entity
     * @param Request $request Current request instance
     * @return Response Empty if deletion is successful, errors if any
     */
	protected function deleteEntity($entityName, $id, Request $request)
	{

		$entity = $this -> getDoctrine() -> getEntityManager() -> getRepository($entityName) -> findOneById($id);

		if($entity === NULL){
			return $this -> notFound();			
		}

		$this -> getDoctrine() -> getEntityManager() -> remove($entity);
		$this -> getDoctrine() -> getEntityManager() -> flush();

	    $view = View::create()
	          ->setStatusCode(204)
	          ->setData(NULL);
        return $this->get('fos_rest.view_handler')->handle($view);

	}

    /**
	 * Returns a 404 Not Found response, with an error message.
	 *
     * @param string $message Error message to be displayed in response body
     * @return Response Response being rendered
     */
	protected function notFound($message = 'Resource not found.')
	{

        return $this->get('fos_rest.view_handler')->handle(
			View::create()
	          ->setStatusCode(404)
	          ->setData(array('message' => $message))
		);

	}

    /**
	 * Returns a 403 Forbidden response, with an error message.
	 *
     * @param string $message Error message to be displayed in response body
     * @return Response Response being rendered
     */
	protected function forbid($message = 'Access denied baby.')
	{

        return $this->get('fos_rest.view_handler')->handle(
			View::create()
	          ->setStatusCode(403)
	          ->setData(array('message' => $message))
		);

	}

    /**
	 * Returns a 400 Bad Request response, with an error message and a list of detailed errors.
	 *
     * @param string $message Error message to be displayed in response body
     * @return Response Response being rendered
     */
	protected function missing($message = 'Necessary data missing.', $data)
	{

		$schema = array(
			'message' => $message,
			'errors' => array()
		);

		foreach($this -> get('validator') -> validate( $data ) as $error )
		{

			$schema['errors'][ $error -> getPropertyPath() ] = $error -> getMessage();

		}

        return $this->get('fos_rest.view_handler')->handle(
			View::create()
	          ->setStatusCode(400)
	          ->setData($schema)
		);

	}

}
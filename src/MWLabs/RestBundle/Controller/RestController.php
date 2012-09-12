<?php

namespace MWLabs\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;

class RestController extends Controller
{

	protected function notFound($message = 'Resource not found.')
	{

        return $this->get('fos_rest.view_handler')->handle(
			View::create()
	          ->setStatusCode(404)
	          ->setData(array('message' => $message))
		);

	}

	protected function forbid($message = 'Access denied baby.')
	{

        return $this->get('fos_rest.view_handler')->handle(
			View::create()
	          ->setStatusCode(403)
	          ->setData(array('message' => $message))
		);

	}

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
	
	protected function renderForm($form)
	{
		
		return $this -> render('RestBundle::tryform.html.twig', array(
			'form' => $form -> createView()
		));		
		
	}

}
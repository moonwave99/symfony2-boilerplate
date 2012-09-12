<?php

namespace MWLabs\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomeController extends Controller
{

    /**
     * @Route("/")
     */
    public function indexAction()
    {

		return $this -> render('FrontendBundle::index.html.twig', array(
			'section'	=> 'home'
		));

    }

    /**
     * @Route("/search")
     */
    public function searchAction()
    {

		return new Response;

    }

}
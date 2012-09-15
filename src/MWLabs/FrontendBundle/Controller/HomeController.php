<?php

/*
 * This file is part of the Symfony2-Boilerplate repository.
 *
 * (c) Diego Caponera <http://moonwave99.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
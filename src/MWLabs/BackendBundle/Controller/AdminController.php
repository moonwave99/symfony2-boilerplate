<?php

namespace MWLabs\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use MWLabs\UserBundle\Entity\User;
use MWLabs\FrontendBundle\Entity\Article;
use MWLabs\FrontendBundle\Entity\League;

use MWLabs\RestBundle\Form\Type\UserType;
use MWLabs\RestBundle\Form\Type\ArticleType;
use MWLabs\RestBundle\Form\Type\LeagueType;


class AdminController extends Controller
{
    /**
     * @Route("/admin/index")
     */
    public function indexAction()
    {

		return $this -> render('BackendBundle::Admin/index.html.twig', array(
			'section' => 'index'
		));

    }

    /**
     * @Route("/admin/users")
     */
    public function usersAction()
    {

		$form = $this -> createForm(new UserType, new User);

		return $this -> render('BackendBundle::Admin/users.html.twig', array(
			'section'	=> 'users',
			'form'		=> $form -> createView()
		));

    }

}

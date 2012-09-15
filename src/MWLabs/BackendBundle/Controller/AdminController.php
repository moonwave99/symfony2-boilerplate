<?php

/*
 * This file is part of the Symfony2-Boilerplate repository.
 *
 * (c) Diego Caponera <http://moonwave99.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MWLabs\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use MWLabs\UserBundle\Entity\User;

use MWLabs\RestBundle\Form\Type\UserType;


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

<?php

/*
 * This file is part of the Symfony2-Boilerplate repository.
 *
 * (c) Diego Caponera <http://moonwave99.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MWLabs\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Basic user entity extending FOS default model.
 *
 * @author Diego Caponera <diego.caponera@gmail.com>
 */

/**
 * @ORM\Entity
 * @ORM\Table(name="app_user")
 * @UniqueEntity(fields="username", message="Username already used.")
 * @UniqueEntity(fields="email", message="E-mail already used.")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
	 * Default constructor.
     */
    public function __construct()
    {

        parent::__construct();

		$this -> password = md5(microtime());

    }

    /**
	 * Id setter.
	 *
     * @param int $id The id being set
     */
	public function setId($id)
	{ 
	
		$this -> id = $id;
		
	}

    /**
	 * Id getter.
	 *
     * @return int The id
     */	
	public function getId()
	{
		
		return $this -> id;
		
	}

}
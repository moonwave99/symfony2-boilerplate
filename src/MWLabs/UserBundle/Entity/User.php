<?php

namespace MWLabs\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\SerializerBundle\Annotation as Serialize;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

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
	
    public function __construct()
    {

        parent::__construct();

		$this -> password = md5(microtime());

    }

	public function setId($id){ $this -> id = $id; }
	public function getId(){ return $this -> id; }

}
<?php

/*
 * This file is part of the Symfony2-Boilerplate repository.
 *
 * (c) Diego Caponera <http://moonwave99.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MWLabs\RestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form Type for handling requests on User entity.
 *
 * @author Diego Caponera <diego.caponera@gmail.com>
 */
class UserType extends AbstractType
{
	
    /**
	 * Builds form.
	 *
     * @param FormBuilderInterface $builder Form builder instance to be injected with form elements	
     * @param Array $options form options
     */	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder -> add('username', 'text', array( 'attr' => array('class '=> 'span3') ));
        $builder -> add('email', 'email', array( 'attr' => array('class '=> 'span3') ));
		$builder -> add('enabled', 'choice', array(
		    'choices'  => array(
				0 => 'No',
				1 => 'Yes'
			),
			'help_block' => 'Users will not be able to login until enabled.',
			'attr' => array('class '=> 'span1'),			
		));
		$builder -> add('id', 'hidden');

    }

    /**
	 * Form name getter.
	 *
     * @return String form name
     */
    public function getName()
    {
        return 'user';
    }

    /**
	 * Gets default form options
	 *
     * @param Array $options existing options
     * @return Array form options
     */
	public function getDefaultOptions(array $options)
	{

	    return array(
	        'data_class'		=> 'MWLabs\UserBundle\Entity\User',
			'csrf_protection'	=> false
	    );
	
	}

}
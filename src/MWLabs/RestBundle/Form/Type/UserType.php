<?php

namespace MWLabs\RestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder -> add('username');
        $builder -> add('email', 'email');
		$builder -> add('enabled', 'choice', array(
		    'choices'  => array(
				0 => 'No',
				1 => 'Yes'
			),
		));
		$builder -> add('id', 'hidden');

    }

    public function getName()
    {
        return 'user';
    }

	public function getDefaultOptions(array $options)
	{

	    return array(
	        'data_class'		=> 'MWLabs\UserBundle\Entity\User',
			'csrf_protection'	=> false
	    );
	
	}

}
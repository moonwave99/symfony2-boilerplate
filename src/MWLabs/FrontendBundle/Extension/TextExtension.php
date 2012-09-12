<?php

namespace MWLabs\FrontendBundle\Extension;

class TextExtension extends \Twig_Extension {

    public function getName()
    {

  		return 'text';

    }

    public function getFilters()
    {

  		return array(
            'truncate' => new \Twig_Filter_Method($this, 'truncate'),
			'url_decode' => new \Twig_Filter_Method($this, 'urlDecode'),
			'percent_spaces' => new \Twig_Filter_Method($this, 'percentSpaces')
        );

    }

    public function truncate($text, $max = 30)
    {

     	$lastSpace = 0;

        if (strlen($text) >= $max)
        {
            $text = substr($text, 0, $max);
            $lastSpace = strrpos($text,' ');
            $text = substr($text, 0, $lastSpace).'...';
        }

        return $text;

    }

	/**
	 * URL Decode a string
	 *
	 * @param string $url
	 *
	 * @return string The decoded URL
	 */
	public function urlDecode( $url )
	{

		return urldecode( $url );

	}
	
	public function percentSpaces( $string )
	{

		return str_replace(' ', '%20', $string );

	}	

    public function getFunctions()
    {

        return array();

    }

}
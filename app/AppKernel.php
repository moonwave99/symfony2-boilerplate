<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
			new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
			new JMS\SerializerBundle\JMSSerializerBundle($this),
			new JMS\AopBundle\JMSAopBundle(),
			new JMS\DiExtraBundle\JMSDiExtraBundle($this),			
			new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
			new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),			
			new FOS\UserBundle\FOSUserBundle(),
			new FOS\RestBundle\FOSRestBundle(),
			new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
			new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),			
			new MWLabs\RestBundle\RestBundle(),					
			new MWLabs\UserBundle\UserBundle(),			
			new MWLabs\FrontendBundle\FrontendBundle(),
			new MWLabs\BackendBundle\BackendBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}

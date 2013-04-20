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
            //new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),

            new Dizda\CoreBundle\CoreBundle(),
            new Dizda\CrawlerBundle\CrawlerBundle(),

            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Dizda\SiteBundle\SiteBundle(),

            //new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Knp\Bundle\TimeBundle\KnpTimeBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Dizda\UserBundle\UserBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Mopa\Bundle\BootstrapSandboxBundle\MopaBootstrapSandboxBundle();
            $bundles[] = new Liip\ThemeBundle\LiipThemeBundle();
            $bundles[] = new Knp\Bundle\MenuBundle\KnpMenuBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}

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
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new ZawidawieInfo\UserBundle\ZawidawieInfoUserBundle(),
            new ZawidawieInfo\CoreBundle\ZawidawieInfoCoreBundle(),
            new ZawidawieInfo\PagerBundle\ZawidawieInfoPagerBundle(),
//             new ZawidawieInfo\CommentBundle\ZawidawieInfoCommentBundle(),
            new ZawidawieInfo\ForumBundle\ZawidawieInfoForumBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Herzult\Bundle\ForumBundle\HerzultForumBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
	    new Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
//             new Symfony\Bundle\DoctrineFixturesBundle\DoctrineFixturesBundle(),
            new Vich\GeographicalBundle\VichGeographicalBundle(),
            new FOS\CommentBundle\FOSCommentBundle(),
            new FPN\TagBundle\FPNTagBundle(),
            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
//             new Mach\SavelBundle\MachSavelBundle(),
            new Nekland\Bundle\FeedBundle\NeklandFeedBundle(),
	    new Sonata\BlockBundle\SonataBlockBundle(),
// 	    new Sonata\CacheBundle\SonataCacheBundle(),
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

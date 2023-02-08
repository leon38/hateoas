<?php

namespace Tests\HateOas\HateOasBundle\Fixtures;

use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use HateOas\HateOasBundle\HateOasHateOasBundle;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function getCacheDir(): string
    {
        return $this->getProjectDir().'/var/cache/'.$this->environment;
    }

    public function getLogDir(): string
    {
        return $this->getProjectDir().'/var/log';
    }

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new SensioFrameworkExtraBundle();
        yield new HateOasHateOasBundle();
    }

    protected function configureContainer(ContainerConfigurator $container, LoaderInterface $loader)
    {
        $container->extension('framework', [
            'secret' => 'S0ME_SECRET',
            'test' => true,
        ]);

        $container->import(__DIR__.'/../Resources/config.yaml');

        $container->import(__DIR__.'/../Resources/services.yaml');
    }

    protected function configureRoutes(RoutingConfigurator $routes)
    {
        $routes->import(__DIR__.'/../Resources/routes.yaml');
    }
}

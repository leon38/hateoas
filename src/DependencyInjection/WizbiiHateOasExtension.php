<?php

namespace Wizbii\HateOasBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Wizbii\HateOasBundle\Listener\HateOasListener;

class WizbiiHateOasExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @param array<string, mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->register(HateOasListener::class)
            ->setAutoconfigured(true)
            ->setAutowired(true)
        ;
    }
}

<?php

namespace Tests\HateOas\HateOasBundle\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use HateOas\HateOasBundle\DependencyInjection\HateOasHateOasExtension;
use HateOas\HateOasBundle\Listener\HateOasListener;

class HateOasExtensionTest extends TestCase
{
    public function testItProvidesAHateOasListenerService()
    {
        $ext = new HateOasExtension();
        $container = new ContainerBuilder();

        $ext->load([], $container);

        $this->assertTrue($container->has(HateOasListener::class));
    }
}

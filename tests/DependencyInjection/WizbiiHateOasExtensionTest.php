<?php

namespace Tests\Wizbii\HateOasBundle\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wizbii\HateOasBundle\DependencyInjection\WizbiiHateOasExtension;
use Wizbii\HateOasBundle\Listener\HateOasListener;

class WizbiiHateOasExtensionTest extends TestCase
{
    public function testItProvidesAHateOasListenerService()
    {
        $ext = new WizbiiHateOasExtension();
        $container = new ContainerBuilder();

        $ext->load([], $container);

        $this->assertTrue($container->has(HateOasListener::class));
    }
}

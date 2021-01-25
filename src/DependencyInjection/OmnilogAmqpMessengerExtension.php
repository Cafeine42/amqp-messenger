<?php

namespace Omnilog\AmqpMessengerBundle\DependencyInjection;

use Psr\Container\ContainerInterface as PsrContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class OmnilogAmqpMessengerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(\dirname(__DIR__).'/Resources/config'));

        $container->registerAliasForArgument('parameter_bag', PsrContainerInterface::class);

        $loader->load('service.xml');

        if (class_exists(Application::class)) {
            $loader->load('console.xml');
        }
    }
}

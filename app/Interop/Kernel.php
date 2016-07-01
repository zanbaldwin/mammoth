<?php
declare(strict_types=1);

namespace App\Interop;

use Interop\Container\ContainerInterface;
use Symfony\Bridge\ProxyManager\LazyProxy\Instantiator\RuntimeInstantiator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

abstract class Kernel extends BaseKernel
{
    /**
     * {@inheritDoc]
     */
    protected function getContainerBaseClass() : string
    {
        return Container::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getContainerBuilder()
    {
        /** @var \Symfony\Component\DependencyInjection\ContainerBuilder $container */
        $container = new class(new ParameterBag($this->getKernelParameters()))
            extends ContainerBuilder
            implements ContainerInterface
        {};

        if (class_exists('ProxyManager\Configuration')
            && class_exists('Symfony\Bridge\ProxyManager\LazyProxy\Instantiator\RuntimeInstantiator')
        ) {
            $container->setProxyInstantiator(new RuntimeInstantiator());
        }
        $container->setAlias('interop_container', 'service_container');
        return $container;
    }
}

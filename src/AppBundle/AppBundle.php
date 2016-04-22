<?php

namespace AppBundle;

use AppBundle\DependencyInjection\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Console\Application;

class AppBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function getContainerExtension()
    {
        return new Extension;
    }

    /**
     * {@inheritDoc}
     */
    public function registerCommands(Application $application)
    {
        // Do NOT register your commands here.
        // Use the "console.command" service container tag instead!
    }
}

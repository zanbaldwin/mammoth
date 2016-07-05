<?php
declare(strict_types=1);

namespace AppBundle;

use AppBundle\DependencyInjection\Extension;
use Symfony\Component\Console\Application;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    /**
     * Return an instance of the bundle extension, hard-coded instead of guessed
     * automagically from opinionated class naming.
     */
    public function getContainerExtension() : Extension
    {
        return new Extension;
    }

    /**
     * Override \Symfony\Component\HttpKernel\Bundle\Bundle::registerCommands().
     * Prevent bundle commands from being registered automagically; register them
     * as tagged services instead.
     */
    public function registerCommands(Application $application)
    {
    }

    public function getParent() : string
    {
        return 'FOSUserBundle';
    }
}

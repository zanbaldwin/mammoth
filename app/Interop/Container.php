<?php
declare(strict_types=1);

namespace App\Interop;

use Interop\Container\ContainerInterface as InteropContainerInterface;
use Symfony\Component\DependencyInjection\Container as SymfonyContainer;

class Container extends SymfonyContainer implements InteropContainerInterface
{
}

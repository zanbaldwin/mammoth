<?php

use Darsyn\Stack\RequestId\Injector as RequestIdInjector;
use Darsyn\Stack\RequestId\UuidGenerator;
use Stack\Builder as StackBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\TerminableInterface;

/**
 * @var Composer\Autoload\ClassLoader
 */
$loader = require __DIR__ . '/../app/autoload.php';
include_once __DIR__ . '/../var/bootstrap.php.cache';

// Enable APC for autoloading to improve performance. You should change the ApcClassLoader first argument to a unique
// prefix in order to prevent cache key conflicts with other applications also using APC.
# $apcLoader = new Symfony\Component\ClassLoader\ApcClassLoader(sha1(__FILE__), $loader);
# $loader->unregister();
# $apcLoader->register(true);

$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();
# $kernel = new AppCache($kernel);

$stack = (new StackBuilder)
    ->push(RequestIdInjector::class, new UuidGenerator(getenv('APPLICATION_NODE') ?: null))
    ->resolve($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the
// configuration parameter
# Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $stack->handle($request);
$response->send();
$kernel instanceof TerminableInterface && $kernel->terminate($request, $response);

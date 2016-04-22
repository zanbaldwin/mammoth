<?php

use Darsyn\Stack\IpRestrict\Whitelist as IpWhitelist;
use Darsyn\Stack\RequestId\Injector as RequestIdInjector;
use Darsyn\Stack\RequestId\UuidGenerator;
use Stack\Builder as StackBuilder;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\TerminableInterface;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line. For more information,
// read http://symfony.com/doc/current/book/installation.html#checking-symfony-application-configuration-and-setup
# umask(0000);

/**
 * @var Composer\Autoload\ClassLoader $loader
 */
$loader = require __DIR__ . '/../app/autoload.php';
Debug::enable();

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();

$stack = (new StackBuilder)
    ->push(IpWhitelist::class, ['127.0.0.1', 'fe80::1', '::1', '192.168.0.0/16'])
    ->push(RequestIdInjector::class, new UuidGenerator(getenv('APPLICATION_NODE') ?: null))
    ->resolve($kernel);

$request = Request::createFromGlobals();
$response = $stack->handle($request);
$response->send();
$kernel instanceof TerminableInterface && $kernel->terminate($request, $response);

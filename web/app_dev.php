<?php
declare(strict_types=1);

use Darsyn\Stack\IpRestrict\Whitelist;
use Darsyn\Stack\RequestId\Injector;
use Darsyn\Stack\RequestId\UuidGenerator;
use Stack\Builder as StackBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpKernel\TerminableInterface;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line. For more information,
// read http://symfony.com/doc/current/book/installation.html#checking-symfony-application-configuration-and-setup
# umask(0000);

/**
 * @var Composer\Autoload\ClassLoader $loader
 */
$loader = require __DIR__.'/../app/autoload.php';
Debug::enable();

// In the main front-controller, we load up environmental variables from ".env" - since that is just to change the
// configuration on-the-fly without having to change any source files in production we won't bother including it in
// this front-controller.

$kernel = new App\Kernel('dev', true);
$kernel->loadClassCache();

$stack = (new StackBuilder)
    ->push(Whitelist::class, ['127.0.0.1', 'fe80::1', '::1', '192.168.0.0/16'])
    ->push(Injector::class, new UuidGenerator(getenv('APPLICATION_NODE') ?: null))
    ->resolve($kernel);

$request = Request::createFromGlobals();
$response = $stack->handle($request);
$response->send();
$kernel instanceof TerminableInterface && $kernel->terminate($request, $response);

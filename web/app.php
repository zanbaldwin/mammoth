<?php
declare(strict_types=1);

use Darsyn\Stack\RequestId\Injector;
use Darsyn\Stack\RequestId\UuidGenerator;
use Dotenv\Dotenv;
use Stack\Builder as StackBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\TerminableInterface;

/**
 * @var Composer\Autoload\ClassLoader
 */
$loader = require __DIR__.'/../app/autoload.php';
include_once __DIR__.'/../var/bootstrap.php.cache';

// If you need to make changes to the parameters in an emergency, but don't want to change the configuration parameters
// (those values may be specific to the current installation) then add "PARAMETER=VALUE" pairs (one per line) to the
// ".env" file in the parent directory in the External Parameter format:
//     http://symfony.com/doc/current/cookbook/configuration/external_parameters.html
// Remember that if you make any changes to that file (including deletion), you need to clear the cache!
if (file_exists(sprintf('%s%s', $dir = __DIR__ . '/..', $file = '.env'))) {
    $dotEnv = new Dotenv($dir, $file);
    $dotEnv->load();
}

$kernel = new App\Kernel('prod', false);
$kernel->loadClassCache();

$stack = (new StackBuilder)
    # ->push(App\AppCache::class)
    ->push(Injector::class, new UuidGenerator(getenv('APPLICATION_NODE') ?: null))
    ->resolve($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the
// configuration parameter
# Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $stack->handle($request);
$response->send();
$kernel instanceof TerminableInterface && $kernel->terminate($request, $response);

<?php
declare(strict_types=1);

namespace App;

use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends BaseKernel
{
    public function registerBundles() : array
    {
        $bundles = [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle,
            new \Symfony\Bundle\SecurityBundle\SecurityBundle,
            new \Symfony\Bundle\TwigBundle\TwigBundle,
            new \Symfony\Bundle\MonologBundle\MonologBundle,
            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle,
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle,
            new \Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle,
            new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle,
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle,

            new \AppBundle\AppBundle,
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new \Symfony\Bundle\DebugBundle\DebugBundle;
            $bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
            $bundles[] = new \Sensio\Bundle\DistributionBundle\SensioDistributionBundle;
            $bundles[] = new \Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle;
        }

        return $bundles;
    }

    public function getRootDir() : string
    {
        return __DIR__;
    }

    public function getCacheDir() : string
    {
        return sprintf('%s/var/cache/%s', dirname($this->getRootDir()), $this->getEnvironment());
    }

    public function getLogDir() : string
    {
        return sprintf('%s/var/logs', dirname($this->getRootDir()));
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $config = sprintf('%s/config/config_%s.yml', $this->getRootDir(), $this->getEnvironment());
        if (!file_exists($config)) {
            throw new \RuntimeException(sprintf(
                'Environment "%s" not supported.',
                $this->getEnvironment()
            ));
        }
        $loader->load($config);
        // Now we have loaded our configuration (and more importantly, parameters) - reload all environmental variables
        // named for Symfony into the parameter bag so that we can override the configuration quickly in an emergancy
        // without amending any application files (which may be specific to the current installation).
        // Don't forget to clear the cache after changing the environmental variables!
        $envParameters = $this->getEnvParameters();
        $loader->load(function ($container) use ($envParameters) {
            /** @var \Symfony\Component\DependencyInjection\Container $container */
            $container->getParameterBag()->add($envParameters);
        });
    }
}

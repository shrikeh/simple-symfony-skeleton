<?php

declare(strict_types=1);

use App\ServiceProvider\BundleProvider;
use Shrikeh\TestSymfonyApp\Console\Application;
use Shrikeh\TestSymfonyApp\Kernel\Environment\Environment;
use Shrikeh\TestSymfonyApp\ServiceProvider\ConsoleProvider;
use Shrikeh\TestSymfonyApp\ServiceProvider\KernelProvider;
use Symfony\Component\Console\Input\InputInterface;

function console()
{
    if (false === in_array(PHP_SAPI, ['cli', 'phpdbg', 'embed'], true)) {
        echo sprintf(
            'Warning: The console should be invoked via the CLI version of PHP, not the %s SAPI' . PHP_EOL,
            PHP_SAPI
        );
    }

    set_time_limit(0);

    $container = KernelProvider::create();
    $container->register(new BundleProvider());
    $serviceLocator = ConsoleProvider::serviceLocator($container);


    $input = $serviceLocator->get(InputInterface::class);

    if (null !== $env = $input->getParameterOption(['--env', '-e'], null, true)) {
        putenv('APP_ENV=' . $_SERVER[Environment::SERVER_APP_ENV] = $_ENV['APP_ENV'] = $env);
    }

    if ($input->hasParameterOption('--no-debug', true)) {
        putenv('APP_DEBUG=' . $_SERVER[Environment::SERVER_APP_DEBUG] = $_ENV['APP_DEBUG'] = '0');
    }
    /** @var Application $application */
    $application = $serviceLocator->get(Application::class);

    if ($application->isDebug()) {
        umask(0000);
    }

    $application->run($input);
}

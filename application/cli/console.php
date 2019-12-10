<?php

declare(strict_types=1);

use App\Console\Application;
use App\Kernel\Environment\Environment;
use App\ServiceProvider\ConsoleProvider;
use Symfony\Component\Console\Input\InputInterface;

require_once dirname(__DIR__) . '/bootstrap.php';

if (false === in_array(PHP_SAPI, ['cli', 'phpdbg', 'embed'], true)) {
    echo 'Warning: The console should be invoked via the CLI version of PHP, not the ' . PHP_SAPI . ' SAPI' . PHP_EOL;
}

set_time_limit(0);

$container = ConsoleProvider::serviceLocator();

$input = $container->get(InputInterface::class);

if (null !== $env = $input->getParameterOption(['--env', '-e'], null, true)) {
    putenv('APP_ENV=' . $_SERVER[Environment::SERVER_APP_ENV] = $_ENV['APP_ENV'] = $env);
}

if ($input->hasParameterOption('--no-debug', true)) {
    putenv('APP_DEBUG=' . $_SERVER[Environment::SERVER_APP_DEBUG] = $_ENV['APP_DEBUG'] = '0');
}
/** @var Application $application */
$application = $container->get(Application::class);

if ($application->isDebug()) {
    umask(0000);
}

$application->run($input);

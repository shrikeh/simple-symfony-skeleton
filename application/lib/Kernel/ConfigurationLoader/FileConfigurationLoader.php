<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Kernel\ConfigurationLoader;

use Shrikeh\TestSymfonyApp\Kernel\Environment\EnvironmentInterface;
use Generator;
use Symfony\Component\Config\Loader\LoaderInterface;

final class FileConfigurationLoader implements ConfigurationLoaderInterface
{
    /**
     * @var string
     */
    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';
    /**
     * @var string
     */
    private const TYPE_GLOB = 'glob';

    /** @var EnvironmentInterface */
    private EnvironmentInterface $environment;
    /**
     * @var string
     */
    private string $configDir;

    /**
     * FileConfigurationLoader constructor.
     * @param EnvironmentInterface $environment
     * @param string $configDir
     */
    public function __construct(
        EnvironmentInterface $environment,
        string $configDir
    ) {
        $this->environment = $environment;
        $this->configDir = $configDir;
    }

    /**
     * @param LoaderInterface $loader
     * @throws \Exception
     */
    public function loadConfig(LoaderInterface $loader): void
    {
        $loader->load($this->getAppConfig());
        $this->loadConfigs($loader);
    }

    /**
     * @param LoaderInterface $loader
     * @throws \Exception
     */
    private function loadConfigs(LoaderInterface $loader): void
    {
        foreach ($this->getConfigGlobs() as $glob) {
            $loader->load($glob, static::TYPE_GLOB);
        }
    }

    /**
     * @return Generator
     */
    private function getConfigGlobs(): Generator
    {
        $environment = $this->environment->getName();

        yield sprintf('%s/{packages}/*%s', $this->configDir, self::CONFIG_EXTS);
        yield sprintf('%s/{packages}/%s/**/*/%s', $this->configDir, $environment, self::CONFIG_EXTS);
        yield sprintf('%s/{services}/%s', $this->configDir, self::CONFIG_EXTS);
        yield sprintf('%s/{services}/_%s', $this->configDir, self::CONFIG_EXTS);
    }

    /**
     * @return string
     */
    private function getAppConfig(): string
    {
        return $this->configDir . '/app.yml';
    }
}

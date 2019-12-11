<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Kernel\Traits;

use Shrikeh\TestSymfonyApp\Kernel\Environment\EnvironmentInterface;

trait EnvironmentTrait
{
    /**
     * @var EnvironmentInterface
     */
    private EnvironmentInterface $environment;

    /**
     * {@inheritDoc}
     */
    public function getCharset(): string
    {
        return $this->environment->getCharset();
    }

    /**
     * {@inheritDoc}
     */
    public function getEnvironment(): string
    {
        return $this->environment->getName();
    }

    /**
     * {@inheritDoc}
     */
    public function isDebug(): bool
    {
        return $this->environment->isDebug();
    }

    /**
     * @param string $key
     * @param null $defaultValue
     * @return string
     */
    private function getFromServerBag(string $key, $defaultValue = null): ?string
    {
        return $this->environment->getServerBag()->get($key, $defaultValue);
    }
}

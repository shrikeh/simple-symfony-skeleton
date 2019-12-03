<?php

declare(strict_types=1);

namespace App\Kernel\Environment;

use App\Kernel\Environment\Exception\UnrecognisedEnvironment;
use Symfony\Component\HttpFoundation\ServerBag;

final class Environment implements EnvironmentInterface
{
    public const CHARSET = 'UTF-8';

    public const SERVER_APP_ENV = 'APP_ENV';
    public const SERVER_APP_DEBUG = 'APP_DEBUG';

    public const KEY_SHELL_VERBOSITY = 'SHELL_VERBOSITY';
    public const SHELL_VERBOSITY_LEVEL = 3;

    /**
     * @var ServerBag
     */
    private ServerBag $serverBag;
    /**
     * @var bool
     */
    private bool $debug;
    /**
     * @var string
     */
    private $environment;

    /**
     * @param ServerBag $serverBag
     * @param bool|null $debug
     * @return Environment
     */
    public static function fromServerBag(ServerBag $serverBag, bool $debug = null): self
    {
        $env = $serverBag->get(static::SERVER_APP_ENV);
        $debug = $debug ?? $serverBag->getBoolean(static::SERVER_APP_DEBUG);

        return new static($serverBag, $env, $debug);
    }

    /**
     * @param string $environment
     * @param array|null $server
     * @param bool $debug
     * @return Environment
     */
    public static function create(string $environment, array $server = null, bool $debug = false): self
    {
        if (null === $server) {
            $server = $_SERVER;
        }

        return new self(new ServerBag($server), $environment, $debug);
    }


    /**
     * Environment constructor.
     * @param ServerBag $serverBag
     * @param string $environment
     * @param bool $debug
     */
    public function __construct(ServerBag $serverBag, string $environment, bool $debug)
    {
        if (!in_array($environment, static::ALLOWED_ENVS, true)) {
            throw UnrecognisedEnvironment::create($environment);
        }
        $this->serverBag = $serverBag;
        $this->environment = $environment;
        $this->debug = $debug;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->environment;
    }

    /**
     * {@inheritDoc}
     */
    public function getServerBag(): ServerBag
    {
        return $this->serverBag;
    }

    /**
     * {@inheritDoc}
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * {@inheritDoc}
     */
    public function getCharset(): string
    {
        return self::CHARSET;
    }

    /**
     * {@inheritDoc}
     */
    public function setDebugShellVerbosity(): void
    {
        if ($this->isDebug()) {
            if (!isset($_ENV[self::KEY_SHELL_VERBOSITY]) && !isset($_SERVER[self::KEY_SHELL_VERBOSITY])) {
                $this->setShellVerbosityLevel(static::SHELL_VERBOSITY_LEVEL);
            }
        }
    }

    /**
     * @param int $level
     */
    private function setShellVerbosityLevel(int $level): void
    {
        putenv(sprintf('%s=%d', self::KEY_SHELL_VERBOSITY, $level));
        $_ENV[self::KEY_SHELL_VERBOSITY] = $level;
        $_SERVER[self::KEY_SHELL_VERBOSITY] = $level;
    }
}

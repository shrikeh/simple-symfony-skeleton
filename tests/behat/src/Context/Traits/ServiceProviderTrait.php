<?php

declare(strict_types=1);

namespace Tests\Behat\Context\Traits;

use App\ServiceProvider\BundleProvider;
use Pimple\Container;
use Shrikeh\TestSymfonyApp\ServiceProvider\KernelProvider;

trait ServiceProviderTrait
{
    /** @var Container */
    private Container $serviceProviderContainer;

    /**
     * @beforeScenario
     */
    public function initServiceProvider(): Container
    {
        $this->serviceProviderContainer = KernelProvider::create();
        $this->serviceProviderContainer->register(new BundleProvider());

        return $this->serviceProviderContainer;
    }
}

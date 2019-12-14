<?php

declare(strict_types=1);

namespace Tests\Behat\Context\HelloWorld;

use App\Console\Command\HelloWorldCommand;
use Behat\Behat\Context\Context;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Shrikeh\TestSymfonyApp\Console\Application;
use Shrikeh\TestSymfonyApp\ServiceProvider\ConsoleProvider;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Tests\Behat\Context\Traits\ProphetTrait;
use Tests\Behat\Context\Traits\ServiceProviderTrait;

final class ConsoleContext implements Context
{
    use ProphetTrait;
    use ServiceProviderTrait {
        initServiceProvider as kernelServiceProviderInit;
    }

    private string $message;

    private ObjectProphecy $output;

    /**
     * @beforeScenario
     */
    public function initServiceProvider(): void
    {
        ConsoleProvider::create($this->kernelServiceProviderInit());
    }

    /**
     * @Given that I have the message :msg
     * @param string $msg
     */
    public function thatIHaveTheMessage(string $msg): void
    {
        $this->message = $msg;
    }

    /**
     * @When I send this message
     */
    public function iSendThisMessage(): void
    {
        $application = $this->getApplication();
        $this->output = $this->prophesize(OutputInterface::class);
        $input = new ArrayInput([
           sprintf('--%s', HelloWorldCommand::ARG_TEST_MESSAGE) => $this->message,
        ]);
        $input->setInteractive(false);
        $application->setDefaultCommand(HelloWorldCommand::NAME, true);

        $application->run(
            $input,
            $this->output->reveal()
        );
    }

    /**
     * @Then it is received
     */
    public function itIsReceived(): void
    {

        $this->output->writeln(Argument::containingString($this->message))->shouldHaveBeenCalled();
    }

    /**
     * @return Application
     */
    private function getApplication(): Application
    {
        /** @var Application $application */
        $application = $this->serviceProviderContainer[Application::class];
        $application->setAutoExit(false);

        return $application;
    }
}

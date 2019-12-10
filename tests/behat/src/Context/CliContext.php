<?php

declare(strict_types=1);

namespace Tests\Behat\Context;

use Behat\Behat\Context\Context;

final class CliContext implements Context
{
    /**
     * @Given that I have the message :msg
     * @param string $msg
     */
    public function thatIHaveTheMessage(string $msg): void
    {
        throw new PendingException();
    }

    /**
     * @When I send this message
     */
    public function iSendThisMessage()
    {
        throw new PendingException();
    }

    /**
     * @Then it is received.
     */
    public function itIsReceived()
    {
        throw new PendingException();
    }

}

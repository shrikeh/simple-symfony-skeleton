<?php

declare(strict_types=1);

namespace Tests\Behat\Context\Traits;

use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

trait ProphetTrait
{
    private Prophet $prophet;

    /**
     * @param string $className
     * @return ObjectProphecy
     */
    private function prophesize(string $className): ObjectProphecy
    {
        if (!isset($this->prophet)) {
            $this->prophet = new Prophet();
        }

        return $this->prophet->prophesize($className);
    }
}
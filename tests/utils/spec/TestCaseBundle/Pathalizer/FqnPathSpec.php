<?php

declare(strict_types=1);

namespace spec\Tests\Utils\TestCaseBundle\Pathalizer;

use PhpSpec\ObjectBehavior;
use SplFileInfo;
use Tests\Utils\UnitTest\ClassNamespace;

final class FqnPathSpec extends ObjectBehavior
{
    public function it_returns_the_base_fqn(): void
    {
        $fqn = ClassNamespace::fromNamespaceString('Tic\Tac\Toe');
        $fileInfo = new SplFileInfo(__FILE__);

        $this->beConstructedWith($fqn, $fileInfo);

        $this->getBaseFqn()->shouldReturn($fqn);
    }

    public function it_returns_the_file_info(): void
    {
        $fqn = ClassNamespace::fromNamespaceString('Tic\Tac\Toe');
        $fileInfo = new SplFileInfo(__FILE__);

        $this->beConstructedWith($fqn, $fileInfo);

        $this->getBaseDir()->shouldReturn($fileInfo);
    }
}

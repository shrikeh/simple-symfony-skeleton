<?php

declare(strict_types=1);

namespace spec\Tests\Utils\UnitTestBundle\TestCaseRenderer;

use SplFileInfo;
use Tests\Utils\UnitTest\ClassNamespace;
use Tests\Utils\UnitTest\TestCase;
use Tests\Utils\UnitTestBundle\TestCaseRenderer\TemplateData\TemplateDataInterface;
use Tests\Utils\UnitTestBundle\TestCaseRenderer\TwigTestCaseRenderer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Twig\Environment;

final class TwigTestCaseRendererSpec extends ObjectBehavior
{
    public function it_renders_a_template(
        Environment $environment,
        TemplateDataInterface $templateData
    ): void {
        $template = 'foo.twig';

        $testCase = new TestCase(
            new SplFileInfo(__FILE__),
            ClassNamespace::fromNamespaceString('Foo\BarTest'),
            ClassNamespace::fromNamespaceString('Subject\Under\Test')
        );

        $data = [
            'wibble' => 'wobble',
        ];

        $content = 'foo bar baz';

        $templateData->getTemplateData($testCase)->willReturn($data);

        $environment->render($template, $data)->willReturn($content);


        $this->beConstructedWith($environment, $templateData, $template);

        $this->render($testCase)->shouldReturn($content);
    }
}

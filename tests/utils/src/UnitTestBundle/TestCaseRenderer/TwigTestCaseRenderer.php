<?php
declare(strict_types=1);

namespace Tests\Utils\UnitTestBundle\TestCaseRenderer;

use Tests\Utils\UnitTest\TestCase;
use Tests\Utils\UnitTestBundle\TestCaseRenderer\TemplateData\TemplateDataInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class TwigTestCaseRenderer implements TestCaseRendererInterface
{
    /**
     * @var Environment
     */
    private Environment $twig;
    /**
     * @var string
     */
    private string $template;
    /**
     * @var TemplateDataInterface
     */
    private TemplateDataInterface $templateData;

    /**
     * TwigTestCaseRenderer constructor.
     * @param Environment $twig
     * @param TemplateDataInterface $templateData
     * @param string $template
     */
    public function __construct(Environment $twig, TemplateDataInterface $templateData, string $template)
    {
        $this->twig = $twig;
        $this->template = $template;
        $this->templateData = $templateData;
    }

    /**
     * @param TestCase $testCase
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(TestCase $testCase): string
    {
        return $this->twig->render(
            $this->template,
            $this->templateData->getTemplateData($testCase)
        );
    }
}
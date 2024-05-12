<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use PetrKnap\Shorts\PhpUnit\MarkdownFileTestInterface;
use PetrKnap\Shorts\PhpUnit\MarkdownFileTestTrait;
use PHPUnit\Framework\TestCase;

class ReadmeTest extends TestCase implements MarkdownFileTestInterface
{
    use MarkdownFileTestTrait;

    public static function getPathToMarkdownFile(): string
    {
        return __DIR__ . '/../README.md';
    }

    public static function getExpectedOutputsOfPhpExamples(): iterable
    {
        return [
            'example' => ''
                . 'value'
                . 'value'
                . 'value'
                . 'value'
                . 'value'
                . 'It is `value`.'
            ,
        ];
    }
}

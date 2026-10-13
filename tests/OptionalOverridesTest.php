<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;
use ReflectionNamedType;

final class OptionalOverridesTest extends OptionalTestCase
{
    #[DataProvider('dataOverridesReturnFromParentToChild')]
    public function testOverridesReturnFromParentToChild(ReflectionClass $customOptional, string $methodName): void
    {
        $methodReturnType = $customOptional->getMethod($methodName)->getReturnType();
        self::assertInstanceOf(ReflectionNamedType::class, $methodReturnType);
        self::assertSame('self', $methodReturnType->getName());
    }

    public static function dataOverridesReturnFromParentToChild(): iterable
    {
        $optional = new ReflectionClass(Optional::class);
        $customOptional = new ReflectionClass(OptionalOverrides::class);

        foreach ($optional->getMethods() as $method) {
            $methodReturnType = $method->getReturnType();
            if ($methodReturnType instanceof ReflectionNamedType && $methodReturnType->getName() === 'self') {
                yield $method->getName() => [$customOptional, $method->getName()];
            }
        }
    }

    #[DataProvider('dataHasSameDocComment')]
    public function testHasSameDocComment(string $optional, string $customOptional): void
    {
        self::assertSame($optional, $customOptional);
    }

    public static function dataHasSameDocComment(): iterable
    {
        $optional = new ReflectionClass(Optional::class);
        $customOptional = new ReflectionClass(OptionalOverrides::class);

        foreach ($customOptional->getMethods() as $customOptionalMethod) {
            yield $customOptionalMethod->getName() => [
                $optional->getMethod($customOptionalMethod->getName())->getDocComment(),
                $customOptionalMethod->getDocComment(),
            ];
        }
    }

    protected static function getClassName(): string
    {
        return Some\Optional::class;
    }
}

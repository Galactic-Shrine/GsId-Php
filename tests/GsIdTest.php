<?php

declare(strict_types=1);

namespace GalacticShrine\GsId\Tests;

use GalacticShrine\GsId\GsId;
use GalacticShrine\GsId\GsIdCase;
use GalacticShrine\GsId\GsIdConstants;
use GalacticShrine\GsId\GsIdException;
use GalacticShrine\GsId\GsIdFormat;
use GalacticShrine\GsId\GsIdOptions;
use GalacticShrine\GsId\GsIdParser;
use GalacticShrine\GsId\GsIdValidator;
use PHPUnit\Framework\TestCase;

final class GsIdTest extends TestCase
{
    private const NormalizedValue = '9F2A6C1E8D4B7A90A13F9C2DE88B421091AF77CB4D6E39A2FC018AD92E7B5C64';
    private const FormattedValue = '9F2A6C1E8D4B7A90-A13F9C2D-E88B4210-91AF77CB-4D6E39A2-FC018AD92E7B5C64';

    protected function tearDown(): void
    {
        $reflection = new \ReflectionClass(GsIdOptions::class);
        $property = $reflection->getProperty('isLocked');
        $property->setValue(null, false);
        GsIdOptions::reset();
    }

    public function testFromStringAcceptsFormatN(): void
    {
        $id = GsId::fromString(self::NormalizedValue);
        self::assertSame(self::NormalizedValue, $id->toString(GsIdFormat::N, GsIdCase::Upper));
    }

    public function testFromStringAcceptsFormatD(): void
    {
        $id = GsId::fromString(self::FormattedValue);
        self::assertSame(self::FormattedValue, $id->toString(GsIdFormat::D, GsIdCase::Upper));
    }

    public function testFromStringNormalizesLowercaseValue(): void
    {
        $id = GsId::fromString(strtolower(self::FormattedValue));
        self::assertSame(self::FormattedValue, $id->toString(GsIdFormat::D, GsIdCase::Upper));
    }

    public function testToStringSupportsLowercaseOutput(): void
    {
        $id = GsId::fromString(self::FormattedValue);

        self::assertSame(strtolower(self::NormalizedValue), $id->toString(GsIdFormat::N, GsIdCase::Lower));
        self::assertSame(strtolower(self::FormattedValue), $id->toString(GsIdFormat::D, GsIdCase::Lower));
        self::assertSame(strtolower(self::NormalizedValue), $id->normalized(GsIdCase::Lower));
    }

    public function testParserNormalizeSupportsRequestedCase(): void
    {
        self::assertSame(self::NormalizedValue, GsIdParser::normalize(strtolower(self::FormattedValue), GsIdCase::Upper));
        self::assertSame(strtolower(self::NormalizedValue), GsIdParser::normalize(self::FormattedValue, GsIdCase::Lower));
    }

    public function testDefaultCaseShouldBeCentralizedInGsIdOptions(): void
    {
        GsIdOptions::setDefaultCase(GsIdCase::Lower);
        $id = GsId::fromString(self::FormattedValue);

        self::assertSame(strtolower(self::FormattedValue), $id->toString());
        self::assertSame(strtolower(self::NormalizedValue), $id->toString(GsIdFormat::N));
        self::assertSame(strtolower(self::NormalizedValue), GsIdParser::normalize(self::FormattedValue));
    }

    public function testDefaultTextFormatShouldBeCentralizedInGsIdOptions(): void
    {
        GsIdOptions::setDefaultTextFormat(GsIdFormat::N);
        $id = GsId::fromString(self::FormattedValue);

        self::assertSame(self::NormalizedValue, $id->toString());
    }

    public function testJsonSerializeUsesGlobalDefaultJsonFormat(): void
    {
        GsIdOptions::configure(defaultCase: GsIdCase::Lower, defaultJsonFormat: GsIdFormat::N);
        $id = GsId::fromString(self::NormalizedValue);

        self::assertSame('"' . strtolower(self::NormalizedValue) . '"', json_encode($id, JSON_THROW_ON_ERROR));
    }

    public function testTryFromStringReturnsNullWhenValueIsInvalid(): void
    {
        self::assertNull(GsId::tryFromString('invalid'));
    }

    public function testNewGsIdGeneratesNonEmptyIdentifier(): void
    {
        $id = GsId::newGsId();

        self::assertFalse($id->isEmpty());
        self::assertSame(GsIdConstants::HexLength, strlen($id->toString(GsIdFormat::N, GsIdCase::Upper)));
        self::assertSame(GsIdConstants::FormattedLength, strlen($id->toString(GsIdFormat::D, GsIdCase::Upper)));
    }

    public function testEqualsComparesByValue(): void
    {
        $first = GsId::fromString(self::NormalizedValue);
        $second = GsId::fromString(self::FormattedValue);

        self::assertTrue($first->equals($second));
    }

    public function testValidatorChecksFormat(): void
    {
        self::assertTrue(GsIdValidator::isValidFormat(self::NormalizedValue, GsIdFormat::N));
        self::assertTrue(GsIdValidator::isValidFormat(self::FormattedValue, GsIdFormat::D));
        self::assertFalse(GsIdValidator::isValidFormat(self::FormattedValue, GsIdFormat::N));
    }

    public function testLockPreventsFurtherMutation(): void
    {
        GsIdOptions::configure(
            defaultCase: GsIdCase::Lower,
            defaultTextFormat: GsIdFormat::N,
            defaultJsonFormat: GsIdFormat::D,
            defaultDatabaseFormat: GsIdFormat::N,
        );

        GsIdOptions::lock();

        self::assertTrue(GsIdOptions::isLocked());
        $this->expectException(GsIdException::class);
        GsIdOptions::setDefaultCase(GsIdCase::Upper);
    }
}

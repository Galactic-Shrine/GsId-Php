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
use GalacticShrine\GsId\Symfony\GsIdOptionsConfigurator;
use GalacticShrine\GsId\GsIdValidator;
use GalacticShrine\GsId\Symfony\Bridge\GsIdToUid;
use PHPUnit\Framework\TestCase;

final class GsIdCoreTest extends TestCase
{
    private const string NormalizedUpper = '9F2A6C1E8D4B7A90A13F9C2DE88B421091AF77CB4D6E39A2FC018AD92E7B5C64';
    private const string FormattedUpper = '9F2A6C1E8D4B7A90-A13F9C2D-E88B4210-91AF77CB-4D6E39A2-FC018AD92E7B5C64';

    protected function setUp(): void
    {
        self::forceResetOptions();
    }

    protected function tearDown(): void
    {
        self::forceResetOptions();
    }

    public function testNewGsIdShouldCreateNonEmptyIdentifier(): void
    {
        $id = GsId::newGsId();

        self::assertFalse($id->isEmpty());
        self::assertSame(GsIdConstants::HexLength, strlen($id->toString(GsIdFormat::N, GsIdCase::Upper)));
        self::assertSame(GsIdConstants::FormattedLength, strlen($id->toString(GsIdFormat::D, GsIdCase::Upper)));
    }

    public function testParseShouldAcceptNAndDFormats(): void
    {
        $fromN = GsId::fromString(self::NormalizedUpper);
        $fromD = GsId::fromString(self::FormattedUpper);

        self::assertTrue($fromN->equals($fromD));
        self::assertSame(self::NormalizedUpper, $fromN->toString(GsIdFormat::N, GsIdCase::Upper));
        self::assertSame(self::FormattedUpper, $fromD->toString(GsIdFormat::D, GsIdCase::Upper));
    }

    public function testParseShouldAcceptLowercaseInput(): void
    {
        $id = GsId::fromString(strtolower(self::FormattedUpper));

        self::assertSame(self::NormalizedUpper, $id->toString(GsIdFormat::N, GsIdCase::Upper));
        self::assertSame(strtolower(self::FormattedUpper), $id->toString(GsIdFormat::D, GsIdCase::Lower));
    }

    public function testTryFromStringShouldReturnNullForInvalidValue(): void
    {
        self::assertNull(GsId::tryFromString('invalid-gsid'));
    }

    public function testValidatorShouldCheckFormat(): void
    {
        self::assertTrue(GsIdValidator::isValid(self::NormalizedUpper));
        self::assertTrue(GsIdValidator::isValid(self::FormattedUpper));
        self::assertTrue(GsIdValidator::isValid(strtolower(self::NormalizedUpper)));
        self::assertTrue(GsIdValidator::isValid(strtolower(self::FormattedUpper)));

        self::assertTrue(GsIdValidator::isValidFormat(self::NormalizedUpper, GsIdFormat::N));
        self::assertTrue(GsIdValidator::isValidFormat(self::FormattedUpper, GsIdFormat::D));
        self::assertFalse(GsIdValidator::isValidFormat(self::FormattedUpper, GsIdFormat::N));
    }

    public function testOptionsShouldControlDefaultOutput(): void
    {
        GsIdOptions::configure(
            defaultCase: GsIdCase::Lower,
            defaultTextFormat: GsIdFormat::N,
            defaultJsonFormat: GsIdFormat::D,
            defaultDatabaseFormat: GsIdFormat::N,
        );

        $id = GsId::fromString(self::FormattedUpper);

        self::assertSame(strtolower(self::NormalizedUpper), $id->toString());
        self::assertSame(strtolower(self::FormattedUpper), $id->toString(GsIdFormat::D));
    }

    public function testJsonSerializationShouldUseDefaultJsonOptions(): void
    {
        GsIdOptions::configure(defaultCase: GsIdCase::Lower, defaultJsonFormat: GsIdFormat::D);
        $id = GsId::fromString(self::FormattedUpper);

        self::assertSame('"' . strtolower(self::FormattedUpper) . '"', json_encode($id, JSON_THROW_ON_ERROR));
    }

    public function testParserNormalizeShouldUseDefaultCase(): void
    {
        GsIdOptions::setDefaultCase(GsIdCase::Lower);

        self::assertSame(strtolower(self::NormalizedUpper), GsIdParser::normalize(self::FormattedUpper));
    }

    public function testOptionsLockShouldPreventMutation(): void
    {
        GsIdOptions::configure(defaultCase: GsIdCase::Lower);
        GsIdOptions::lock();

        self::assertTrue(GsIdOptions::isLocked());
        $this->expectException(GsIdException::class);
        GsIdOptions::setDefaultCase(GsIdCase::Upper);
    }

    public function testOptionsConfiguratorShouldApplyArrayConfiguration(): void
    {
        new GsIdOptionsConfigurator([
            'default_case' => 'Lower',
            'default_text_format' => 'N',
            'default_json_format' => 'D',
            'default_database_format' => 'N',
            'lock' => false,
        ]);

        $id = GsId::fromString(self::FormattedUpper);

        self::assertSame(strtolower(self::NormalizedUpper), $id->toString());
    }

    public function testGsIdToUidShouldNormalizeAndDenormalizeRouteValue(): void
    {
        $id = GsId::fromString(self::FormattedUpper);

        self::assertSame(self::FormattedUpper, GsIdToUid::normalizeForRoute($id, GsIdFormat::D, GsIdCase::Upper));
        self::assertTrue($id->equals(GsIdToUid::denormalizeFromRoute(self::FormattedUpper)));
    }

    private static function forceResetOptions(): void
    {
        $reflection = new \ReflectionClass(GsIdOptions::class);
        $property = $reflection->getProperty('isLocked');
        $property->setValue(null, false);
        GsIdOptions::reset();
    }
}

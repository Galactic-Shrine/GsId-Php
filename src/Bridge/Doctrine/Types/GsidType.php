<?php

declare(strict_types=1);

namespace GalacticShrine\GsId\Bridge\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use GalacticShrine\GsId\GsId;
use GalacticShrine\GsId\GsIdConstants;
use GalacticShrine\GsId\GsIdException;
use GalacticShrine\GsId\GsIdFormat;
use GalacticShrine\GsId\GsIdOptions;

/**
 * Type Doctrine DBAL pour stocker GsId au format configuré globalement.
 * La longueur SQL suit GsIdOptions::getDefaultDatabaseFormat().
 */
final class GsidType extends Type
{
    public const Name = 'gsid';

    public function getName(): string
    {
        return self::Name;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $storageFormat = GsIdOptions::getDefaultDatabaseFormat();

        return $platform->getStringTypeDeclarationSQL([
            'length' => $storageFormat === GsIdFormat::N
                ? GsIdConstants::HexLength
                : GsIdConstants::FormattedLength,
            'fixed' => true,
        ]);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?GsId
    {
        if ($value === null || $value instanceof GsId) {
            return $value;
        }

        if (!is_string($value)) {
            throw ConversionException::conversionFailedInvalidType(
                $value,
                self::Name,
                ['null', 'string', GsId::class]
            );
        }

        try {
            return GsId::fromString($value);
        } catch (GsIdException $exception) {
            throw ConversionException::conversionFailedFormat(
                $value,
                self::Name,
                GsIdConstants::DGroupPattern,
                $exception
            );
        }
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        $storageFormat = GsIdOptions::getDefaultDatabaseFormat();

        if (is_string($value)) {
            return GsId::fromString($value)->toString($storageFormat, GsIdOptions::getDefaultCase());
        }

        if ($value instanceof GsId) {
            return $value->toString($storageFormat, GsIdOptions::getDefaultCase());
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            self::Name,
            ['null', 'string', GsId::class]
        );
    }
}

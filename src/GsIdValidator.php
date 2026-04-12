<?php

declare(strict_types=1);

namespace GalacticShrine\GsId;

final class GsIdValidator
{
    public static function isValid(?string $value): bool
    {
        return GsIdParser::tryParse($value) instanceof GsId;
    }

    public static function isValidFormat(?string $value, GsIdFormat $format): bool
    {
        if ($value === null) {
            return false;
        }

        $trimmedValue = trim($value);

        return match ($format) {
            GsIdFormat::N => strlen($trimmedValue) === GsIdConstants::HexLength && self::isValid($trimmedValue),
            GsIdFormat::D => strlen($trimmedValue) === GsIdConstants::FormattedLength && self::isValid($trimmedValue),
        };
    }

    private function __construct()
    {
    }
}

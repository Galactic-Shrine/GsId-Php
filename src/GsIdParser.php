<?php

declare(strict_types=1);

namespace GalacticShrine\GsId;

final class GsIdParser
{
    public static function parse(string $value): GsId
    {
        return GsId::fromNormalized(self::normalize($value, GsIdCase::Upper));
    }

    public static function tryParse(?string $value): ?GsId
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        try {
            return self::parse($value);
        } catch (GsIdException) {
            return null;
        }
    }

    public static function normalize(string $value, ?GsIdCase $case = null): string
    {
        $resolvedCase = $case ?? GsIdOptions::getDefaultCase();
        $trimmedValue = trim($value);

        if ($trimmedValue === '') {
            throw new GsIdException('La valeur GsId ne peut pas être vide.');
        }

        if (strlen($trimmedValue) === GsIdConstants::HexLength) {
            return self::normalizeN($trimmedValue, $resolvedCase);
        }

        if (strlen($trimmedValue) === GsIdConstants::FormattedLength) {
            return self::normalizeD($trimmedValue, $resolvedCase);
        }

        throw new GsIdException(sprintf(
            'La valeur GsId doit contenir %d caractères sans tirets ou %d caractères avec tirets.',
            GsIdConstants::HexLength,
            GsIdConstants::FormattedLength
        ));
    }

    private static function normalizeN(string $value, GsIdCase $case): string
    {
        for ($index = 0, $length = strlen($value); $index < $length; $index++) {
            if (!self::isHexCharacter($value[$index])) {
                throw new GsIdException(sprintf("Le caractère '%s' n'est pas hexadécimal.", $value[$index]));
            }
        }

        return self::applyCase($value, $case);
    }

    private static function normalizeD(string $value, GsIdCase $case): string
    {
        $buffer = '';

        for ($index = 0, $length = strlen($value); $index < $length; $index++) {
            $character = $value[$index];

            if (self::isHyphenPosition($index)) {
                if ($character !== '-') {
                    throw new GsIdException("La valeur GsId n'utilise pas les positions de tirets officielles.");
                }

                continue;
            }

            if (!self::isHexCharacter($character)) {
                throw new GsIdException(sprintf("Le caractère '%s' n'est pas hexadécimal.", $character));
            }

            $buffer .= self::applyCase($character, $case);
        }

        return $buffer;
    }

    private static function isHyphenPosition(int $index): bool
    {
        return in_array($index, [16, 25, 34, 43, 52], true);
    }

    private static function isHexCharacter(string $character): bool
    {
        return ctype_xdigit($character);
    }

    private static function applyCase(string $value, GsIdCase $case): string
    {
        return match ($case) {
            GsIdCase::Upper => strtoupper($value),
            GsIdCase::Lower => strtolower($value),
        };
    }

    private function __construct()
    {
    }
}

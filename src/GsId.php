<?php

declare(strict_types=1);

namespace GalacticShrine\GsId;

use JsonSerializable;
use Stringable;

final class GsId implements JsonSerializable, Stringable
{
    private string $normalizedValue;

    private function __construct(string $normalizedValue)
    {
        $this->normalizedValue = $normalizedValue;
    }

    public static function newGsId(): self
    {
        return GsIdGenerator::newGsId();
    }

    public static function fromString(string $value): self
    {
        return GsIdParser::parse($value);
    }

    public static function tryFromString(?string $value): ?self
    {
        return GsIdParser::tryParse($value);
    }

    public static function fromBytes(string $bytes): self
    {
        if (strlen($bytes) !== GsIdConstants::ByteLength) {
            throw new GsIdException(sprintf(
                'Un GsId brut doit contenir exactement %d octets.',
                GsIdConstants::ByteLength
            ));
        }

        return new self(strtoupper(bin2hex($bytes)));
    }

    public static function fromNormalized(string $normalizedValue): self
    {
        if (strlen($normalizedValue) !== GsIdConstants::HexLength || !ctype_xdigit($normalizedValue)) {
            throw new GsIdException('La valeur normalisée GsId doit contenir exactement 64 caractères hexadécimaux.');
        }

        return new self(strtoupper($normalizedValue));
    }

    public static function empty(): self
    {
        return new self(str_repeat('0', GsIdConstants::HexLength));
    }

    public function isEmpty(): bool
    {
        return trim($this->normalizedValue, '0') === '';
    }

    public function normalized(?GsIdCase $case = null): string
    {
        return self::applyCase($this->normalizedValue, $case ?? GsIdOptions::getDefaultCase());
    }

    public function toString(?GsIdFormat $format = null, ?GsIdCase $case = null): string
    {
        $resolvedFormat = $format ?? GsIdOptions::getDefaultTextFormat();
        $normalizedValue = $this->normalized($case);

        return match ($resolvedFormat) {
            GsIdFormat::N => $normalizedValue,
            GsIdFormat::D => sprintf(
                '%s-%s-%s-%s-%s-%s',
                substr($normalizedValue, 0, 16),
                substr($normalizedValue, 16, 8),
                substr($normalizedValue, 24, 8),
                substr($normalizedValue, 32, 8),
                substr($normalizedValue, 40, 8),
                substr($normalizedValue, 48, 16),
            ),
        };
    }

    public function toBytes(): string
    {
        $bytes = hex2bin($this->normalizedValue);

        if ($bytes === false) {
            throw new GsIdException('Impossible de convertir le GsId en tableau d’octets.');
        }

        return $bytes;
    }

    public function equals(self $other): bool
    {
        return hash_equals($this->normalizedValue, $other->normalizedValue);
    }

    public function jsonSerialize(): string
    {
        return $this->toString(GsIdOptions::getDefaultJsonFormat(), GsIdOptions::getDefaultCase());
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    private static function applyCase(string $value, GsIdCase $case): string
    {
        return match ($case) {
            GsIdCase::Upper => strtoupper($value),
            GsIdCase::Lower => strtolower($value),
        };
    }
}

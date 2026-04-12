<?php

declare(strict_types=1);

namespace GalacticShrine\GsId;

/**
 * Pont léger pour Symfony (routes, formulaires, serializer, DTO).
 * Cette classe ne remplace pas le composant symfony/uid ; elle fournit
 * simplement des conversions stables vers et depuis les chaînes GsId.
 */
final class GsIdSymfonyUidBridge
{
    public static function normalizeForRoute(
        GsId|string|null $value,
        GsIdFormat $format = GsIdFormat::D,
        ?GsIdCase $case = null
    ): ?string {
        if ($value === null) {
            return null;
        }

        if ($value instanceof GsId) {
            return $value->toString($format, $case);
        }

        return GsId::fromString($value)->toString($format, $case);
    }

    public static function denormalizeFromRoute(?string $value): ?GsId
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        return GsId::fromString($value);
    }

    public static function normalizeForSerializer(
        GsId|string|null $value,
        GsIdFormat $format = GsIdFormat::D,
        ?GsIdCase $case = null
    ): ?string {
        return self::normalizeForRoute($value, $format, $case);
    }

    public static function denormalizeFromSerializer(null|string|\Stringable $value): ?GsId
    {
        if ($value === null) {
            return null;
        }

        return GsId::fromString((string) $value);
    }

    private function __construct()
    {
    }
}

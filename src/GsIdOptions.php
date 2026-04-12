<?php

declare(strict_types=1);

namespace GalacticShrine\GsId;

final class GsIdOptions
{
    private static GsIdCase $defaultCase = GsIdCase::Upper;
    private static GsIdFormat $defaultTextFormat = GsIdFormat::D;
    private static GsIdFormat $defaultJsonFormat = GsIdFormat::D;
    private static GsIdFormat $defaultDatabaseFormat = GsIdFormat::N;
    private static bool $isLocked = false;

    public static function getDefaultCase(): GsIdCase
    {
        return self::$defaultCase;
    }

    public static function setDefaultCase(GsIdCase $case): void
    {
        self::ensureUnlocked();
        self::$defaultCase = $case;
    }

    public static function getDefaultTextFormat(): GsIdFormat
    {
        return self::$defaultTextFormat;
    }

    public static function setDefaultTextFormat(GsIdFormat $format): void
    {
        self::ensureUnlocked();
        self::$defaultTextFormat = self::validateFormat($format);
    }

    public static function getDefaultJsonFormat(): GsIdFormat
    {
        return self::$defaultJsonFormat;
    }

    public static function setDefaultJsonFormat(GsIdFormat $format): void
    {
        self::ensureUnlocked();
        self::$defaultJsonFormat = self::validateFormat($format);
    }

    public static function getDefaultDatabaseFormat(): GsIdFormat
    {
        return self::$defaultDatabaseFormat;
    }

    public static function setDefaultDatabaseFormat(GsIdFormat $format): void
    {
        self::ensureUnlocked();
        self::$defaultDatabaseFormat = self::validateFormat($format);
    }

    public static function configure(
        ?GsIdCase $defaultCase = null,
        ?GsIdFormat $defaultTextFormat = null,
        ?GsIdFormat $defaultJsonFormat = null,
        ?GsIdFormat $defaultDatabaseFormat = null,
    ): void {
        self::ensureUnlocked();

        if ($defaultCase !== null) {
            self::$defaultCase = $defaultCase;
        }

        if ($defaultTextFormat !== null) {
            self::$defaultTextFormat = self::validateFormat($defaultTextFormat);
        }

        if ($defaultJsonFormat !== null) {
            self::$defaultJsonFormat = self::validateFormat($defaultJsonFormat);
        }

        if ($defaultDatabaseFormat !== null) {
            self::$defaultDatabaseFormat = self::validateFormat($defaultDatabaseFormat);
        }
    }

    public static function lock(): void
    {
        self::$isLocked = true;
    }

    public static function isLocked(): bool
    {
        return self::$isLocked;
    }

    public static function reset(): void
    {
        self::ensureUnlocked();
        self::$defaultCase = GsIdCase::Upper;
        self::$defaultTextFormat = GsIdFormat::D;
        self::$defaultJsonFormat = GsIdFormat::D;
        self::$defaultDatabaseFormat = GsIdFormat::N;
    }

    private static function ensureUnlocked(): void
    {
        if (self::$isLocked) {
            throw new GsIdException('Les options GsId sont verrouillées et ne peuvent plus être modifiées.');
        }
    }

    private static function validateFormat(GsIdFormat $format): GsIdFormat
    {
        return match ($format) {
            GsIdFormat::N => GsIdFormat::N,
            GsIdFormat::D => GsIdFormat::D,
        };
    }

    private function __construct()
    {
    }
}

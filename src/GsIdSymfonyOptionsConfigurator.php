<?php

declare(strict_types=1);

namespace GalacticShrine\GsId;

/**
 * Configurateur runtime pour appliquer les options GsId dans une application Symfony.
 */
final class GsIdSymfonyOptionsConfigurator
{
    /**
     * @var array<string, mixed>
     */
    private array $options;

    private bool $applied = false;

    /**
     * @param array<string, mixed> $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
        $this->apply();
    }

    public function apply(): void
    {
        if ($this->applied || GsIdOptions::isLocked()) {
            return;
        }

        GsIdOptions::configure(
            defaultCase: self::readCase($this->options['default_case'] ?? null),
            defaultTextFormat: self::readFormat($this->options['default_text_format'] ?? null),
            defaultJsonFormat: self::readFormat($this->options['default_json_format'] ?? null),
            defaultDatabaseFormat: self::readFormat($this->options['default_database_format'] ?? null),
        );

        $this->applied = true;

        if (self::readBoolean($this->options['lock'] ?? false)) {
            GsIdOptions::lock();
        }
    }

    /**
     * Méthode compatible avec le tag Symfony kernel.event_listener.
     */
    public function onKernelRequest(object|null $event = null): void
    {
        $this->apply();
    }

    private static function readCase(mixed $value): ?GsIdCase
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof GsIdCase) {
            return $value;
        }

        if (!is_string($value)) {
            throw new GsIdException('La valeur default_case doit être une chaîne ou une instance de GsIdCase.');
        }

        return match (strtolower(trim($value))) {
            'upper', 'uppercase', 'majuscule', 'majuscules' => GsIdCase::Upper,
            'lower', 'lowercase', 'minuscule', 'minuscules' => GsIdCase::Lower,
            default => throw new GsIdException(sprintf(
                'La valeur default_case "%s" est invalide. Valeurs acceptées : Upper, Lower.',
                $value
            )),
        };
    }

    private static function readFormat(mixed $value): ?GsIdFormat
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof GsIdFormat) {
            return $value;
        }

        if (!is_string($value)) {
            throw new GsIdException('Les valeurs de format doivent être des chaînes ou des instances de GsIdFormat.');
        }

        return match (strtoupper(trim($value))) {
            'N' => GsIdFormat::N,
            'D' => GsIdFormat::D,
            default => throw new GsIdException(sprintf(
                'La valeur de format "%s" est invalide. Valeurs acceptées : N, D.',
                $value
            )),
        };
    }

    private static function readBoolean(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value)) {
            return $value === 1;
        }

        if (is_string($value)) {
            return match (strtolower(trim($value))) {
                '1', 'true', 'yes', 'y', 'oui' => true,
                '0', 'false', 'no', 'n', 'non', '' => false,
                default => throw new GsIdException(sprintf(
                    'La valeur booléenne "%s" est invalide. Valeurs acceptées : true, false.',
                    $value
                )),
            };
        }

        return false;
    }
}

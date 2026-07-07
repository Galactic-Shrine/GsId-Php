<?php

declare(strict_types=1);

namespace GalacticShrine\GsId;

final class GsIdConstants
{
    public const ByteLength = 32;
    public const HexLength = 64;
    public const FormattedLength = 69;
    public const HyphenCount = 5;
    public const DGroupPattern = '16-8-8-8-8-16';

    private function __construct()
    {
    }
}

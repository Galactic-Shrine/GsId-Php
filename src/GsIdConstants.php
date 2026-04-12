<?php

declare(strict_types=1);

namespace GalacticShrine\GsId;

final class GsIdConstants
{
    public const int ByteLength = 32;
    public const int HexLength = 64;
    public const int FormattedLength = 69;
    public const int HyphenCount = 5;
    public const string DGroupPattern = '16-8-8-8-8-16';

    private function __construct()
    {
    }
}

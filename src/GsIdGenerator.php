<?php

declare(strict_types=1);

namespace GalacticShrine\GsId;

final class GsIdGenerator
{
    public static function newGsId(): GsId
    {
        return GsId::fromBytes(random_bytes(GsIdConstants::ByteLength));
    }

    private function __construct()
    {
    }
}

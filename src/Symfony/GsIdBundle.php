<?php

declare(strict_types=1);

namespace GalacticShrine\GsId\Symfony;

use GalacticShrine\GsId\Symfony\DependencyInjection\GsIdExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class GsIdBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new GsIdExtension();
    }
}

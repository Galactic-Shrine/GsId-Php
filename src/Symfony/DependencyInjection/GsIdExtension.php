<?php

declare(strict_types=1);

namespace GalacticShrine\GsId\Symfony\DependencyInjection;

use GalacticShrine\GsId\GsIdSymfonyOptionsConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class GsIdExtension extends Extension
{
    /**
     * @param array<int, array<string, mixed>> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container
            ->register(GsIdSymfonyOptionsConfigurator::class, GsIdSymfonyOptionsConfigurator::class)
            ->setArgument('$options', $config)
            ->addTag('kernel.event_listener', [
                'event' => 'kernel.request',
                'method' => 'onKernelRequest',
                'priority' => 4096,
            ]);
    }

    public function getAlias(): string
    {
        return 'gsid';
    }
}

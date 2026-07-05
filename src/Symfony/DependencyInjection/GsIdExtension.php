<?php

declare(strict_types=1);

namespace GalacticShrine\GsId\Symfony\DependencyInjection;

use GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType;
use GalacticShrine\GsId\Symfony\GsIdOptionsConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

final class GsIdExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container): void
    {
        if (!$container->hasExtension('doctrine')) {
            return;
        }

        $container->prependExtensionConfig('doctrine', [
            'dbal' => [
                'types' => [
                    GsidType::Name => GsidType::class,
                ],
            ],
        ]);
    }

    /**
     * @param array<int, array<string, mixed>> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container
            ->register(GsIdOptionsConfigurator::class, GsIdOptionsConfigurator::class)
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

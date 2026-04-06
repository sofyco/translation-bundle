<?php declare(strict_types=1);

namespace Sofyco\Bundle\TranslationBundle\DependencyInjection;

use Sofyco\Bundle\TranslationBundle\EventListener\SwitchLocaleListener;
use Sofyco\Bundle\TranslationBundle\Loader\DatabaseLoader;
use Symfony\Component\DependencyInjection\{ContainerBuilder, Definition, Extension\Extension};

final class TranslationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $switchLocaleListener = new Definition(SwitchLocaleListener::class);
        $switchLocaleListener->setAutowired(true);
        $switchLocaleListener->setAutoconfigured(true);
        $container->setDefinition(SwitchLocaleListener::class, $switchLocaleListener);

        $databaseLoader = new Definition(DatabaseLoader::class);
        $databaseLoader->setAutowired(true);
        $databaseLoader->addTag(name: 'translation.loader', attributes: ['alias' => 'database']);
        $container->setDefinition(DatabaseLoader::class, $databaseLoader);
    }
}

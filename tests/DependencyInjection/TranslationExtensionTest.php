<?php declare(strict_types=1);

namespace Sofyco\Bundle\TranslationBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Sofyco\Bundle\TranslationBundle\DependencyInjection\TranslationExtension;
use Sofyco\Bundle\TranslationBundle\EventListener\SwitchLocaleListener;
use Sofyco\Bundle\TranslationBundle\Loader\DatabaseLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class TranslationExtensionTest extends TestCase
{
    public function testLoadRegistersListenerAndLoaderDefinitions(): void
    {
        $container = new ContainerBuilder();
        $extension = new TranslationExtension();

        $extension->load(configs: [], container: $container);

        self::assertTrue($container->hasDefinition(SwitchLocaleListener::class));
        self::assertTrue($container->hasDefinition(DatabaseLoader::class));

        $switchLocaleDefinition = $container->getDefinition(SwitchLocaleListener::class);
        self::assertTrue($switchLocaleDefinition->isAutowired());
        self::assertTrue($switchLocaleDefinition->isAutoconfigured());

        $databaseLoaderDefinition = $container->getDefinition(DatabaseLoader::class);
        self::assertTrue($databaseLoaderDefinition->isAutowired());
        self::assertTrue($databaseLoaderDefinition->hasTag('translation.loader'));
    }
}

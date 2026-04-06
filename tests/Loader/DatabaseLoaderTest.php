<?php declare(strict_types=1);

namespace Sofyco\Bundle\TranslationBundle\Tests\Loader;

use PHPUnit\Framework\TestCase;
use Sofyco\Bundle\TranslationBundle\Entity\TranslationInterface;
use Sofyco\Bundle\TranslationBundle\Loader\DatabaseLoader;
use Sofyco\Bundle\TranslationBundle\Repository\TranslationRepositoryInterface;

final class DatabaseLoaderTest extends TestCase
{
    public function testLoadReturnsCatalogueWithTranslationsFromRepository(): void
    {
        $translationOne = new class () implements TranslationInterface {
            public function getKey(): string
            {
                return 'greeting';
            }

            public function getValue(): string
            {
                return 'Hello';
            }
        };

        $translationTwo = new class () implements TranslationInterface {
            public function getKey(): string
            {
                return 'farewell';
            }

            public function getValue(): string
            {
                return 'Good bye';
            }
        };

        $repository = $this->createMock(TranslationRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('findByLocaleAndDomain')
            ->with('en', 'messages')
            ->willReturn([$translationOne, $translationTwo]);

        $loader = new DatabaseLoader($repository);

        $catalogue = $loader->load(resource: null, locale: 'en', domain: 'messages');

        self::assertSame('Hello', $catalogue->get('greeting'));
        self::assertSame('Good bye', $catalogue->get('farewell'));
    }

    public function testLoadReturnsEmptyCatalogueWhenRepositoryThrows(): void
    {
        $repository = $this->createMock(TranslationRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('findByLocaleAndDomain')
            ->with('en', 'messages')
            ->willThrowException(new \RuntimeException('Database error'));

        $loader = new DatabaseLoader($repository);

        $catalogue = $loader->load(resource: null, locale: 'en', domain: 'messages');

        self::assertSame([], $catalogue->all('messages'));
    }
}

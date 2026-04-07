<?php declare(strict_types=1);

namespace Sofyco\Bundle\TranslationBundle\Loader;

use Sofyco\Bundle\TranslationBundle\Repository\TranslationRepositoryInterface;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

final readonly class DatabaseLoader implements LoaderInterface
{
    private const string DEFAULT_DOMAIN = 'messages';

    public function __construct(private TranslationRepositoryInterface $translationRepository)
    {
    }

    public function load(mixed $resource, string $locale, string $domain = self::DEFAULT_DOMAIN): MessageCatalogue
    {
        try {
            $translations = $this->translationRepository->findByLocaleAndDomain(locale: $locale, domain: $domain);
        } catch (\Throwable) {
            $translations = [];
        }

        $messages = [];

        foreach ($translations as $translation) {
            $messages[$domain][$translation->key] = $translation->value;
        }

        return new MessageCatalogue(locale: $locale, messages: $messages);
    }
}

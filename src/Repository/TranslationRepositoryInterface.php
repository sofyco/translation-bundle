<?php declare(strict_types=1);

namespace Sofyco\Bundle\TranslationBundle\Repository;

use Sofyco\Bundle\TranslationBundle\Entity\TranslationInterface;

interface TranslationRepositoryInterface
{
    /**
     * @return TranslationInterface[]
     */
    public function findByLocaleAndDomain(string $locale, string $domain): iterable;
}

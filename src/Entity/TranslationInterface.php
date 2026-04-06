<?php declare(strict_types=1);

namespace Sofyco\Bundle\TranslationBundle\Entity;

interface TranslationInterface
{
    public function getKey(): string;

    public function getValue(): string;
}

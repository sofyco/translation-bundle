<?php declare(strict_types=1);

namespace Sofyco\Bundle\TranslationBundle\Entity;

interface TranslationInterface
{
    public string $key {
        get;
    }

    public string $value {
        get;
    }
}

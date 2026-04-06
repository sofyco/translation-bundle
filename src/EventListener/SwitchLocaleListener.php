<?php declare(strict_types=1);

namespace Sofyco\Bundle\TranslationBundle\EventListener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Translation\LocaleSwitcher;

#[AsEventListener]
final readonly class SwitchLocaleListener
{
    private const string LOCALE_HEADER_NAME = 'App-Locale';

    public function __construct(private LocaleSwitcher $localeSwitcher, private ParameterBagInterface $parameterBag)
    {
    }

    public function __invoke(RequestEvent $event): void
    {
        if (false === $event->isMainRequest()) {
            return;
        }

        $locale = $event->getRequest()->headers->get(
            key: self::LOCALE_HEADER_NAME,
            default: $event->getRequest()->getPreferredLanguage(),
        );

        if (null === $locale) {
            return;
        }

        $enabledLocales = $this->parameterBag->get(name: 'kernel.enabled_locales');

        if (false === is_array($enabledLocales) || false === in_array($locale, $enabledLocales, strict: true)) {
            return;
        }

        $this->localeSwitcher->setLocale(locale: $locale);
    }
}

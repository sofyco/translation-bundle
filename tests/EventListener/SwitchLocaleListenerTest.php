<?php declare(strict_types=1);

namespace Sofyco\Bundle\TranslationBundle\Tests\EventListener;

use PHPUnit\Framework\TestCase;
use Sofyco\Bundle\TranslationBundle\EventListener\SwitchLocaleListener;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Translation\LocaleSwitcher;

final class SwitchLocaleListenerTest extends TestCase
{
    public function testInvokeSetsLocaleFromHeaderForMainRequest(): void
    {
        $localeSwitcher = $this->createMock(LocaleSwitcher::class);
        $localeSwitcher
            ->expects($this->once())
            ->method('setLocale')
            ->with('uk');

        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $parameterBag
            ->expects($this->once())
            ->method('get')
            ->with('kernel.enabled_locales')
            ->willReturn(['en', 'uk']);

        $request = Request::create('/');
        $request->headers->set('App-Locale', 'uk');

        $event = $this->createRequestEvent($request, HttpKernelInterface::MAIN_REQUEST);

        $listener = new SwitchLocaleListener($localeSwitcher, $parameterBag);
        $listener($event);
    }

    public function testInvokeUsesPreferredLanguageWhenHeaderIsMissing(): void
    {
        $localeSwitcher = $this->createMock(LocaleSwitcher::class);
        $localeSwitcher
            ->expects($this->once())
            ->method('setLocale')
            ->with('en');

        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $parameterBag
            ->expects($this->once())
            ->method('get')
            ->with('kernel.enabled_locales')
            ->willReturn(['en', 'uk']);

        $request = Request::create('/');
        $request->headers->set('Accept-Language', 'en');

        $event = $this->createRequestEvent($request, HttpKernelInterface::MAIN_REQUEST);

        $listener = new SwitchLocaleListener($localeSwitcher, $parameterBag);
        $listener($event);
    }

    public function testInvokeDoesNothingForNotMainRequest(): void
    {
        $localeSwitcher = $this->createMock(LocaleSwitcher::class);
        $localeSwitcher->expects($this->never())->method('setLocale');

        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $parameterBag->expects($this->never())->method('get');

        $event = $this->createRequestEvent(Request::create('/'), HttpKernelInterface::SUB_REQUEST);

        $listener = new SwitchLocaleListener($localeSwitcher, $parameterBag);
        $listener($event);
    }

    public function testInvokeDoesNothingWhenLocaleIsNotEnabled(): void
    {
        $localeSwitcher = $this->createMock(LocaleSwitcher::class);
        $localeSwitcher->expects($this->never())->method('setLocale');

        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $parameterBag
            ->expects($this->once())
            ->method('get')
            ->with('kernel.enabled_locales')
            ->willReturn(['en']);

        $request = Request::create('/');
        $request->headers->set('App-Locale', 'uk');

        $event = $this->createRequestEvent($request, HttpKernelInterface::MAIN_REQUEST);

        $listener = new SwitchLocaleListener($localeSwitcher, $parameterBag);
        $listener($event);
    }

    private function createRequestEvent(Request $request, int $requestType): RequestEvent
    {
        $kernel = $this->createStub(HttpKernelInterface::class);

        return new RequestEvent($kernel, $request, $requestType);
    }
}

<?php

namespace Wizbii\HateOasBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;
use Wizbii\HateOasBundle\Attribute\FromRequest;
use Wizbii\HateOasBundle\Attribute\FromResponse;
use Wizbii\HateOasBundle\Attribute\RouteLink;

class HateOasListener implements EventSubscriberInterface
{
    public function __construct(
        private RouterInterface $router,
    ) {
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        /** The request is not in JSON */
        if ($request->headers->get('content-type') !== 'application/json' || !$controller = $request->get('_controller', '')) {
            return;
        }

        if (!is_string($controller)) {
            return;
        }

        $controllerName = $this->getControllerName($controller);
        $controllerRoutes = $this->getRoutesFromController($controllerName);

        $content = json_decode((string) $response->getContent(), true, flags: JSON_THROW_ON_ERROR);

        if (is_array($content)) {
            /** @var array<string, array<string, string>> $links */
            $links = [];
            if ($request->attributes->get('_generateHateOasLinks')) {
                $links = $this->addLinksFromController($links, $controllerRoutes, $request);
            }

            if ($routeLinks = $request->attributes->get('_routeLink')) {
                $links = $this->addLinkToContent($links, 'self', $request->getRequestUri(), $request->getMethod());
                /** @var RouteLink[] $routeLinks */
                foreach ($routeLinks as $link) {
                    $parameters = $this->getParameters($link, $request, $response);
                    $links = $this->addLinkToContent($links, $link->getName() ?? $link->getRouteName(), $this->router->generate($link->getRouteName(), $parameters), $link->getMethod());
                }
            }
            $content['_links'] = $links;
        }

        $content = json_encode($content);
        if ($content !== false) {
            $response->setContent($content);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::RESPONSE => 'onKernelResponse'];
    }

    private function getControllerName(string $controller): string
    {
        preg_match('/(.+?)(?=::)/', $controller, $matches);
        if (!isset($matches[0])) {
            throw new \RuntimeException('The controller $controller is unknown');
        }

        return $matches[0];
    }

    /**
     * @return Route[]
     */
    private function getRoutesFromController(string $controllerName): array
    {
        $routes = $this->router->getRouteCollection();
        $controllerRoutes = [];
        foreach ($routes->getIterator() as $name => $route) {
            /** @var Route $route */
            $currentControllerName = $this->getControllerName($route->getDefaults()['_controller']);
            if ($controllerName === $currentControllerName) {
                $controllerRoutes[$name] = $route;
            }
        }

        return $controllerRoutes;
    }

    /**
     * @param array<string, array<string, string>> $links
     * @param array<string, Route>                 $controllerRoutes
     *
     * @return array<string, array<string, string>>
     */
    private function addLinksFromController(array $links, array $controllerRoutes, Request $request): array
    {
        foreach ($controllerRoutes as $routeName => $route) {
            $currentController = $route->getDefaults()['_controller'];
            $href = $route->getPath();
            foreach ($route->getMethods() as $method) {
                $routeNameLink = $routeName;
                if (count($route->getMethods()) > 1) {
                    $routeNameLink = strtolower($method).'_'.$routeName;
                }
                if ($this->isCurrentRoute($currentController, $request)) {
                    $routeNameLink = 'self';
                    $href = $request->getRequestUri();
                    $method = $request->getMethod();
                }
                $links = $this->addLinkToContent($links, $routeNameLink, $href, $method);
            }
        }

        return $links;
    }

    private function isCurrentRoute(string $currentControllerAction, Request $request): bool
    {
        return $currentControllerAction === $request->get('_controller');
    }

    /**
     * @param array<string, array<string, string>> $links
     *
     * @return array<string, array<string, string>>
     */
    private function addLinkToContent(array $links, string $name, string $href, string $method): array
    {
        $links[$name] = ['href' => $href, 'method' => $method];

        return $links;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getParameters(RouteLink $link, Request $request, Response $response): array
    {
        $parameters = [];
        foreach ($link->getParams() as $paramName => $param) {
            if ($param instanceof FromRequest) {
                $parameters[$paramName] = $request->get($param->getName());
            }
            if ($param instanceof FromResponse) {
                $parameters[$paramName] = $param->getParameterValue((string) $response->getContent());
            }
        }

        return $parameters;
    }
}

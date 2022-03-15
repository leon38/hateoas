<?php

namespace Wizbii\HateOasBundle\Attribute;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class RouteLink extends ConfigurationAnnotation
{
    /**
     * @param array<string, mixed> $params
     */
    public function __construct(
        private string $routeName,
        /** @var array<string, FromRequest> */
        private array $params = [],
        private ?string $name = null,
        private string $method = 'GET'
    ) {
        $values = [];

        $values['routeName'] = $routeName;
        $values['params'] = $params;
        $values['name'] = $name;
        $values['method'] = $method;

        parent::__construct($values);
    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    /**
     * @return array<string, FromRequest|FromResponse>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setRouteName(string $routeName): void
    {
        $this->routeName = $routeName;
    }

    /**
     * @param array<string, FromRequest> $params
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getAliasName(): string
    {
        return 'routeLink';
    }

    public function allowArray(): bool
    {
        return true;
    }
}

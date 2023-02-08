<?php

namespace HateOas\HateOasBundle\Model;

class Link
{
    public function __construct(
        private string $name,
        private string $href,
        private string $method
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function toArray(): array
    {
        return [
            $this->getName() => [
                'href' => $this->getHref(),
                'method' => $this->getMethod(),
            ],
        ];
    }
}

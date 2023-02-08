<?php

namespace HateOas\HateOasBundle\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class FromResponse
{
    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParameterValue(string $content): string
    {
        $json = json_decode($content, false, flags: JSON_THROW_ON_ERROR);
        $chunks = explode('.', $this->name);
        $paramValue = $json;
        foreach ($chunks as $chunk) {
            $paramValue = $paramValue->$chunk;
        }

        return $paramValue;
    }
}

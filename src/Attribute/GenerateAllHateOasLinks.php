<?php

namespace Wizbii\HateOasBundle\Attribute;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

#[\Attribute(\Attribute::TARGET_CLASS)]
class GenerateAllHateOasLinks extends ConfigurationAnnotation
{
    /**
     * @param array<string, mixed> $values
     */
    public function __construct(array $values = [])
    {
        parent::__construct($values);
    }

    public function getAliasName(): string
    {
        return 'generateHateOasLinks';
    }

    public function allowArray(): bool
    {
        return false;
    }
}

<?php

namespace Tests\Wizbii\HateOasBundle\Fixtures;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Wizbii\HateOasBundle\Trait\HateOasTrait;

#[Route('/not_abstract')]
class NotAbstractController
{
    use HateOasTrait;

    #[Route('/api/resources', name: 'not_abstract_get_all', methods: ['GET'])]
    public function getAllAction(): JsonResponse
    {
        return $this->hateoasJson([]);
    }
}

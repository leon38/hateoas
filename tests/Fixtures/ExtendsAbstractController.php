<?php

namespace Tests\HateOas\HateOasBundle\Fixtures;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use HateOas\HateOasBundle\Model\Link;
use HateOas\HateOasBundle\Trait\HateOasTrait;

#[Route('/extends_abstract')]
class ExtendsAbstractController extends AbstractController
{
    use HateOasTrait;

    #[Route('/api/resources', name: 'extends_abstract_get_all', methods: ['GET'])]
    public function getAllAction(): JsonResponse
    {
        return $this->hateoasJson([]);
    }

    #[Route('/api/resources/without-link', name: 'extends_abstract_get_without_link', methods: ['GET'])]
    public function getWithoutLink(): JsonResponse
    {
        return $this->hateoasJson(['data' => 'response with no link']);
    }

    #[Route('/api/resources/with-links', name: 'extends_abstract_get_without_link', methods: ['GET'])]
    public function getWithLinks(): JsonResponse
    {
        $links = [
            new Link('getAll', '/api/other-resources', 'GET'),
            new Link('create', '/api/other-resources', 'POST'),
            new Link('update', '/api/other-resources/{id}', 'PUT'),
        ];

        return $this->hateoasJson(['data' => 'response with no link'], links: $links);
    }
}

<?php

namespace Tests\HateOas\HateOasBundle\Fixtures;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use HateOas\HateOasBundle\Attribute\GenerateAllHateOasLinks;

#[Route('/auto')]
#[GenerateAllHateOasLinks]
class AutoLinkController extends AbstractController
{
    #[Route('/api/resources', name: 'auto_get_all', methods: ['GET'])]
    public function getAllAction(): JsonResponse
    {
        return $this->json([]);
    }

    #[Route('/api/resources', name: 'auto_create', methods: ['POST'])]
    public function createAction(): JsonResponse
    {
        return $this->json([]);
    }

    #[Route('/api/resources/{id}', name: 'auto_update', methods: ['PUT'])]
    public function updateAction(string $id): JsonResponse
    {
        return $this->json([]);
    }

    #[Route('/api/resources', name: 'auto_create_or_update', methods: ['PUT', 'POST'])]
    public function createOrUpdateAction(): JsonResponse
    {
        return $this->json([]);
    }

    #[Route('/api/resources/{id}', name: 'auto_delete', methods: ['DELETE'])]
    public function deleteAction(string $id): JsonResponse
    {
        return $this->json([]);
    }
}

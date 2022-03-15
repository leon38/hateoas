<?php

namespace Tests\Wizbii\HateOasBundle\Fixtures;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Wizbii\HateOasBundle\Attribute\FromRequest;
use Wizbii\HateOasBundle\Attribute\FromResponse;
use Wizbii\HateOasBundle\Attribute\RouteLink;

#[Route('/manual')]
class ManualLinkController extends AbstractController
{
    #[Route('/api/resources/{id}', name: 'manual_get', methods: ['GET'])]
    #[RouteLink('manual_update', ['id' => new FromRequest('id')], name: 'update', method: 'PUT')]
    #[RouteLink('manual_delete', ['id' => new FromRequest('id')], name: 'delete', method: 'DELETE')]
    #[RouteLink(routeName: 'manual_create', name: 'create', method: 'POST')]
    public function getAction(string $id): JsonResponse
    {
        return $this->json([]);
    }

    #[Route('/api/resources', name: 'manual_create', methods: ['POST'])]
    #[RouteLink('manual_update', ['id' => new FromResponse('data.id')], name: 'update', method: 'PUT')]
    #[RouteLink('manual_delete', ['id' => new FromResponse('data.id')], name: 'delete', method: 'DELETE')]
    public function createAction(): JsonResponse
    {
        return $this->json(['data' => ['id' => 'generated-id']]);
    }

    #[Route('/api/resources/{id}', name: 'manual_update', methods: ['PUT'])]
    public function updateAction(string $id): JsonResponse
    {
        return $this->json([]);
    }

    #[Route('/api/resources/{id}', name: 'manual_delete', methods: ['DELETE'])]
    public function deleteAction(string $id): JsonResponse
    {
        return $this->json([]);
    }
}

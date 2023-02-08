<?php

namespace HateOas\HateOasBundle\Trait;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Wizbii\HateOasBundle\Model\Link;

trait HateOasTrait
{
    /**
     * @param mixed                $data
     * @param array<string, mixed> $headers
     * @param array<string, mixed> $context
     * @param Link[]               $links
     */
    public function hateoasJson($data, int $status = Response::HTTP_OK, array $headers = [], array $context = [], array $links = []): JsonResponse
    {
        if (!($this instanceof AbstractController)) {
            throw new \RuntimeException('"HateOasTrait" should be used in a class that extends AbstractController');
        }

        $response = $this->json($data, $status, $headers, $context);
        $json = $response->getContent();

        $dataToEncode = json_decode($json, associative: false, flags: JSON_THROW_ON_ERROR);

        if (!is_object($dataToEncode)) {
            throw new \LogicException('The response should be encapsulated in an object if it returns many objects');
        }

        if (!empty($links)) {
            $dataToEncode->_links ??= [];
            foreach ($links as $link) {
                $dataToEncode->_links[$link->getName()] = ['href' => $link->getHref(), 'method' => $link->getMethod()];
            }
        }

        $response->setContent(json_encode($dataToEncode));

        return $response;
    }
}

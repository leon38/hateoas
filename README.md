# Hateoas Bundle

Create attributes and controller to add links automagically in content

## Installation
Run this command to install the HateOas Bundle before using it:
```bash
composer require hateoas/hateoas-bundle
```


## Basic Usage
Use this bundle to expose routes associated to the current route. If you want to expose all the routes of your controller, you'll use `#[HateOas]` attribute:
```php
#[HateOas]
class YourCrudController extends AbstractController
{
    ...
    #[Route('/api/items', name: 'get_all', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $items = $this->repository->findAll();
        
        return $this->json(['items' => $items]);
    }
    
    #[Route('/api/items/{id}', name: 'get_one', methods: ['GET'])]
    public function getOne(string $id): JsonResponse
    {
        $item = $this->repository->get($id);
        
        return $this->json($item);
    }
    
    #[Route('/api/items', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $itemResource = $this->serializer->deserialize($request->getContent(), ItemResource::class, 'josn');
        $this->repository->save($itemResource->toItem());
        
        return $this->json();
    }
    
    #[Route('/api/items/{id}', name: 'update', methods: ['PUT'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $itemResource = $this->serializer->deserialize($request->getContent(), ItemResource::class, 'josn');
        ...
        $this->repository->save($itemResource->toItem());
        
        return $this->json($itemResource);
    }
    
    #[Route('/api/items/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(string $id, Request $request): JsonResponse
    {
        $this->repository->delete($id);
        
        return $this->json();
    }
}
```

For each endpoint of this controller, the attribute will add links to other endpoints :
```json
{
  "items": [{"id":  "item-1"}],
  "_links": {
    "self": {
      "href": "/api/items",
      "method": "GET"
    },
    "get_one": {
      "href": "/api/items/{id}",
      "method": "GET"
    },
    "create": {
      "href": "/api/items",
      "method": "POST"
    },
    "update": {
      "href": "/api/items/{id}",
      "method": "PUT"
    },
    "delete": {
      "href": "/api/items/{id}",
      "method": "DELETE"
    }
  }
}
```
The `self` route is the current endpoint called.

## Manual Usage
Sometimes, we don't want to expose all the controller routes, you can configure that for each endpoint with the `#[RouteLink]`attribute:
```php
class YourCrudController extends AbstractController
{
    ...
    #[Route('/api/items', name: 'get_all', methods: ['GET'])]
    #[RouteLink(routeName: 'create', name: 'createItem', method: 'POST'))]
    public function getAll(): JsonResponse
    {
        $items = $this->repository->findAll();
        
        return $this->json(['items' => $items]);
    }
    
    #[Route('/api/items/{id}', name: 'get_one', methods: ['GET'])]
    #[RouteLink(routeName: 'create', name: 'createItem', method: 'POST'))]
    #[RouteLink(routeName: 'update', ['id' => new FromRequest('id')], name: 'updateItem', method: 'PUT'))]
    #[RouteLink(routeName: 'delete', ['id' => new FromRequest('id')], name: 'deleteItem', method: 'DELETE'))]
    public function getOne(string $id): JsonResponse
    {
        $item = $this->repository->get($id);
        
        return $this->json($item);
    }
    
    #[Route('/api/items', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $itemResource = $this->serializer->deserialize($request->getContent(), ItemResource::class, 'josn');
        $this->repository->save($itemResource->toItem());
        
        return $this->json();
    }
    
    #[Route('/api/items/{id}', name: 'update', methods: ['PUT'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $itemResource = $this->serializer->deserialize($request->getContent(), ItemResource::class, 'josn');
        ...
        $this->repository->save($itemResource->toItem());
        
        return $this->json($itemResource);
    }
    
    #[Route('/api/items/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(string $id, Request $request): JsonResponse
    {
        $this->repository->delete($id);
        
        return $this->json();
    }
}
```

### `GET /api/resources` response:
```json
{
  "items": [{"id":  "item-1"}],
  "_links": {
    "self": {
      "href": "/api/items",
      "method": "GET"
    },
    "create": {
      "href": "/api/items",
      "method": "POST"
    }
  }
}
```

### `GET /api/resources/item-1` response:
We can see on this example that the `id` in the path has been replaced with the current `id`.
```json
{
  "id":  "item-1",
  "_links": {
    "self": {
      "href": "/api/item/item-1",
      "method": "GET"
    },
    "update": {
      "href": "/api/items/item-1",
      "method": "PUT"
    },
    "delete": {
      "href": "/api/items/item-1",
      "method": "DELETE"
    }
  }
}
```

## Add link with PHP
Say you want to add link on another endpoint depending on conditions, you can do that inside the controller:
```php
class YourCrudController extends AbstractController
{
    use HateOasTrait
    ...
    #[Route('/api/items', name: 'get_all', methods: ['GET'])]
    #[RouteLink(routeName: 'create', name: 'createItem', method: 'POST'))]
    public function getAll(): JsonResponse
    {
        $items = $this->repository->findAll();
        $links = [];
        if (...) {
            $links = [
                new Link('other_action', '/api/resources/do-other-action', 'POST'),
                new Link('another_action', '/api/resources/do-another-action', 'PUT'),
            ]
        }
        
        return $this->hateOasJson(['items' => $items], links: $links);
    }
}
```

### `GET /api/items`

```json
{
  "items": [{"id":  "item-1"}],
  "_links": {
    "self": {
      "href": "/api/items",
      "method": "GET"
    },
    "create": {
      "href": "/api/items",
      "method": "POST"
    },
    "other_action": {
      "href": "/api/resources/do-other-action",
      "method": "POST"
    },
    "another_action": {
      "href": "/api/resources/do-another-action",
      "method": "PUT"
    }
  }
}
```


## Contributing
You can contribute to this project via merge requests, or open an issue in Github, here: https://github.com/leon38/hateoas/issues

Pull Request Process
1. Update the README.md with details of changes to the interface, this includes new environment variables, exposed ports, useful file locations and container parameters.
2. You may merge the Pull Request in once you have the sign-off of one other developer.


## License
The MIT License (MIT)

Copyright (c) 2016 Pagar.me Pagamentos S/A

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

## Project status
Active development

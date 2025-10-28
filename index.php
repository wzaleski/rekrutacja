<?php
require_once './vendor/autoload.php';

use App\Controller\VehicleController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\{
    Exception\MethodNotAllowedException,
    Exception\ResourceNotFoundException,
    Matcher\UrlMatcher,
    RequestContext,
    Route,
    RouteCollection,
};

try {
    $routes = new RouteCollection();

    $routes->add('index', new Route(
        path: '/vehicles',
        defaults: ['controller' => VehicleController::class, 'method' => 'index'],
        methods: ['GET'],
    ));
    $routes->add('list', new Route(
        path: '/vehicles/list',
        defaults: ['controller' => VehicleController::class, 'method' => 'list'],
        methods: ['GET'],
    ));
    $routes->add('get_by_id', new Route(
        path: '/vehicles/{id}',
        defaults: ['controller' => VehicleController::class, 'method' => 'getById'],
        requirements: ['id' => '[0-9]+'],
        methods: ['GET'],
    ));
    $routes->add('vehicle_create', new Route(
        path: '/vehicles',
        defaults: ['controller' => VehicleController::class, 'method' => 'save'],
        methods: ['POST'],
    ));
    $routes->add('vehicle_update', new Route(
        path: '/vehicles/{id}',
        defaults: ['controller' => VehicleController::class, 'method' => 'save'],
        requirements: ['id' => '[0-9]+'],
        methods: ['PUT'],
    ));
    $routes->add('vehicle_delete', new Route(
        path: '/vehicles/{id}',
        defaults: ['controller' => VehicleController::class, 'method' => 'delete'],
        requirements: ['id' => '[0-9]+'],
        methods: ['DELETE'],
    ));

    $request = Request::createFromGlobals();

    $context = new RequestContext();
    $context->fromRequest($request);

    $matcher = new UrlMatcher($routes, $context);
    $parameters = $matcher->match($context->getPathInfo());

    $controller = new $parameters['controller'];
    $action = $parameters['method'];
    $controller->$action(
        $parameters['id'] ?? null,
        in_array($request->getMethod(),['POST', 'PUT']) ? $request : null,
    );
} catch (ResourceNotFoundException $e) {
    (new Response(content: $e->getMessage(), status: 404))->send();
} catch (MethodNotAllowedException $e) {
    (new Response(content: 'Method Not Allowed', status: 405))->send();
} catch (Throwable $e) {
    (new Response(content: $e->getMessage(), status: 500))->send();
}

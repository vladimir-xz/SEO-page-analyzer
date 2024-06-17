<?php

use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use DI\Container;
use Hexlet\Code\Engine;
use Hexlet\Code\Validator;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../view');
});

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);
$app->add(MethodOverrideMiddleware::class);
$router = $app->getRouteCollector()->getRouteParser();

$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'index.phtml');
});

$app->get('/urls/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $engine = new Engine('urls', 'find', $id);
    $url = $engine->process();
    $params = [
        'url' => $url,
    ];
    return $this->get('renderer')->render($response, 'url.phtml', $params);
});

$app->get('/urls', function ($request, $response, $args) {
    $engine = new Engine('urls', 'get');
    $urls = $engine->process();
    $params = [
        'urls' => $urls,
    ];
    return $this->get('renderer')->render($response, 'urls.phtml', $params);
});

$app->post('/urls', function ($request, $response) {
    $validator = new Validator();
    $url = $request->getParsedBodyParam('url');
    $errors = $validator->validate($url);
        $engine = new Engine('urls', 'insert', $url['name']);
        $insertedId = $engine->process();
        return $response->withRedirect("/urls/{$insertedId}", 302);
    $params = [
        'url' => $url,
        'errors' => $errors
    ];
    return $this->get('renderer')->render($response, "index.phtml", $params);
});

$app->run();

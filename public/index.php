<?php

use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use DI\Container;
use Hexlet\Code\Engine;
use Hexlet\Code\Validator;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../view');
});
$container->set('flash', function () {
    return new \Slim\Flash\Messages();
});

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);
$app->add(MethodOverrideMiddleware::class);
$router = $app->getRouteCollector()->getRouteParser();

$app->get('/', function ($request, $response) {
    $messages = $this->get('flash')->getMessages();
    return $this->get('renderer')->render($response, 'index.phtml');
});

$app->get('/urls/{id}', function ($request, $response, $args) {
    $messages = $this->get('flash')->getMessages();
    $id = $args['id'];
    $engine = new Engine('urls', 'find', $id);
    $url = $engine->process();
    $params = [
        'url' => $url,
        'flash' => $messages
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
    if (count($errors) === 0) {
        $engine = new Engine('urls', 'insert', $url['name']);
        $insertedId = $engine->process();
        $this->get('flash')->addMessage('success', 'Страница успешно добавлена');
        return $response->withRedirect("/urls/{$insertedId}", 302);
    }
    $params = [
        'url' => $url,
        'errors' => 5
    ];
    return $this->get('renderer')->render($response, "index.phtml", $params);
});

$app->run();

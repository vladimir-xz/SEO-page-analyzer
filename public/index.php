<?php

use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use DI\Container;
use Hexlet\Code\DbHandler;
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

$app->get('/', function ($request, $response) use ($router) {
    $messages = $this->get('flash')->getMessages();
    $url = $request->getParsedBodyParam('url');
    $params = [
        'flash' => $messages,
        'url' => $url
    ];
    return $this->get('renderer')->render($response, 'index.phtml', $params);
})->setName('main');

$app->get('/urls/{id}', function ($request, $response, $args) use ($router) {
    $messages = $this->get('flash')->getMessages();
    $id = $args['id'];
    $dbHandler = new DbHandler('urls');
    $url = $dbHandler->process('find', $id);
    $params = [
        'url' => $url,
        'flash' => $messages
    ];
    return $this->get('renderer')->render($response, 'url.phtml', $params);
})->setName('url');

$app->get('/urls', function ($request, $response) use ($router) {
    $dbHandler = new DbHandler('urls');
    $urls = $dbHandler->process('get');
    $params = [
        'urls' => $urls,
    ];
    return $this->get('renderer')->render($response, 'urls.phtml', $params);
})->setName('urls');

$app->post('/urls', function ($request, $response) use ($router) {
    $validator = new Validator();
    $dbHandler = new DbHandler('urls');
    $url = $request->getParsedBodyParam('url');
    $errors = $validator->validate($url);
    $existingUrl = $dbHandler->process('findByUrl', $url['name']);
    if ($existingUrl) {
        $this->get('flash')->addMessage('success', 'Страница уже существует');
        return $response->withRedirect($router->
        urlFor('url', ['id' => $existingUrl]), 302);
    } elseif (count($errors) === 0) {
        $insertedId = $dbHandler->process('insert', $url['name']);
        $this->get('flash')->addMessage('success', 'Страница успешно добавлена');
        return $response->withRedirect($router->
        urlFor('url', ['id' => $insertedId]), 302);
    }
    $this->get('flash')->addMessage('error', 'Некорректный URL');
    $params = [
        'url' => $url['name'],
        'errors' => $errors
    ];
    return $this->get('renderer')->render($response, "index.phtml", $params);
});

$app->run();

<?php

use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use DI\Container;
use Hexlet\Code\AnalyzeUrl\Engine;
use Hexlet\Code\Check;
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
    $url = $dbHandler->process('find by id', $id);
    $checkRecords = $dbHandler->process('getCheckRecords', $id);
    $params = [
        'url' => $url,
        'checks' => $checkRecords,
        'flash' => $messages
    ];
    return $this->get('renderer')->render($response, 'url.phtml', $params);
})->setName('url');

$app->get('/urls', function ($request, $response) use ($router) {
    $dbHandler = new DbHandler('urls');
    $urls = $dbHandler->process('get urls');
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
    $existingUrl = $dbHandler->process('find by url', $url['name']);
    if ($existingUrl) {
        $this->get('flash')->addMessage('success', 'Страница уже существует');
        return $response->withRedirect($router->
        urlFor('url', ['id' => $existingUrl]), 302);
    } elseif (count($errors) === 0) {
        $insertedId = $dbHandler->process('insert url', $url['name']);
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

$app->post('/urls/{url_id}/checks', function ($request, $response, $args) use ($router) {
    $dbHandler = new DbHandler('urls');
    $analyzer = new Engine('CheckConnection', 'CheckParams');
    $urlId = $args['url_id'];
    $url = $dbHandler->process('find by id', $urlId);
    $checkResult = $analyzer->process($url);
    if (is_string($checkResult)) {
        $this->get('flash')->addMessage('danger', 'Произошла ошибка при проверке, не удалось подключиться');
    } else {
        $lastUrl = $dbHandler->process('insert check', $checkResult);
        $this->get('flash')->addMessage('success', 'Страница успешно проверена');
    }
    return $response->withRedirect($router->
    urlFor('url', ['id' => $urlId]), 302);
});

$app->run();

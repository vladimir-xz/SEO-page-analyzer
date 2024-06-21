<?php

use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use DI\Container;
use Hexlet\Code\AnalyzeUrl\EngineAnalyze;
use Hexlet\Code\DbHandler;
use Hexlet\Code\ValidateUrl;

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
    return $this->get('renderer')->render($response, 'index.phtml');
})->setName('main');

$app->get('/urls/{id}', function ($request, $response, $args) {
    $messages = $this->get('flash')->getMessages();
    $id = $args['id'];
    $dbHandler = new DbHandler('urls');
    $url = $dbHandler->process('find by id', $id);
    if (!$url) {
        return $response->withStatus(404);
    }
    $checkRecords = $dbHandler->process('get check records', $id);
    $params = [
        'url' => $url,
        'checks' => $checkRecords,
        'flash' => $messages
    ];
    return $this->get('renderer')->render($response, 'url.phtml', $params);
})->setName('url');

$app->get('/urls', function ($request, $response) {
    $dbHandler = new DbHandler('urls');
    $urls = $dbHandler->process('get urls');
    $params = [
        'urls' => $urls,
    ];
    return $this->get('renderer')->render($response, 'urls.phtml', $params);
})->setName('urls');

$app->post('/urls', function ($request, $response) use ($router) {
    $dbHandler = new DbHandler('urls');
    $url = $request->getParsedBodyParam('url');
    $errors = ValidateUrl::validate($url['name']);
    if (count($errors) > 0) {
        $params = [
            'url' => $url['name'],
            'errors' => $errors
        ];
        return $this->get('renderer')->render($response, "index.phtml", $params)->withStatus(422);
    } elseif ($existingUrl = $dbHandler->process('find by url', $url['name'])) {
        $this->get('flash')->addMessage('success', 'Страница уже существует');
        return $response->withRedirect($router->
        urlFor('url', ['id' => $existingUrl]), 302);
    }
    $insertedId = $dbHandler->process('insert url', $url['name']);
    $this->get('flash')->addMessage('success', 'Страница успешно добавлена');
    return $response->withRedirect($router->
        urlFor('url', ['id' => $insertedId]), 302);
});

$app->post('/urls/{url_id}/checks', function ($request, $response, $args) use ($router) {
    $dbHandler = new DbHandler('urls');
    $analyzer = new EngineAnalyze('Check Connection', 'Check Params');
    $urlId = $args['url_id'];
    $url = $dbHandler->process('find by id', $urlId);
    if (!$url) {
        return $response->withStatus(404);
    }
    $checkResult = $analyzer->process($url);
    if ($checkResult instanceof \Exception) {
        $this->get('flash')->addMessage('danger', 'Произошла ошибка при проверке, не удалось подключиться');
    } else {
        $dbHandler->process('insert check', $checkResult);
        $this->get('flash')->addMessage('success', 'Страница успешно проверена');
    }
    return $response->withRedirect($router->
    urlFor('url', ['id' => $urlId]), 302);
});

$app->run();

<?php

use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use DI\Container;
use Hexlet\Code\Urls\Analyze\Engine;
use Hexlet\Code\DbHandler;
use Hexlet\Code\Urls\Prepare;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$container = new Container();

$container->set('flash', function () {
    return new \Slim\Flash\Messages();
});

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);
$app->add(MethodOverrideMiddleware::class);
$router = $app->getRouteCollector()->getRouteParser();

$container->set('renderer', function () use ($router, $container) {
    $messages = $container->get('flash')->getMessages();
    $phpView = new \Slim\Views\PhpRenderer(
        __DIR__ . '/../view',
        [
            'flash' => $messages,
            'index' => $router->urlFor('index'),
            'urlsShow' => fn($id) => $router->urlFor('urls.show', ['id' => $id]),
            'urlsIndex' => $router->urlFor('urls.index'),
            'urlsStore' => $router->urlFor('urls.store'),
            'urlsCheck' => fn($id) => $router->urlFor('urls.checks', ['url_id' => $id])
        ]
    );
    $phpView->setLayout('layout.phtml');
    return $phpView;
});

$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'index.phtml', ['main' => 'active']);
})->setName('index');

$app->get('/urls/{id:[0-9]+}', function ($request, $response, $args) {
    $id = $args['id'];
    $dbHandler = new DbHandler('urls');
    if (!$url = $dbHandler->process('find by id', $id)) {
        return $response->withStatus(404);
    }
    $checkRecords = $dbHandler->process('get check records', $id);
    $params = [
        'url' => $url,
        'checks' => $checkRecords,
    ];
    return $this->get('renderer')->render($response, '/urls/show.phtml', $params);
})->setName('urls.show');

$app->get('/urls', function ($request, $response) {
    $dbHandler = new DbHandler('urls');
    $urls = $dbHandler->process('get urls');
    $params = [
        'pages' => 'active',
        'urls' => $urls,
    ];
    return $this->get('renderer')->render($response, '/urls/index.phtml', $params);
})->setName('urls.index');

$app->post('/urls', function ($request, $response) use ($router) {
    $url = $request->getParsedBodyParam('url');
    $error = Prepare\Validate::validate($url['name']);
    if ($error != null) {
        $params = [
            'main' => 'active',
            'url' => $url['name'],
            'error' => $error
        ];
        return $this->get('renderer')->render($response, "index.phtml", $params)->withStatus(422);
    }
    $dbHandler = new DbHandler('urls');
    $normalizedUrl = Prepare\Normalize::process($url['name']);
    $existingUrl = $dbHandler->process('find by url', $normalizedUrl);
    if ($existingUrl) {
        $this->get('flash')->addMessage('success', 'Страница уже существует');
        return $response->withRedirect($router->
        urlFor('urls.show', ['id' => $existingUrl]), 302);
    } else {
        $insertedId = $dbHandler->process('insert url', $normalizedUrl);
        $this->get('flash')->addMessage('success', 'Страница успешно добавлена');
        return $response->withRedirect($router->
            urlFor('urls.show', ['id' => $insertedId]), 303);
    }
})->setName('urls.store');

$app->post('/urls/{url_id:[0-9]+}/checks', function ($request, $response, $args) use ($router) {
    $dbHandler = new DbHandler('urls');
    $analyzer = new Engine('Check Connection', 'Check Params');
    $id = $args['url_id'];
    if (!$url = $dbHandler->process('find by id', $id)) {
        return $response->withStatus(404);
    }
    $checkResult = $analyzer->process($url);
    if ($checkResult->getStatusCode() == 200) {
        $dbHandler->process('insert check', $checkResult);
        $this->get('flash')->addMessage('success', 'Страница успешно проверена');
    } elseif ($checkResult->getHtmlBody() != null) {
        $dbHandler->process('insert check', $checkResult);
        $this->get('flash')->addMessage('warning ', 'Проверка была выполнена успешно, но сервер ответил с ошибкой');
    } else {
        $this->get('flash')->addMessage('danger', 'Произошла ошибка при проверке, не удалось подключиться');
    }
    return $response->withRedirect($router->
    urlFor('urls.show', ['id' => $id]), 303);
})->setName('urls.checks');

$app->run();

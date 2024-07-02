<?php

use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use DI\Container;
use Hexlet\Code\Urls\Analyze\Engine;
use Hexlet\Code\DbHandler;
use Hexlet\Code\Urls\Prepare;

use function DI\create;
use function DI\get;

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
    $messages = $container->get('flash')->create()->getMessages();
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

$container->set('dbUrls', function () {
    $dbConnect = new \Hexlet\Code\Urls\Database\Connect();
    return new \Hexlet\Code\Urls\Database\DbUrls($dbConnect);
});

$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'index.phtml', ['main' => 'active']);
})->setName('index');

$app->get('/urls/{id:[0-9]+}', function ($request, $response, $args) {
    $id = $args['id'];
    $urlsDatabase = $this->get('dbUrls');
    if (!$url = $urlsDatabase->findById($id)) {
        return $response->withStatus(404);
    }
    $checkRecords = $urlsDatabase->getCheckRecords($id);
    $params = [
        'url' => $url,
        'checks' => $checkRecords,
    ];
    return $this->get('renderer')->render($response, '/urls/show.phtml', $params);
})->setName('urls.show');

$app->get('/urls', function ($request, $response) {
    $urlsDatabase = $this->get('dbUrls');
    $urls = $urlsDatabase->getUrls();
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
    $urlsDatabase = $this->get('dbUrls', 'Urls');
    $normalizedUrl = Prepare\Normalize::process($url['name']);
    $existingUrl = $urlsDatabase->findByUrl($normalizedUrl);
    if ($existingUrl) {
        $this->get('flash')->addMessage('success', 'Страница уже существует');
        return $response->withRedirect($router->
        urlFor('urls.show', ['id' => $existingUrl]), 302);
    } else {
        $insertedId = $urlsDatabase->insertUrl($normalizedUrl);
        $this->get('flash')->addMessage('success', 'Страница успешно добавлена');
        return $response->withRedirect($router->
            urlFor('urls.show', ['id' => $insertedId]), 303);
    }
})->setName('urls.store');

$app->post('/urls/{url_id:[0-9]+}/checks', function ($request, $response, $args) use ($router) {
    $urlsDatabase = $this->get('dbUrls');
    $analyzer = new Engine('Check Connection', 'Check Params');
    $id = $args['url_id'];
    if (!$url = $urlsDatabase->findById($id)) {
        return $response->withStatus(404);
    }
    $checkResult = $analyzer->process($url);
    if ($checkResult->getStatusCode() == 200) {
        $urlsDatabase->insertCheck($checkResult);
        $this->get('flash')->addMessage('success', 'Страница успешно проверена');
    } elseif ($checkResult->getHtmlBody() != null) {
        $urlsDatabase->insertCheck($checkResult);
        $this->get('flash')->addMessage('warning ', 'Проверка была выполнена успешно, но сервер ответил с ошибкой');
    } else {
        $this->get('flash')->addMessage('danger', 'Произошла ошибка при проверке, не удалось подключиться');
    }
    return $response->withRedirect($router->
    urlFor('urls.show', ['id' => $id]), 303);
})->setName('urls.checks');

$app->run();

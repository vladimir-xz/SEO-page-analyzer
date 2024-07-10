<?php

use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use DI\Container;
use GuzzleHttp\Client;
use DiDom\Document;
use Hexlet\Code\Urls\Validate;

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
            'urlsIndex' => $router->urlFor('urls.index')
        ]
    );
    $phpView->setLayout('layout.phtml');
    return $phpView;
});

$container->set('dbConnect', function () {
    return new \Hexlet\Code\Urls\Database\Connect();
});

$container->set('dbUrls', function () use ($container) {
    $dbConnect = $container->get('dbConnect');
    return new \Hexlet\Code\Urls\Database\DbUrls($dbConnect);
});

$app->get('/', function ($request, $response) use ($router) {
    return $this->get('renderer')->render($response, 'index.phtml', [
        'main' => 'active', 'urlsStore' => $router->urlFor('urls.store'),
    ]);
})->setName('index');

$app->get('/urls/{id:[0-9]+}', function ($request, $response, $args) use ($router) {
    $id = $args['id'];
    $urlsDatabase = $this->get('dbUrls');
    if (!$url = $urlsDatabase->findById($id)) {
        return $response->withStatus(404);
    }
    $checkRecords = $urlsDatabase->getUrlChecks($id);
    $params = [
        'url' => $url,
        'checks' => $checkRecords,
        'urlsCheck' => fn($id) => $router->urlFor('urls.checks', ['url_id' => $id])
    ];
    return $this->get('renderer')->render($response, '/urls/show.phtml', $params);
})->setName('urls.show');

$app->get('/urls', function ($request, $response) use ($router) {
    $urlsDatabase = $this->get('dbUrls');
    $urls = $urlsDatabase->getUrls();
    $params = [
        'pages' => 'active',
        'urls' => $urls,
        'urlsShow' => fn($id) => $router->urlFor('urls.show', ['id' => $id])
    ];
    return $this->get('renderer')->render($response, '/urls/index.phtml', $params);
})->setName('urls.index');

$app->post('/urls', function ($request, $response) use ($router) {
    $url = $request->getParsedBodyParam('url');
    $valideUrl = Validate::validate($url['name']);
    if (isset($valideUrl['errors'])) {
        $params = [
            'main' => 'active',
            'url' => $url['name'],
            'error' => $valideUrl['errors'][0],
            'urlsStore' => $router->urlFor('urls.store')
        ];
        return $this->get('renderer')->render($response, "index.phtml", $params)->withStatus(422);
    }
    $urlsDatabase = $this->get('dbUrls');
    $existingUrl = $urlsDatabase->findByUrl($valideUrl['url']);
    if ($existingUrl) {
        $this->get('flash')->addMessage('success', 'Страница уже существует');
        return $response->withRedirect($router->
        urlFor('urls.show', ['id' => $existingUrl]), 302);
    } else {
        $insertedId = $urlsDatabase->insertUrl($valideUrl['url']);
        $this->get('flash')->addMessage('success', 'Страница успешно добавлена');
        return $response->withRedirect($router->
            urlFor('urls.show', ['id' => $insertedId]), 303);
    }
})->setName('urls.store');

$app->post('/urls/{url_id:[0-9]+}/checks', function ($request, $response, $args) use ($router) {
    $urlsDatabase = $this->get('dbUrls');
    $id = $args['url_id'];
    if (!$url = $urlsDatabase->findById($id)) {
        return $response->withStatus(404);
    }
    try {
        $client = new Client();
        $res = $client->request('GET', $url->name, ['connect_timeout' => 3.14, 'http_errors' => false]);
        $status = $res->getStatusCode();
        $body = $res->getBody()->__toString();
        $document = new Document($body);
        $h1 = optional($document->first('h1'))->text();
        $title = optional($document->first('title'))->text();
        $description = optional($document->first('meta[name=description]'))->getAttribute('content');
        $params = [
            'url_id' => $id,
            'status_code' => $status,
            'h1' => $h1,
            'title' => $title,
            'description' => $description
        ];
        $urlsDatabase->insertCheck($params);
        $checkStatus = 303;
        if ($status == 200) {
            $this->get('flash')->addMessage('success', 'Страница успешно проверена');
        } else {
            $this->get('flash')->addMessage('warning ', 'Проверка была выполнена успешно, но сервер ответил с ошибкой');
        }
    } catch (\Exception $e) {
        $checkStatus = 302;
        $this->get('flash')->addMessage('danger', 'Произошла ошибка при проверке, не удалось подключиться');
    }
    return $response->withRedirect($router->
    urlFor('urls.show', ['id' => $id]), $checkStatus);
})->setName('urls.checks');

$app->run();

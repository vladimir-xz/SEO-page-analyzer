<?php

use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use DI\Container;
use Slim\Flash\Messages;
use GuzzleHttp\Client;
use Slim\Views\PhpRenderer;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use DiDom\Document;
use Hexlet\Code\Urls\Database\DbUrls;
use Hexlet\Code\Urls\UrlValidator;
use Hexlet\Code\Urls\Helpers\Utils;

use function DI\string;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$container = new Container();

$container->set('flash', new Messages());

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);
$app->add(MethodOverrideMiddleware::class);
$router = $app->getRouteCollector()->getRouteParser();

$container->set('renderer', function ($container) use ($router) {
    $messages = $container->get('flash')->getMessages();
    $phpView = new PhpRenderer(
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

$app->get('/', function ($request, $response) use ($router) {
    return $this->get('renderer')->render($response, 'index.phtml', [
        'main' => 'active', 'urlsStore' => $router->urlFor('urls.store'),
    ]);
})->setName('index');

$app->get('/urls/{id:[0-9]+}', function ($request, $response, $args) use ($router) {
    $id = $args['id'];
    $urlsDatabase = $this->get(DbUrls::class);
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
    $urlsDatabase = $this->get(DbUrls::class);
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
    $valideUrl = UrlValidator::validate($url['name']);
    if (isset($valideUrl['errors'])) {
        $params = [
            'main' => 'active',
            'url' => $url['name'],
            'error' => $valideUrl['errors'][0],
            'urlsStore' => $router->urlFor('urls.store')
        ];
        return $this->get('renderer')->render($response, "index.phtml", $params)->withStatus(422);
    }
    $normalUrl = Utils::normalize($valideUrl['url']);
    $urlsDatabase = $this->get(DbUrls::class);
    $existingUrl = $urlsDatabase->findByUrl($normalUrl);
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
    $urlsDatabase = $this->get(DbUrls::class);
    $id = $args['url_id'];
    if (!$url = $urlsDatabase->findById($id)) {
        return $response->withStatus(404);
    }

    try {
        $client = new Client();
        $res = $client->request('GET', $url->name, ['connect_timeout' => 3.14]);
        $status = $res->getStatusCode();
        $body = (string) $res->getBody();
        $this->get('flash')->addMessage('success', 'Страница успешно проверена');
    } catch (ConnectException  $e) {
        $this->get('flash')->addMessage('danger', 'Произошла ошибка при проверке, не удалось подключиться');
        return $response->withRedirect($router->
            urlFor('urls.show', ['id' => $id]));
    } catch (RequestException $e) {
        $clientResponse = $e->getResponse();
        $status = $clientResponse->getStatusCode();
        $body = (string) $clientResponse->getBody();
        $this->get('flash')->addMessage('warning ', 'Проверка была выполнена успешно, но сервер ответил с ошибкой');
    }

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
    return $response->withRedirect($router->
    urlFor('urls.show', ['id' => $id]), 303);
})->setName('urls.checks');

$app->run();

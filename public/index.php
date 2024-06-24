<?php

use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use DI\Container;
use Hexlet\Code\AnalyzeUrl\EngineAnalyze;
use Hexlet\Code\DbHandler;
use Hexlet\Code\PrepareUrl;

use function DI\string;

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
    if (!is_numeric($id) || !$url = $dbHandler->process('find by id', $id)) {
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
    $url = $request->getParsedBodyParam('url');
    $errors = PrepareUrl\Validate::validate($url['name']);
    if (count($errors) > 0) {
        $params = [
            'url' => $url['name'],
            'errors' => $errors
        ];
        return $this->get('renderer')->render($response, "index.phtml", $params)->withStatus(422);
    }
    $dbHandler = new DbHandler('urls');
    $normalizedUrl = PrepareUrl\Normalize::process($url['name']);
    $existingUrl = $dbHandler->process('find by url', $normalizedUrl);
    if ($existingUrl) {
        $this->get('flash')->addMessage('success', 'Страница уже существует');
        return $response->withRedirect($router->
        urlFor('url', ['id' => $existingUrl]), 302);
    } else {
        $insertedId = $dbHandler->process('insert url', $normalizedUrl);
        $this->get('flash')->addMessage('success', 'Страница успешно добавлена');
        return $response->withRedirect($router->
            urlFor('url', ['id' => $insertedId]), 303);
    }
});

$app->post('/urls/{url_id}/checks', function ($request, $response, $args) use ($router) {
    $dbHandler = new DbHandler('urls');
    $analyzer = new EngineAnalyze('Check Connection', 'Check Params');
    $id = $args['url_id'];
    if (!is_numeric($id) || !$url = $dbHandler->process('find by id', $id)) {
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
    urlFor('url', ['id' => string($id)]), 303);
});

$app->run();

<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

// Fix: Change the period to a comma here
$container->set('templating', function(){
    return new Mustache_Engine([
        'loader' => new Mustache_Loader_FilesystemLoader(
            __DIR__.'/../templates',
            ['extension' => '']
        )
    ]);
});

AppFactory::setContainer($container);

$app = AppFactory::create();


$app->get('/', 'App\Controller\AlbumsController:default');
$app->get('/details/{id:[0-9]+}', 'App\Controller\AlbumsController:details');
$app->get('/search', 'App\Controller\AlbumsController:search');
$app->any('/form', 'App\Controller\AlbumsController:form');
$app->get('/api', 'App\Controller\ApiController:search');

$errorMiddelware = $app->addErrorMiddleware(true, true, true);

$errorMiddelware->setErrorHandler(
    Slim\Exception\HttpNotFoundException::class,
    function(Psr\Http\Message\ServerRequestInterface $request) use ($container){
        $controller = new App\Controller\ExceptionController($container);
        return $controller->notFound($request);
    }
);

$app->run();

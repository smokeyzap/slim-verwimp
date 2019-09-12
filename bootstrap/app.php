<?php
session_start();
ini_set('date.timezone', 'Asia/Manila');

require __DIR__ . '/../vendor/autoload.php';
$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

// Create app
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => getenv('DISPLAY_ERRORS')
    ]
]);

//Database setup
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection([
    'driver'    => getenv('DB_DRIVER'),
    'host'      => getenv('DB_HOSTNAME'),
    'database'  => getenv('DB_NAME'),
    'username'  => getenv('DB_USERNAME'),
    'password'  => getenv('DB_PASSWORD'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Get container
$container = $app->getContainer();

//Register flash on container
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => false,
    ]);
    
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    $view->getEnvironment()->addGlobal('user_id', isset($_SESSION['id']) ? $_SESSION['id'] : '');
    $view->getEnvironment()->addGlobal('customer_number', isset($_SESSION['customer_number']) ? $_SESSION['customer_number'] : '');
    $view->getEnvironment()->addGlobal('name', isset($_SESSION['name']) ? $_SESSION['name'] : '');
    $view->getEnvironment()->addGlobal('lang', $container->lang);
    $view->getEnvironment()->addGlobal('flash', $container->flash);
    $view->getEnvironment()->addGlobal('client_url', getenv('CLIENT_URL'));//local
    //$view->getEnvironment()->addGlobal('flash', 'domain.com');//live
    $view->addExtension(new App\Extension\CsrfExtension($container['csrf']));
    return $view;
};

//Register csrf on container
$container['csrf'] = function ($c) {
    $guard =  new \Slim\Csrf\Guard;
    $guard->setPersistentTokenMode(true);
    return $guard;
};

//Overide notfoundhandler
$container['notFoundHandler'] = function  ($c) {
    return function ($request, $response) use ($c) {
        return $c->view->render($response, 'errors/404.twig')->withStatus(404);
    };
};

//Register Middleware
$app->add($container->get('csrf'));

//Register CORS globally
$app->add(new \App\Middleware\Cors($container));

//Mailer
$container['mailer'] = function () {
    $apiKey = getenv('SENDGRID_API_KEY');
    return new \App\Mail\Mailer($apiKey);
};

//Language
$container['lang'] = function () {
    return new \App\Language\Lang;
};

$container['ssp'] = function () {
    return new \App\Extension\SSP;
};

//IP address
$checkProxyHeaders = true; // Note: Never trust the IP address for security processes!
$trustedProxies = ['10.0.0.1', '10.0.0.2']; // Note: Never trust the IP address for security processes!
$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));

require __DIR__ . '/../routes/web.php';
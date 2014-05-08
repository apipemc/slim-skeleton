<?php
session_start();

\Slim\Slim::registerAutoloader();

$app = new \SlimController\Slim(array('templates.path'   => dirname(__DIR__).'/views/',
                            'cookies.secret_key'         => md5('appsecretkey'), 
                            'controller.class_prefix'    =>'',
                            'controller.method_suffix'   => '',
                            'controller.template_suffix' => 'twig'
                     ));

// Create monolog logger and store logger in container as singleton 
// (Singleton resources retrieve the same log resource definition each time)
// $app->log->info("Log error");
$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('slim-skeleton');
    $log->pushHandler(new \Monolog\Handler\StreamHandler(dirname(__DIR__).'/logs/app.log', \Monolog\Logger::DEBUG));
    return $log;
});

$app->view(new \Slim\Views\Twig());
$app->view->parserOptions = array(
    'debug'            => true,
    'charset'          => 'utf-8',
    'cache'            => dirname(__DIR__).'/views/cache',
    'auto_reload'      => true,
    'strict_variables' => false,
    'autoescape'       => true
);
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension(), new Twig_Extension_Debug());
$app->view->appendData(array( 'app' => $app ));

/**
 * Require all models
 */
foreach (glob(dirname(__DIR__)."/models/*.php") as $filename) {
    require $filename;
}

/**
 * Require all routes
 */
foreach (glob(dirname(__DIR__)."/controllers/*.php") as $filename) {    
    require $filename;
}

require dirname(__DIR__)."/routes.php";

$app->run(); 

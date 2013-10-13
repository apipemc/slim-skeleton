<?php
session_start();

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array('mode' => 'development',
			    'view' => '\Slim\LayoutView',
			    'templates.path' => dirname(__DIR__).'/views',
			    'layout' => 'layouts/layout.php'
			));

// Only invoked if mode is "production"
$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'log.level'  => 4,
        'log.path'   => dirname(__DIR__).'/logs',
        'debug'      => false
    ));
});

// Only invoked if mode is "development"
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'log.enable' => false,
        'debug'      => true
    ));
});

/**
 * Require all routes
 */
$filename='';
foreach (glob(dirname(__DIR__)."/routes/*.php") as $filename) {
    require $filename;
}

$app->run(); 

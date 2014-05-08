<?php
session_start();

\Slim\Slim::registerAutoloader();

$app = new \SlimController\Slim(array('templates.path'   => dirname(__DIR__).'/views/',
                            'cookies.secret_key'         => md5('appsecretkey'), 
                            'controller.class_prefix'    =>'',
                            'controller.method_suffix'   => '',
                            'controller.template_suffix' => 'twig'
                     ));

/** Create monolog logger and store logger in container as singleton 
 * (Singleton resources retrieve the same log resource definition each time)
 *  $app->log->info("Log error");
 *  try catch hook
 *  $app->applyHook('log.request.info', $e->getMessage());
 *  $app->log->error($e);
 */
$app->container->singleton('log', function () use ($app) {

    $logpath = dirname(__DIR__).'/logs/'.date('Y/m');
    $logfile = $logpath.'/'.date('d').'.log';

    $old = umask(0);
    if(!is_dir($logpath)){ mkdir($logpath, 0777, true); }
    if(!is_writable($logpath)){ chmod($logfile, 0777); }
    if(!file_exists($logfile)){ file_put_contents($logfile, ''); }
    umask($old);

    $log = new \Monolog\Logger(strtoupper($app->request->getHost()));
    $log->pushHandler(new \Monolog\Handler\StreamHandler($logfile, \Monolog\Logger::DEBUG, true, 0777));

    return $log;
});

/**
 * Register handlers
 */
\Monolog\ErrorHandler::register($app->log);


/**
 * Config Views
 */
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

require dirname(__DIR__)."/hooks.php";
require dirname(__DIR__)."/routes.php";

$app->run(); 

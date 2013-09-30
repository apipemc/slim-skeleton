<?php

$app->get('/', function () use ($app){
	$app->view->setData(array('title' => 'Index'));
    $app->render('index.php');
});

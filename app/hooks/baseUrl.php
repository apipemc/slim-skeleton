<?php 

$app->hook('slim.before', function () use ($app) {
	$app->view()->setData(array('baseUrl' => $app->request->getRootUri() ));
});
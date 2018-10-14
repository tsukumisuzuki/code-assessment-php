<?php

// API group
$app->group('/api', function () use ($app) {
    // Version group
	$app->get('/employees', \Demo\Controller\ExampleController::class . ':getEmployees');
  $app->get('/employee/{id}', \Demo\Controller\ExampleController::class . ':getEmployee');
});

?>

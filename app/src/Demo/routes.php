<?php

// API group
$app->group('/api', function () use ($app) {
  // module group
  $app->group('/employee', function () use ($app) {
  	$app->get('/', \Demo\Model\Employees::class . ':getEmployees');
    $app->get('/{id}', \Demo\Model\Employees::class . ':getEmployee');
    $app->post('/', \Demo\Model\Employees::class . ':createEmployee');
    $app->put('/{id}', \Demo\Model\Employees::class . ':updateEmployee');
    $app->delete('/{id}', \Demo\Model\Employees::class . ':deleteEmployee');
  });
  $app->group('/jobtitle', function () use ($app) {
  	$app->get('/', \Demo\Model\JobTitles::class . ':getJobTitles');
    $app->get('/{id}', \Demo\Model\JobTitles::class . ':getJobTitle');
  });
  $app->group('/location', function () use ($app) {
  	$app->get('/', \Demo\Model\Locations::class . ':getLocations');
    $app->get('/{id}', \Demo\Model\Locations::class . ':getLocation');
  });
});

?>

<?php

// API group
$app->group('/api', function () use ($app) {
    // Version group
	$app->get('/employees', \Demo\Model\Project::class . ':getEmployees');
  $app->get('/employee/{id}', \Demo\Model\Project::class . ':getEmployee');
  $app->get('/jobtitles', \Demo\Model\Project::class . ':getJobTitles');
  $app->get('/jobtitle/{id}', \Demo\Model\Project::class . ':getJobTitle');
  $app->get('/locations', \Demo\Model\Project::class . ':getLocations');
  $app->get('/location/{id}', \Demo\Model\Project::class . ':getLocation');
});

?>

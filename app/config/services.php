<?php

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new \Phalcon\DI\FactoryDefault();


use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Manager as EventsManager;

$di->set('dispatcher', function () {

	// Create an events manager
	$eventsManager = new EventsManager();

	// Listen for events produced in the dispatcher using the Security plugin
	$eventsManager->attach('dispatch:beforeExecuteRoute', new SecurityPlugin);

	// Handle exceptions and not-found exceptions using NotFoundPlugin
	$eventsManager->attach('dispatch:beforeException', new NotFoundPlugin);

	$dispatcher = new Dispatcher();

	// Assign the events manager to the dispatcher
	$dispatcher->setEventsManager($eventsManager);

	return $dispatcher;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function() use ($config) {
	$url = new \Phalcon\Mvc\Url();
	$url->setBaseUri($config->application->baseUri);
	return $url;
});

/**
 * Setting up the view component
*/
$di->set('view', function () use ($config) {
	$view = new \Phalcon\Mvc\View();
	$view->setViewsDir($config->application->viewsDir);
	return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
$di->set('db', function () use ($config) {
	return new DbAdapter(
		array(
			"host"     => $config->database->host,
			"username" => $config->database->username,
			"password" => $config->database->password,
			"dbname"   => $config->database->name
		)
	);
});

use Phalcon\Flash\Session as FlashSession;

$di->set('flash', function () {
	return new FlashSession(array(
		'error'   => 'alert alert-danger',
		'success' => 'alert alert-success',
		'notice'  => 'alert alert-info',
		'warning' => 'alert alert-warning'
	));
});


/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function() {
	$session = new \Phalcon\Session\Adapter\Files();
	$session->start();
	return $session;
});
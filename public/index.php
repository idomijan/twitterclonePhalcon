<?php

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\Dispatcher;


try {

	// Register an autoloader
	$loader = new Loader();
	$loader->registerDirs(array(
		'../app/controllers/',
		'../app/models/'
	))->register();

	// Create a DI
	$di = new FactoryDefault();

	$di->set('db', function () {
		return new DbAdapter(array(
			"host"     => "localhost",
			"username" => "root",
			"password" => "root",
			"dbname"   => "twitter"
		));
	});

	// Setup the view component
	$di->set('view', function () {
		$view = new View();
		$view->setViewsDir('../app/views/');
		return $view;
	});

	/**
	 * MVC dispatcher
	 */
	$di->set('dispatcher', function () {

		// ...

		$dispatcher = new Dispatcher();

		return $dispatcher;
	});

	// Setup a base URI so that all generated URIs include the "tutorial" folder
	$di->set('url', function () {
		$url = new UrlProvider();
		$url->setBaseUri('/');
		return $url;
	});

	// Handle the request
	$application = new Application($di);

	echo $application->handle()->getContent();

} catch (\Exception $e) {
	echo "PhalconException: ", $e->getMessage();
}
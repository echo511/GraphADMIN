<?php

require_once __DIR__ . '/../vendor/autoload.php';


/*********** Common settings *********n*t*/
$configurator = new \Nette\Configurator();
$configurator->setTempDirectory(__DIR__ . '/../temp');


/*********** Application settings *********n*t*/
$configurator->addConfig(__DIR__ . '/../app/config/common.neon');
$configurator->addConfig(__DIR__ . '/../app/config/local.neon');


/*********** Debugger *********n*t*/
$configurator->setDebugMode(TRUE); // @todo Disable in production
$configurator->enableDebugger(__DIR__ . '/../log');


/*********** Run *********n*t*/
$container = $configurator->createContainer();
/** @var \Nette\Application\Application $application */
$application = $container->getByType(\Nette\Application\Application::class);
$application->run();

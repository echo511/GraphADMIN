<?php

namespace Echo511\GraphADMIN\LeanMapper\DI;

use Nette\DI\CompilerExtension;

class LeanMapperExtension extends CompilerExtension
{

	public $config = array(
	    'host' => '127.0.0.1',
	    'username' => 'root',
	    'password' => '',
	    'database' => 'graph',
	);

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->config);

		$connection = $this->containerBuilder->addDefinition($this->prefix('connection'))
			->setClass('LeanMapper\Connection', array(array(
			'host' => $config['host'],
			'username' => $config['username'],
			'password' => $config['password'],
			'database' => $config['database']
		)));

		//////
		////// LeanMapper internals
		$this->containerBuilder->addDefinition($this->prefix('mapper'))
			->setClass('Echo511\GraphADMIN\LeanMapper\Mapper');

		$this->containerBuilder->addDefinition($this->prefix('entityFactory'))
			->setClass('Echo511\GraphADMIN\LeanMapper\EntityFactory');

		$useProfiler = isset($config['profiler']) ? $config['profiler'] : $this->containerBuilder->parameters['debugMode'];

		unset($config['profiler']);

		if ($useProfiler) {
			$panel = $this->containerBuilder->addDefinition($this->prefix('panel'))
				->setClass('DibiNettePanel')
				->addSetup('Nette\Diagnostics\Debugger::getBar()->addPanel(?)', array('@self'))
				->addSetup('Nette\Diagnostics\Debugger::getBlueScreen()->addPanel(?)', array('DibiNettePanel::renderException'));

			$connection->addSetup('$service->onEvent[] = ?', array(array($panel, 'logEvent')));
		}

		//////
		////// LeanMapper repositories
		$this->containerBuilder->addDefinition($this->prefix('repoedgeRepository'))
			->setClass('Echo511\GraphADMIN\LeanMapper\Repository\EdgeRepository');

		$this->containerBuilder->addDefinition($this->prefix('reponodeRepository'))
			->setClass('Echo511\GraphADMIN\LeanMapper\Repository\NodeRepository');

		//////
		////// Graph repositories
		$this->containerBuilder->addDefinition($this->prefix('edgeRepository'))
			->setClass('Echo511\GraphADMIN\LeanMapper\Model\EdgeRepository');

		$this->containerBuilder->addDefinition($this->prefix('nodeRepository'))
			->setClass('Echo511\GraphADMIN\LeanMapper\Model\NodeRepository');
				
		//////
		////// Loaders
		$this->containerBuilder->addDefinition($this->prefix('edgesLoader'))
			->setClass('Echo511\GraphADMIN\LeanMapper\Loader\EdgesLoader');
	}



}

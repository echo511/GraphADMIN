<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\LeanMapper\DI;

use Nette\DI\CompilerExtension;

/**
 * Add LeanMapper GraphADMIN database driver to Nette DI container.
 * @author Nikolas Tsiongas
 */
class LeanMapperExtension extends CompilerExtension
{

	public $config = [
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'database' => 'graph',
	];


	public function loadConfiguration()
	{
		$config = $this->getConfig($this->config);

		$connection = $this->getContainerBuilder()->addDefinition($this->prefix('connection'))
			->setClass(\LeanMapper\Connection::class, [[
				'host' => $config['host'],
				'username' => $config['username'],
				'password' => $config['password'],
				'database' => $config['database'],
				'port' => $config['port'],
			]]);

		//////
		////// LeanMapper internals
		$this->getContainerBuilder()->addDefinition($this->prefix('mapper'))
			->setType(\Echo511\GraphADMIN\LeanMapper\Mapper::class);

		$this->getContainerBuilder()->addDefinition($this->prefix('entityFactory'))
			->setType(\Echo511\GraphADMIN\LeanMapper\EntityFactory::class);

		$useProfiler = isset($config['profiler']) ? $config['profiler'] : $this->getContainerBuilder()->parameters['debugMode'];

		unset($config['profiler']);

		if ($useProfiler) {
			$panel = $this->getContainerBuilder()->addDefinition($this->prefix('panel'))
				->setType(\Dibi\Bridges\Tracy\Panel::class)
				->addSetup(\Tracy\Debugger::class . '::getBar()->addPanel(?)', ['@self'])
				->addSetup(\Tracy\Debugger::class . '::getBlueScreen()->addPanel(?)', [\Dibi\Bridges\Tracy\Panel::class . '::renderException']);

			$connection->addSetup('$service->onEvent[] = ?', [[$panel, 'logEvent']]);
		}

		//////
		////// LeanMapper repositories
		$this->getContainerBuilder()->addDefinition($this->prefix('repoedgeRepository'))
			->setType(\Echo511\GraphADMIN\LeanMapper\Repository\EdgeRepository::class);

		$this->getContainerBuilder()->addDefinition($this->prefix('reponodeRepository'))
			->setType(\Echo511\GraphADMIN\LeanMapper\Repository\NodeRepository::class);

		//////
		////// Graph repositories
		$this->getContainerBuilder()->addDefinition($this->prefix('edgeRepository'))
			->setType(\Echo511\GraphADMIN\LeanMapper\Model\EdgeRepository::class);

		$this->getContainerBuilder()->addDefinition($this->prefix('nodeRepository'))
			->setType(\Echo511\GraphADMIN\LeanMapper\Model\NodeRepository::class);

		//////
		////// Loaders
		$this->getContainerBuilder()->addDefinition($this->prefix('edgesLoader'))
			->setType(\Echo511\GraphADMIN\LeanMapper\Loader\EdgesLoader::class);
	}


}

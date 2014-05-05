<?php

namespace Echo511\GraphADMIN\DI;

use Nette\DI\CompilerExtension;

/**
 * Add GraphADMIN to Nette DI container.
 * @author Nikolas Tsiongas
 */
class GraphADMINExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$this->containerBuilder->addDefinition($this->prefix('graphFacade'))
			->setClass('Echo511\GraphADMIN\Facade\GraphFacade');

		$this->containerBuilder->addDefinition($this->prefix('backup_jsonExporter'))
			->setClass('Echo511\GraphADMIN\Backup\JsonExporter');
	}



}

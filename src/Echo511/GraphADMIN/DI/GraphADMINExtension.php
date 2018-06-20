<?php declare(strict_types = 1);

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
		$this->getContainerBuilder()->addDefinition($this->prefix('graphFacade'))
			->setType(\Echo511\GraphADMIN\Facade\Neo4jGraphFacade::class);

		$this->getContainerBuilder()->addDefinition($this->prefix('neo4jFacade'))
			->setType(\Echo511\GraphADMIN\Neo4j\GraphFacade::class);

		$this->getContainerBuilder()->addDefinition($this->prefix('identityMap'))
			->setType(\Echo511\GraphADMIN\Neo4j\IdentityMap::class);

		$this->getContainerBuilder()->addDefinition($this->prefix('backup_jsonExporter'))
			->setType(\Echo511\GraphADMIN\Backup\JsonExporter::class);
	}


}

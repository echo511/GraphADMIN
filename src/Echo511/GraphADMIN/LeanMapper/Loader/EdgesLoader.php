<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\LeanMapper\Loader;

use LeanMapper\Connection;
use LeanMapper\IMapper;
use LeanMapper\Result;
use LeanMapper\ResultProxy;

/**
 * Preload edges for nodes in NotORM style.
 * @author Nikolas Tsiongas
 * @author Vojtěch Kohout Supplied solution for preloading edges. https://github.com/Tharos/LeanMapper/issues/53
 */
class EdgesLoader
{

	/** @var Connection */
	private $connection;

	/** @var IMapper */
	private $mapper;


	/**
	 * @param Connection $connection
	 * @param IMapper $mapper
	 */
	public function __construct(Connection $connection, IMapper $mapper)
	{
		$this->connection = $connection;
		$this->mapper = $mapper;
	}


	public function preloadEdges(ResultProxy $resultProxy)
	{
		$nodesIds = [];
		foreach ($resultProxy as $node) {
			$nodesIds[$node['id']] = TRUE;
		}

		$edges = $this->connection->select('*')
			->from('edge')
			->where('[source] IN %in OR [target] IN %in', $ids = array_keys($nodesIds), $ids)
			->orderBy('type')
			->fetchAll();

		$referencing = Result::createInstance([], 'edge', $this->connection, $this->mapper);
		foreach ($edges as $edge) {
			if (isset($nodesIds[$edge['source']]) || isset($nodesIds[$edge['target']])) {
				$edge = $edge->toArray();
				$edge['related_node'] = $edge['source'];
				$referencing->addDataEntry($edge);
				if ($edge['target'] !== $edge['source'] and $edge['target'] !== NULL) {
					$edge['related_node'] = $edge['target'];
					$referencing->addDataEntry($edge);
				}
			}
		}
		$referencing->cleanAddedAndRemovedMeta();
		$resultProxy->setReferencingResult($referencing, 'edge', 'related_node');
	}


}

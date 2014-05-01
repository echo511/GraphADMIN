<?php

namespace Echo511\GraphADMIN\LeanMapper\Loader;

use LeanMapper\Connection;
use LeanMapper\IMapper;
use LeanMapper\Result;
use LeanMapper\ResultProxy;
use Nette\Object;

class EdgesLoader extends Object
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
		$nodesIds = array();
		foreach ($resultProxy as $node) {
			$nodesIds[$node['id']] = true;
		}
		
		$edges = $this->connection->select('*')
			->from('edge')
			->where('[source] IN %in OR [target] IN %in', $ids = array_keys($nodesIds), $ids)
			->orderBy('type')
			->fetchAll();
		
		$referencing = Result::createInstance(array(), 'edge', $this->connection, $this->mapper);
		foreach ($edges as $edge) {
			if (isset($nodesIds[$edge['source']]) || isset($nodesIds[$edge['target']])) {
				$edge = $edge->toArray();
				$edge['related_node'] = $edge['source'];
				$referencing->addDataEntry($edge);
				if ($edge['target'] !== $edge['source'] and $edge['target'] !== null) {
					$edge['related_node'] = $edge['target'];
					$referencing->addDataEntry($edge);
				}
			}
		}
		$referencing->cleanAddedAndRemovedMeta();
		$resultProxy->setReferencingResult($referencing, 'edge', 'related_node');
	}



}

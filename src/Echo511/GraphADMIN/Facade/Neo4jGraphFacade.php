<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\Facade;

class Neo4jGraphFacade implements \Echo511\GraphADMIN\IGraph
{

	/**
	 * @var \Echo511\GraphADMIN\Neo4j\GraphFacade
	 */
	private $facade;


	/**
	 * Neo4jGraphFacade constructor.
	 * @param \Echo511\GraphADMIN\Neo4j\GraphFacade $facade
	 */
	public function __construct(\Echo511\GraphADMIN\Neo4j\GraphFacade $facade)
	{
		$this->facade = $facade;
	}


	public function getNode($label)
	{
		$node = $this->facade->fetchNodeByLabel($label);

		if (!$node) {
			$node = $this->facade->createNode($label);
		}

		return $node;
	}


	public function getRandomNode()
	{
		return NULL; // @todo
	}


	public function changeNodeLabel($id, $label)
	{
		$node = $this->facade->fetchNode($id);
		$node->setLabel($label);
	}


	public function deleteNode($id)
	{
		$this->facade->deleteNode($id);
	}


	public function changeNodeProperty($id, $property, $value)
	{
		$node = $this->facade->fetchNode($id);
		$node->setProperty($property, $value);
	}


	public function createEdge($sourceLabel, $targetLabel, $label)
	{
		$sourceUuid = $this->getNode($sourceLabel)->getId();
		$targetUuid = $this->getNode($targetLabel)->getId();

		$this->facade->createEdge($sourceUuid, $targetUuid, $label);
	}


	public function changeEdgeLabel($id, $label)
	{
		$edge = $this->facade->fetchEdge($id);
		$edge->setLabel($label);
	}


	public function deleteEdge($id)
	{
		$this->facade->deleteEdge($id);
	}


	public function nodeTypehint($label)
	{
		return $this->facade->typeHint($label);
	}


	public function sigmaJS(
		\Echo511\GraphADMIN\Controls\SigmaJS $sigmaJS,
		?\Echo511\GraphADMIN\INode $node,
		$depth = 2,
		array & $drawnNodesIdsMap = []
	): void {
		if ($node) {
			if (!\array_key_exists($node->getUuid(), $drawnNodesIdsMap)) {
				$drawnNodesIdsMap[$node->getUuid()] = TRUE;
				$sigmaJS->drawNode($node);
				if ($depth > 0) {
					$sigmaJS->drawEdges($node->getEdges());
					foreach ($node->getEdges() as $edge) {
						$this->sigmaJS($sigmaJS, $edge->getSource(), $depth - 1, $drawnNodesIdsMap);
						$this->sigmaJS($sigmaJS, $edge->getTarget(), $depth - 1, $drawnNodesIdsMap);
					}
				}
			}
		}
	}


	public function export()
	{
		// TODO: Implement export() method.
	}


}

<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\Neo4j;

class Node implements \Echo511\GraphADMIN\INode
{

	/**
	 * @var \GraphAware\Common\Type\NodeInterface
	 */
	private $graphNode;

	/**
	 * @var \Echo511\GraphADMIN\Neo4j\GraphFacade
	 */
	private $facade;

	private $edges;


	public function __construct(\GraphAware\Common\Type\NodeInterface $graphNode, \Echo511\GraphADMIN\Neo4j\GraphFacade $facade)
	{
		$this->graphNode = $graphNode;
		$this->facade = $facade;
	}


	public function getUuid()
	{
		return $this->graphNode->value('uuid');
	}


	public function getLabel()
	{
		return $this->graphNode->value('label', '');
	}


	public function getType()
	{
		return $this->graphNode->value('type', '');
	}


	public function getProperty($property)
	{
		return $this->graphNode->value($property, '');
	}


	public function getProperties()
	{
		return $this->graphNode->values();
	}


	public function getEdges()
	{
		if (!$this->edges) {
			$this->edges = $this->facade->getRelationships($this->getUuid());
		}
		return $this->edges;
	}


	public function setLabel($label)
	{
		$this->facade->setNodeProperty($this->getUuid(), 'label', $label);
	}


	public function setProperty($property, $value)
	{
		$this->facade->setNodeProperty($this->getUuid(), $property, $value);
	}


}

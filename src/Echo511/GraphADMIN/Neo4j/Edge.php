<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\Neo4j;

class Edge implements \Echo511\GraphADMIN\IEdge
{

	/**
	 * @var \GraphAware\Bolt\Result\Type\Relationship
	 */
	private $relationship;

	/**
	 * @var \Echo511\GraphADMIN\Neo4j\GraphFacade
	 */
	private $facade;

	private $source;

	private $target;


	public function __construct(\GraphAware\Common\Type\Relationship $relationship, \Echo511\GraphADMIN\Neo4j\GraphFacade $facade)
	{
		if (! $relationship instanceof \GraphAware\Bolt\Result\Type\Relationship) {
			throw new \RuntimeException('Support for Bolt only.');
		}
		$this->relationship = $relationship;
		$this->facade = $facade;
	}


	public function getUuid()
	{
		return $this->relationship->value('uuid');
	}


	public function getSource()
	{
		if (!$this->source) {
			$id = $this->relationship->startNodeIdentity();
			$this->source = $this->facade->fetchNodeById($id);
		}
		return $this->source;
	}


	public function getTarget()
	{
		if (! $this->target) {
			$id = $this->relationship->endNodeIdentity();
			$this->target = $this->facade->fetchNodeById($id);
		}
		return $this->target;
	}


	public function getLabel()
	{
		return $this->relationship->value('label', '');
	}


	public function getType()
	{
		return $this->relationship->value('type', '');
	}


	public function setLabel($label)
	{
		$this->facade->setEdgeProperty($this->getUuid(), 'label', $label);
	}


	public function setSource(\Echo511\GraphADMIN\INode $source)
	{
		$this->facade->redirectEdge(
			$this->getUuid(),
			$source->getUuid(),
			TRUE
		);
	}


	public function setTarget(\Echo511\GraphADMIN\INode $target)
	{
		$this->facade->redirectEdge(
			$this->getUuid(),
			$target->getUuid(),
			FALSE
		);
	}


}

<?php

namespace Echo511\GraphADMIN\LeanMapper\Model;

use Echo511\GraphADMIN\INode;
use Echo511\GraphADMIN\LeanMapper\Loader\EdgesLoader;
use Echo511\GraphADMIN\LeanMapper\ResultProxy\EdgesResultProxy;
use LeanMapper\Entity;

/**
 * @property int $id
 * @property string $label
 * @property string $type
 * @property string $description
 * @property Edge[] $edges m:belongsToMany(related_node)
 */
class Node extends Entity implements INode
{

	/** @var EdgesLoader */
	private $edgesLoader;

	public function __construct($arg = null, EdgesLoader $edgesLoader)
	{
		parent::__construct($arg);
		$this->edgesLoader = $edgesLoader;
	}



	public function getId()
	{
		return $this->row->id;
	}



	public function getLabel()
	{
		return $this->row->label;
	}



	public function getType()
	{
		return $this->get('type');
	}



	public function getProperties()
	{
		return array(
		    'type' => $this->getProperty('type'),
		    'description' => $this->getProperty('description'),
		);
	}



	public function getProperty($property)
	{
		return $this->row->$property;
	}



	public function getEdges()
	{
		$resultProxy = $this->row->getResultProxy(EdgesResultProxy::getClass());
		if (!$resultProxy->hasPreloadedEdges()) {
			$this->edgesLoader->preloadEdges($resultProxy);
			$resultProxy->markEdgesAsPreloaded();
		}
		return $this->getValueByPropertyWithRelationship('edges');
	}



	public function setLabel($label)
	{
		$this->row->label = $label;
	}



	public function setProperty($property, $value)
	{
		$this->row->$property = $value;
	}



}

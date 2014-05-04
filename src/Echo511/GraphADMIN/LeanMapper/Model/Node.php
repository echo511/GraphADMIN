<?php

namespace Echo511\GraphADMIN\LeanMapper\Model;

use Echo511\GraphADMIN\INode;
use Echo511\GraphADMIN\LeanMapper\Loader\EdgesLoader;
use Echo511\GraphADMIN\LeanMapper\ResultProxy\EdgesResultProxy;
use LeanMapper\Entity;
use LeanMapper\Row;

/**
 * Node implementation.
 * @author Nikolas Tsiongas
 * 
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

	/**
	 * @param Row|null $arg
	 * @param EdgesLoader $edgesLoader
	 */
	public function __construct($arg = null, EdgesLoader $edgesLoader)
	{
		parent::__construct($arg);
		$this->edgesLoader = $edgesLoader;
	}



	public function getId()
	{
		return $this->get('id');
	}



	public function getLabel()
	{
		return $this->get('label');
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
		return $this->get($property);
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
		$this->set('label', $label);
	}



	public function setProperty($property, $value)
	{
		$this->set($property, $value);
	}



}

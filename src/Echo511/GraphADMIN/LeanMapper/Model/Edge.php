<?php

namespace Echo511\GraphADMIN\LeanMapper\Model;

use Echo511\GraphADMIN\IEdge;
use LeanMapper\Entity;

/**
 * Edge implementation.
 * @author Nikolas Tsiongas
 * 
 * @property int $id
 * @property string|null $label
 * @property string|null $type
 * @property string|null $description
 * @property Node $source m:hasOne(source)
 * @property Node $target m:hasOne(target)
 */
class Edge extends Entity implements IEdge
{

	public function getId()
	{
		return $this->get('id');
	}



	public function getLabel()
	{
		return $this->get('label');
	}



	public function getSource()
	{
		return $this->get('source');
	}



	public function getTarget()
	{
		return $this->get('target');
	}



	public function getType()
	{
		return $this->get('type');
	}



	public function setLabel($label)
	{
		$this->set('label', $label);
		return $this;
	}



	public function setSource(\Echo511\GraphADMIN\INode $source)
	{
		$this->set('source', $source);
		return $this;
	}



	public function setTarget(\Echo511\GraphADMIN\INode $target)
	{
		$this->set('target', $target);
		return $this;
	}



}

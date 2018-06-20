<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN;

/**
 * Node in the graph.
 * @author Nikolas Tsiongas
 */
interface INode
{


	/** @return string */
	public function getUuid();


	/** @return string */
	public function getLabel();


	public function getType();


	/** @return string */
	public function getProperty($property);


	/** @return array */
	public function getProperties();


	/**
	 * Return edges related or relating to node.
	 * @return IEdge[]
	 */
	public function getEdges();


	/** @param string $label */
	public function setLabel($label);


	/**
	 * @param string $property
	 * @param string $value
	 */
	public function setProperty($property, $value);


}

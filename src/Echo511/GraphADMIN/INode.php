<?php

namespace Echo511\GraphADMIN;

/**
 * Node in the graph.
 * @author Nikolas Tsiongas
 */
interface INode
{

	/** @return int */
	function getId();

	/** @return string */
	function getLabel();

	function getType();

	/** @return string */
	function getProperty($property);

	/** @return array */
	function getProperties();

	/**
	 * Return edges related or relating to node.
	 * @return IEdge[]
	 */
	function getEdges();

	/** @param string $label */
	function setLabel($label);

	/**
	 * @param string $property
	 * @param string $value
	 */
	function setProperty($property, $value);
}

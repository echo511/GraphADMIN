<?php

namespace Echo511\GraphADMIN;

/**
 * Edge in the graph.
 * @author Nikolas Tsiongas
 */
interface IEdge
{

	/** @return int */
	function getId();

	/** @return INode */
	function getSource();

	/** @return INode */
	function getTarget();

	/** @return string */
	function getLabel();

	/** @return string */
	function getType();

	/** @param string $label */
	function setLabel($label);

	/** @param INode $source */
	function setSource(INode $source);

	/** @param INode $target */
	function setTarget(INode $target);
}

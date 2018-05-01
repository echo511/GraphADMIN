<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN;

/**
 * Edge in the graph.
 * @author Nikolas Tsiongas
 */
interface IEdge
{


	/** @return int */
	public function getId();


	/** @return INode */
	public function getSource();


	/** @return INode */
	public function getTarget();


	/** @return string */
	public function getLabel();


	/** @return string */
	public function getType();


	/** @param string $label */
	public function setLabel($label);


	/** @param INode $source */
	public function setSource(INode $source);


	/** @param INode $target */
	public function setTarget(INode $target);


}

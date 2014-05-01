<?php

namespace Echo511\GraphADMIN;

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
	
	function setLabel($label);
	
	function setSource(INode $source);
	
	function setTarget(INode $target);
}

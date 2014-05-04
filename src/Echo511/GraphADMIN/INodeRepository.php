<?php

namespace Echo511\GraphADMIN;

/**
 * Node repository.
 * @author Nikolas Tsiongas
 */
interface INodeRepository
{

	/** @return INode */
	function createInstance();

	/** @return INode */
	function getById($id);

	/** @return INode */
	function getByLabel($label);

	/** @return INode[] */
	function getByLabelTypehint($label);

	/** @return INode */
	function getRandom();

	/** @param INode $node */
	function persist(INode $node);

	/** @param INode $node */
	function delete(INode $node);
}

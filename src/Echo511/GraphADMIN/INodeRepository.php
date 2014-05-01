<?php

namespace Echo511\GraphADMIN;

interface INodeRepository
{

	/**
	 * @return INode
	 */
	function createInstance();

	/**
	 * @return INode
	 */
	function getById($id);

	/**
	 * @return INode
	 */
	function getByLabel($label);
	
	/**
	 * @return INode[]
	 */
	function getByLabelTypehint($label);

	function persist(INode $node);

	function delete(INode $node);
}

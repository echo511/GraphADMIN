<?php

namespace Echo511\GraphADMIN;

/**
 * Edge repository.
 * @author Nikolas Tsiongas
 */
interface IEdgeRepository
{

	/** @return IEdge */
	function createInstance();

	/** @return IEdge */
	function getById($id);

	/** @param IEdge $edge */
	function persist(IEdge $edge);

	/** @param IEdge $edge */
	function delete(IEdge $edge);
}

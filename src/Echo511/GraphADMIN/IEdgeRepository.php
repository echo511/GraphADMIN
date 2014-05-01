<?php

namespace Echo511\GraphADMIN;

interface IEdgeRepository
{

	/**
	 * Create new instance of Edge. Does not store in DB.
	 * @return IEdge
	 */
	function createInstance();

	/**
	 * @return IEdge
	 */
	function getById($id);

	/**
	 * Store Edge in DB or update changes.
	 */
	function persist(IEdge $edge);

	/**
	 * Remove Edge from DB.
	 */
	function delete(IEdge $edge);
}

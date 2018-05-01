<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN;

/**
 * Edge repository.
 * @author Nikolas Tsiongas
 */
interface IEdgeRepository
{


	/** @return IEdge */
	public function createInstance();


	/** @return IEdge[] */
	public function getAll();


	/** @return IEdge */
	public function getById($id);


	/** @param IEdge $edge */
	public function persist(IEdge $edge);


	/** @param IEdge $edge */
	public function delete(IEdge $edge);


}

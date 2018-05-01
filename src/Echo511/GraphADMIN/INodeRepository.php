<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN;

/**
 * Node repository.
 * @author Nikolas Tsiongas
 */
interface INodeRepository
{


	/** @return INode */
	public function createInstance();


	/** @return INode[] */
	public function getAll();


	/** @return INode */
	public function getById($id);


	/** @return INode|FALSE */
	public function getByLabel($label);


	/** @return INode[] */
	public function getByLabelTypehint($label);


	/** @return INode */
	public function getRandom();


	/** @param INode $node */
	public function persist(INode $node);


	/** @param INode $node */
	public function delete(INode $node);


}

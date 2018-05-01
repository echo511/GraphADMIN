<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\LeanMapper\Model;

use Echo511\GraphADMIN\INode;
use Echo511\GraphADMIN\INodeRepository;
use Echo511\GraphADMIN\LeanMapper\EntityFactory;
use Echo511\GraphADMIN\LeanMapper\Repository\NodeRepository as LMNodeRepository;

/**
 * Node repository. Proxy class for LeanMapper internal repository.
 * @author Nikolas Tsiongas
 */
class NodeRepository  implements INodeRepository
{

	/** @var EntityFactory */
	private $entityFactory;

	/** @var LMNodeRepository */
	private $repository;


	public function __construct(LMNodeRepository $repository, EntityFactory $entityFactory)
	{
		$this->entityFactory = $entityFactory;
		$this->repository = $repository;
	}


	public function createInstance()
	{
		return $this->entityFactory->createEntity('Echo511\GraphADMIN\LeanMapper\Model\Node');
	}


	public function getAll()
	{
		return $this->repository->getAll();
	}


	public function getById($id)
	{
		return $this->repository->getById($id);
	}


	public function getByLabel($label)
	{
		return $this->repository->getByLabel($label);
	}


	public function getByLabelTypehint($label)
	{
		return $this->repository->getByLabelTypehint($label);
	}


	public function getRandom()
	{
		return $this->repository->getRandom();
	}


	public function persist(INode $node)
	{
		if (!$node instanceof Node) {
			throw new \InvalidArgumentException();
		}
		$this->repository->persist($node);
	}


	public function delete(INode $node)
	{
		if (!$node instanceof Node) {
			throw new \InvalidArgumentException();
		}
		$this->repository->delete($node);
	}


}
